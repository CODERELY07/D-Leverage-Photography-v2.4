# Feature: Session-Based Access Control

## 1. Purpose

The mechanism every admin page and admin action uses to make sure only a logged-in
admin can see or do anything in the admin area. This is distinct from the
**Authentication module** (`docs/modules/authentication.md`), which is about *how a
visitor becomes logged in*; this feature is about *how every other page checks that
they already are*.

## 2. User Flow

This isn't a visitor-facing flow — it's what happens invisibly on every admin request:

```text
A request arrives for an admin page or action
 ↓
require_login() or require_ajax_login() runs (includes/auth.php)
 ↓
Is $_SESSION['loggedin'] === true?
   Yes → the rest of the page/action runs normally
   No  → full-page request: redirect to the login page, execution stops here
         AJAX request: HTTP 403 response, execution stops here
```

## 3. Requirements

`session_start()` must have already been called before either function runs (both
read `$_SESSION`).

## 4. Business Rules

- There is exactly one check: **is there an active, logged-in session?** There are no
  roles, permission levels, or per-page access rules beyond that single yes/no.
- Two variants exist because the two kinds of endpoint need to respond differently:
  - `require_login($redirectTo = '../index.php')` — for a page a browser navigates to
    directly; redirects, since showing a broken/blank page would be worse than sending
    the visitor to the login screen.
  - `require_ajax_login()` — for an endpoint only ever called by JavaScript
    (`fetch()`/`XMLHttpRequest`); a redirect would be meaningless to a script reading
    the response, so it responds `403 Unauthorized` instead, which the calling
    JavaScript can detect via the response status code.

## 5. Database

None directly — it only reads `$_SESSION`, which was populated at login time by
`actions/login.php` from a database lookup (see
[`docs/modules/authentication.md`](../modules/authentication.md)).

## 6. Files

| File | Role |
|---|---|
| `includes/auth.php` | `require_login()`, `require_ajax_login()` |
| `includes/admin-head.php` | Calls `require_login()` once, on behalf of every admin page that requires it |
| Every `actions/*.php` handler except `login.php`, `register.php`, `contactSubmit.php` | Calls `require_login()` or `require_ajax_login()` directly |

## 7. Logic

Both functions are a single `if` check — there is no more logic to describe. The value
in centralizing them isn't complexity, it's **consistency**: before this was
centralized, the exact same 4-line check was independently copy-pasted into roughly a
dozen files, and one file (`actions/uploadAlbum.php`) was missing it entirely — a real
security gap that existed simply because there was no single place that guaranteed
every write handler had the check. See `docs/architecture.md` section 15.

## 8. UI

Not applicable.

## 9. Validation

Not applicable.

## 10. Authentication

This feature *is* the authentication check used everywhere except the Authentication
module's own login/logout/register pages.

## 11. Authorization

There is no authorization beyond this — see section 4. If this application ever needs
more than one role (e.g. an admin who can only view, not edit), that would be new
functionality built on top of this, not something latent in the current code.

## 12. Error Handling

- `require_login()` failure: an HTTP redirect (`Location:` header) plus `exit()` — no
  error message is shown on the redirect itself (the destination page, typically the
  public home page or the login page, is what the visitor sees).
- `require_ajax_login()` failure: HTTP 403 status code with the plain text body
  `"Unauthorized"`.

## 13. Edge Cases

- The redirect target passed to `require_login()` differs by *how deep the calling
  file is* in the folder structure — every current caller lives exactly one level
  under the project root (`admin/*.php` or `actions/*.php`), so `'../index.php'` is
  correct everywhere it's currently used. **If a new admin page or action is ever
  created at a different folder depth, this default won't be correct** — pass the
  right relative (or absolute) path explicitly in that case.
- `require_ajax_login()` has no customizable redirect — it always responds 403, which
  is correct for every current use, all of which are genuine AJAX endpoints with no
  full-page fallback.

## 14. Testing

```text
- [ ] Every admin/*.php page redirects to the login flow when visited while logged out
- [ ] Every actions/*.php write handler (except login.php, register.php,
      contactSubmit.php) redirects or responds 403 when called while logged out
- [ ] uploadAlbum.php specifically — this one was previously missing the check
      entirely; confirm it now enforces it
- [ ] A logged-in admin can reach every admin page and perform every admin action normally
```

## 15. How to Modify

To protect a new admin page: require `includes/admin-head.php` before printing
anything — it already calls `require_login()` for you. To protect a new admin
`actions/*.php` handler: call `require_login()` (full-page form) or
`require_ajax_login()` (AJAX endpoint) yourself, right after `session_start()` — it is
**not** automatic just because the page linking to it is protected; every handler
needs its own call. See `docs/development-guide.md` section 9.
