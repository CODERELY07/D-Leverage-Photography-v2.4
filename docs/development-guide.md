# Development Guide

## 1. Before Starting Development

Read, in this order:
1. The main [`README.md`](../README.md) — what the app does and how it's laid out.
2. [`architecture.md`](architecture.md) — how a request flows through the five layers
   (UI, request processing, business logic, database, reusable functions).
3. The `docs/modules/<module>.md` for whatever you're about to touch.

Get a local copy running with real data before changing anything — see the README's
Installation section. You cannot safely verify a change in this app without a working
database, because so much of the UI depends on real rows existing (counts, lists,
foreign-key relationships).

## 2. Understand the Architecture

The short version (full detail in `architecture.md`):

```text
admin/*.php or a root-level page   → the page itself: auth check (transitively), gather
                                       data, print HTML
actions/*.php                       → request processing: read $_POST/$_GET, check
                                       auth + CSRF, call logic/db functions, respond
includes/logic/*.php                → business rules (currently: messages module only)
includes/db/*.php                   → prepared-statement database functions
includes/{auth,flash,csrf,functions}.php → small reusable helpers
includes/views/**                   → shared HTML partials
```

## 3. Find the Correct Module

Match what you're changing against `docs/modules/`:

| You're changing... | Module doc |
|---|---|
| Login, logout, session, the admin account | `modules/authentication.md` |
| The admin landing page / stat counts | `modules/dashboard.md` |
| Portfolio image upload, listing, category, delete | `modules/portfolio-images.md` |
| Albums, album cover, album gallery photos | `modules/albums.md` |
| The contact form, or the admin inbox | `modules/messages-inbox.md` |
| The home page or about page | `modules/public-pages.md` |

If it's a cross-cutting mechanism (login guard, flash messages, CSRF, file upload
sanitizing) instead of one module's own page, check `docs/features/` instead.

## 4. Create a New Module

There is no scaffolding tool — you do this by hand, following the shape every existing
module already uses. Say you're adding a "Testimonials" module (hypothetical — this
does **not** exist in the app today):

```text
Step 1 — Database
Add the table to database/schema.sql, and apply it to your local database by hand.

Step 2 — Database access layer
Create includes/db/testimonials.php with plain functions:
   get_all_testimonials(mysqli $db): array
   insert_testimonial(mysqli $db, array $data): bool|string
   delete_testimonial(mysqli $db, int $id): bool|string
Follow the exact pattern in includes/db/messages.php — prepared statements, return
true or an error string, no HTML, no validation.

Step 3 — Business logic (only if there are real rules)
If there's an actual rule to enforce (e.g. "a testimonial must have a rating between
1 and 5"), add it to includes/logic/testimonials.php, calling into your db/ functions.
If there's no real rule beyond "are the required fields present", you can skip this
layer and do that check directly in the actions/ handler — see
docs/coding-standards.md's DRY section for when a logic file earns its place.

Step 4 — Request processing
Create actions/testimonial-submit.php (public, if visitors submit these) and/or
actions/testimonial-delete.php (admin-only). Every admin action starts with:
   require_once __DIR__ . '/../config/connection.php';
   require_once __DIR__ . '/../includes/auth.php';
   session_start();
   require_login('../index.php');   // or require_ajax_login() for a JSON endpoint

Step 5 — Admin page
Create admin/testimonials.php:
   require_once __DIR__ . '/../includes/admin-head.php';   // connects + auth + layout
   require_once __DIR__ . '/../includes/admin-header.php';
   require_once __DIR__ . '/../includes/db/testimonials.php';
   $testimonials = get_all_testimonials($db);
   // ... print the table using $testimonials ...
   require_once __DIR__ . '/../includes/admin-footer.php';

Step 6 — Add a link to it
Add the new page to includes/admin-header.php's dropdown, and (if it belongs there)
a card on admin/dashboard.php.

Step 7 — Validation
Put required-field checks either in your logic/ layer (step 3) or right in the
actions/ handler if there's no logic/ file for this module.

Step 8 — Authentication / CSRF
Every admin page gets require_login() for free via admin-head.php. Every admin
actions/ handler needs its own require_login()/require_ajax_login() call — it is
NOT automatic just because the page that links to it is protected. Add CSRF
(includes/csrf.php) if the form is a genuine state-changing action — see
docs/features/csrf-protection.md for the exact pattern to copy.

Step 9 — UI
Write the page's HTML directly in admin/testimonials.php, matching the existing
Bootstrap-based markup style of admin/portfolio.php or admin/albums.php (cards, a
data table, a modal for the create/edit form). Only pull shared pieces into
includes/views/ if the exact same block would otherwise be duplicated on a second page.

Step 10 — CSS/JavaScript
Page-specific behavior can be an inline <script> at the bottom of the page (see
admin/portfolio.php for the largest example of this). Only move it into its own
js/*.js file if it needs to run on more than one page.

Step 11 — Test
Manually: create, view, delete, log out and confirm the page redirects, try an
invalid submission and confirm the error path. See "Testing" in each module doc for
the exact checklist shape to copy.

Step 12 — Document
Add docs/modules/testimonials.md using the module template (see any existing file
under docs/modules/ as the template), and link it from README.md's module table.

Step 13 — Commit
See section 16 below.
```

