# Feature: CSRF Protection

## 1. Purpose

Protects state-changing admin actions from **Cross-Site Request Forgery** — a class of
attack where a malicious page tricks a logged-in admin's browser into submitting a
request to this site without the admin intending it (since browsers automatically
attach a logged-in visitor's session cookie to any request to a site they're logged
into, even one triggered by a different website). A random, unguessable token is
required on every protected request; a request without the correct token is rejected.

**Important — current scope**: this protection is implemented and fully applied to
the **Messages / Inbox module only** (mark-as-read/unread, delete message). It is
**not yet applied** to the Portfolio Images or Albums modules' forms and AJAX calls.
This is a deliberate, tracked decision — see section 15.

## 2. User Flow

Invisible to the visitor when everything is working correctly:

```text
Admin opens admin/inbox.php or admin/inbox-read.php
 ↓
The page calls csrf_token() (creating one, if this session doesn't have one yet) and
embeds it — as a hidden form field (csrf_field()) for the "mark as read/unread" form,
or as a JavaScript constant for the AJAX delete action
 ↓
Admin submits the form / clicks delete
 ↓
The request includes the token
 ↓
actions/inbox-markAsRead.php or actions/deleteMessages.php calls verify_csrf_token()
 ↓
Token matches the one in the session → the action proceeds normally
Token missing or wrong → HTTP 403, the action does not happen
```

## 3. Requirements

`session_start()` must be active (the token is stored in `$_SESSION['csrf_token']`).

## 4. Business Rules

- One token per session, generated the first time `csrf_token()` is called and reused
  for the rest of that session (not regenerated per-request or per-page).
- The token is compared with `hash_equals()`, not `==` or `===` — this avoids a
  timing-attack that could otherwise let an attacker guess the token one character at
  a time by measuring response times.

## 5. Database

None — the token lives entirely in the PHP session.

## 6. Files

| File | Role |
|---|---|
| `includes/csrf.php` | `csrf_token()`, `csrf_field()`, `verify_csrf_token()` |
| `admin/inbox.php` | Renders the token as a hidden field in the "mark as read" form |
| `admin/inbox-read.php` | Renders the token as a hidden field (mark as unread form) and as a JS constant (delete AJAX) |
| `actions/inbox-markAsRead.php` | Verifies the token before doing anything |
| `actions/deleteMessages.php` | Verifies the token before doing anything |

## 7. Logic

```php
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): void {
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

function verify_csrf_token(): bool {
    $submitted = $_POST['csrf_token'] ?? '';
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $submitted);
}
```

`random_bytes(32)` is PHP's cryptographically-secure random generator — this is not a
predictable value.

## 8. UI

For a real `<form>`: one hidden `<input>`, added via `<?php csrf_field(); ?>` right
after the opening `<form>` tag. For an AJAX action with no surrounding form
(`admin/inbox-read.php`'s delete button): the token is printed once into a small
`<script>` block as `const CSRF_TOKEN = "...";`, then appended to the AJAX request body
manually.

## 9. Validation

`verify_csrf_token()` is the entire validation — see section 7.

## 10. Authentication

CSRF protection is separate from, and applied **in addition to**,
`require_login()`/`require_ajax_login()` — both `actions/inbox-markAsRead.php` and
`actions/deleteMessages.php` check login first, then CSRF, before doing anything else.

## 11. Authorization

Not applicable.

## 12. Error Handling

A failed check responds `HTTP 403` with a short, specific message
(`"Invalid or expired form submission. Please refresh the page and try again."` for
the mark-as-read form; `"Invalid or expired request."` for the delete action) — this
tells a legitimate admin what to do (refresh) without revealing anything useful to an
attacker.

## 13. Edge Cases

- Because the token is tied to the session (not regenerated per page), it stays valid
  across every page in one browsing session — including both `admin/inbox.php` and
  `admin/inbox-read.php` sharing the same token value, which is expected and correct
  (verified during testing).
- A very long-idle session (past PHP's session lifetime) would have its token expire
  along with the rest of the session — the admin would already be logged out at that
  point (`require_login()` would catch it first), so this isn't a scenario where CSRF
  verification specifically is what a legitimate admin would run into.

## 14. Testing

```text
- [ ] A mark-as-read/unread request with no csrf_token field is rejected (403)
- [ ] A delete-message request with no csrf_token is rejected (403)
- [ ] A request with the real, page-rendered token succeeds for both actions
- [ ] The token stays valid when moving between admin/inbox.php and admin/inbox-read.php
      within the same session
```

## 15. How to Modify — and the known gap

**To extend CSRF protection to the Portfolio Images and Albums modules** (the
recommended next step, not yet done): for each state-changing form, add
`<?php csrf_field(); ?>` right after the opening `<form>` tag (or, for an AJAX-only
action with no surrounding `<form>`, print the token as a JS constant the way
`admin/inbox-read.php` does); then, in the corresponding `actions/*.php` handler, add
`require_once __DIR__ . '/../includes/csrf.php';` and call `verify_csrf_token()`
right after the login check, rejecting with 403 (AJAX) or a flash message + redirect
(full-page form) if it fails. The forms/actions this applies to:

```text
admin/portfolio.php  → actions/upload.php (upload form)
                      → actions/deleteImage.php, updateImage.php, updateCategory.php (AJAX)
admin/albums.php     → actions/uploadAlbum.php (create/edit form)
                      → actions/delete_album.php (delete form)
                      → actions/upload_album_img.php (AJAX)
admin/album-view.php → actions/delete_album_image.php (delete form)
```

This was deliberately left for a follow-up change rather than done in the same pass
that built the mechanism, because those modules' forms and AJAX calls span several
large inline `<script>` blocks that hadn't yet been fully mapped and tested end-to-end
at the time — applying an untested, broad change across many files at once was judged
riskier than shipping the proven pattern on one module first. See
`docs/modules/portfolio-images.md` and `docs/modules/albums.md` for those modules'
current (CSRF-unprotected) state.