## 5. Create a New CRUD Feature

Same shape as above, condensed:

```text
Database (add/confirm the table)
 ↓
Create  (an INSERT function in includes/db/, called from an actions/ handler)
 ↓
Read    (a SELECT function in includes/db/, called from the page that lists/shows it)
 ↓
Update  (an UPDATE function in includes/db/, called from an actions/ handler)
 ↓
Delete  (a DELETE function in includes/db/, called from an actions/ handler — remember
         to also delete any associated file on disk, and check for a foreign-key
         cascade like album → album_img before assuming child rows need deleting too)
 ↓
Validation (in includes/logic/ if there's a real rule, otherwise inline in the handler)
 ↓
Authorization (require_login() / require_ajax_login() on every handler — there's only
               one role in this app, so "authorization" here just means "logged in")
 ↓
UI      (the admin/*.php page — table + modal form, matching the existing style)
 ↓
Testing (manual — see section 14)
 ↓
Documentation (update or create the module's docs/modules/*.md)
```

## 6. Create a New Form

The pattern every form in this app follows:

```php
<!-- The HTML form (in an admin/*.php page or a public page) -->
<form action="actions/your-handler.php" method="POST" enctype="multipart/form-data">
    <?php csrf_field(); ?>  <!-- only if this is a state-changing admin form; see
                                 docs/features/csrf-protection.md -->
    <input type="text" name="field_name" required>
    <button type="submit">Save</button>
</form>
```

```php
<?php
// actions/your-handler.php
session_start();
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/auth.php';
require_login('../index.php');
// require CSRF check here too, if the form has a csrf_field() above

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // validate, then call an includes/db/ function to save it
    // on success: set_flash("Saved successfully"); header("Location: ../admin/your-page.php"); exit();
    // on failure: set_flash("Save failed: " . $error); header("Location: ../admin/your-page.php"); exit();
}
```

The page you redirect back to should call `display_flash();` near the top of its
markup to show that message once. See `docs/features/flash-messages.md`.

## 7. Add Database Operations

Always in `includes/db/<name>.php`, always a prepared statement, always accepting the
`mysqli $db` connection as the first parameter:

```php
if (!function_exists('get_thing_by_id')) {
    function get_thing_by_id(mysqli $db, int $id): ?array {
        $stmt = $db->prepare("SELECT * FROM things WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }
}
```

Never build a query by concatenating a variable into the SQL string
(`"WHERE id = '$id'"`) — this project had exactly one instance of that
(`actions/delete_album.php`) and it was a real SQL-injection vulnerability, since
fixed. Always use `?` placeholders and `bind_param()`.

## 8. Add Validation

- If the rule is genuinely business logic (e.g. "this email can only submit once"),
  put it in `includes/logic/<module>.php`, following the shape of
  `includes/logic/messages.php`'s `validate_contact_submission()` /
  `submit_contact_message()`.
- If it's a simple "is this field present" check with no module-specific logic file
  yet, it's fine to do it directly in the `actions/` handler — don't create a
  `logic/` file just to hold one check (see `docs/coding-standards.md`'s DRY section).
- Either way, return/collect **errors**, don't `die()` or `echo` from inside the
  validation function itself — let the caller (the `actions/` handler, or the page)
  decide how to report the problem.

## 9. Add Authentication

Every admin page gets this automatically by requiring `includes/admin-head.php` first
— it calls `require_login()` internally. **Every `actions/*.php` handler needs its own
explicit call** — it is not protected just because the page linking to it is:

```php
require_once __DIR__ . '/../includes/auth.php';
session_start();
require_login('../index.php');       // full-page requests: redirects
// — or —
require_ajax_login();                // AJAX/JSON endpoints: responds with 403
```

## 10. Add Authorization

There is currently no role/permission system to extend — see
`docs/features/access-control.md`. If a future feature genuinely needs one (e.g. a
second admin who can only view, not delete), that would be new work, not something
you plug into an existing mechanism.

## 11. Add UI Components

Only pull HTML into `includes/views/<module>/*.php` when the **exact same block**
would otherwise appear on two or more pages — this happened for the messages module's
modal content and summary badges (see `docs/modules/messages-inbox.md`). A component
that only takes generic named variables set by the caller (`$row`, `$unreadCount`,
etc. — no exotic parameter-passing scheme) is the right shape; see the two existing
files under `includes/views/messages/` as the template.

## 12. Add CSS

- **Public site**: `css/style.css`.
- **Admin area**: `css/admin.css`.
- There is no shared/reset stylesheet between the two — they're intentionally
  separate, matching the two different layout shells (`includes/header.php` vs.
  `includes/admin-head.php`).
- Add page-specific rules near a comment that names the page/section they're for (the
  existing files are organized with section-header comments — follow that pattern
  rather than appending to the end of the file).

## 13. Add JavaScript

- **Public site behavior**: `js/script.js`.
- **Admin-wide behavior** (used on more than one admin page — dropdowns, delete
  confirmation): `js/adminScript.js` or `js/delete-form.js`.
- **One page's own behavior** (AJAX table refresh, a modal's edit-mode logic): an
  inline `<script>` at the bottom of that page, before `admin-footer.php` is required
  — see `admin/portfolio.php` for the largest real example of this.
- If a behavior starts appearing on a second page, that's the signal to move it into a
  shared `js/*.js` file — this already happened once, for the delete-confirmation
  dialog (`confirmDelete()` in `js/delete-form.js`, used by three different pages).

## 14. Testing

Practical checklist — see the README's own Testing section for the short version, and
each module's own "Testing Checklist" for the specific one to copy for that area:

```text
- [ ] php -l on every file you touched
- [ ] The page loads for a logged-in admin
- [ ] The page redirects for a logged-out visitor (or returns 403, for an AJAX endpoint)
- [ ] Every form/AJAX action you touched: submit it, then verify the result in both
      the UI and directly in the database
- [ ] Any file upload: confirm the file actually landed in the right folder, not just
      that the database row exists
- [ ] Any delete: confirm both the database row AND the file (if any) are gone
- [ ] The flash message (if any) shows once, then is gone on the next page load
- [ ] Test the failure path too, not just the happy path (wrong password, missing
      field, duplicate entry, etc.)
```

## 15. Documentation

Update the relevant `docs/modules/*.md` or `docs/features/*.md` file whenever you
change:
- what a page does,
- a validation rule or business rule,
- a database function's behavior or signature,
- an authentication/authorization requirement.

You do **not** need to update documentation for a pure refactor that changes no
observable behavior (e.g. moving a query into a shared function that does exactly what
the inline code did before) — but it's worth a note if the *location* of the code
changed, since the docs point at specific files.

## 16. Git Workflow

TODO: this project does not have a documented branching/PR convention — the commits
so far are all directly on `main`. If you're working with others on this project,
agree on one before scaling up collaboration; a reasonable default for a project this
size:

```text
Create a branch for your change
 ↓
Implement it
 ↓
Test manually (section 14)
 ↓
Self-review your own diff
 ↓
Commit, with a message that says what changed and why
 ↓
Push
 ↓
Open a Pull Request (if working with others) or merge to main directly (if solo)
```
