# Module: Authentication

## 1. Purpose

Lets the site's admin log in to reach the protected `admin/` area, and log back out.
Also includes the one-time script used to create the admin account in the first place.

## 2. Users/Roles

There is exactly one role: **admin**. There is no visitor account system — the public
site requires no login at all, and the admin area has no permission levels within it
(see [`docs/features/access-control.md`](../features/access-control.md)).

## 3. Features

- Login form with inline validation error display.
- Session-based "stay logged in" while the browser session lasts.
- Logout (destroys the session).
- A one-time admin-account creation script.

## 4. Pages

| File | Purpose |
|---|---|
| `adminLogin.php` | The login form (public page, at the project root) |
| `actions/login.php` | Handles the login form's POST — verifies credentials, starts the session |
| `actions/logout.php` | Destroys the session, redirects to the login page |
| `actions/register.php` | One-time script that creates the single hardcoded admin account |

## 5. Folder Structure

```text
adminLogin.php
actions/
├── login.php
├── logout.php
└── register.php
includes/
└── auth.php           # require_login() / require_ajax_login() — used by every OTHER
                        # module once a session already exists; not itself part of the
                        # login process
```

## 6. Request Flow

```text
User
 ↓
adminLogin.php (GET) — shows the form; if already logged in, redirects straight to
                        admin/dashboard.php instead
 ↓
User submits username + password
 ↓
actions/login.php (POST)
 ↓
Look up the username with a prepared statement (SELECT id, username, password FROM
admin WHERE username = ?)
 ↓
password_verify($password, $hashed_password)
 ↓
Success: $_SESSION['loggedin'] = true, $_SESSION['id'], $_SESSION['username'] set,
         redirect to admin/dashboard.php
Failure: $_SESSION['login_erro'] set, redirect back to adminLogin.php, which displays it
```

## 7. Business Rules

- A username/password pair must match a row in `admin`, with the password verified
  against its bcrypt hash — there is no "remember me", no password reset, and no
  account lockout after repeated failures.
- If a visitor is already logged in and revisits `adminLogin.php`, they're redirected
  straight to the dashboard instead of seeing the form again.
- The reverse is also true for every public page: `includes/header.php` redirects a
  logged-in admin who visits any public page (`index.php`, `about.php`, etc.) straight
  to `admin/dashboard.php`.

## 8. Database Tables

`admin` only — see [`docs/database.md`](../database.md).

## 9. Database Operations

- `actions/login.php`: `SELECT id, username, password FROM admin WHERE username = ?`
  (prepared statement, not centralized into `includes/db/` — this module has no
  shared database file of its own, since this is the only query it performs).
- `actions/register.php`: `INSERT INTO admin (username, password) VALUES (?, ?)`.

## 10. Validation

`actions/login.php` checks that both `username` and `password` were submitted
non-empty (via `trim()`) before attempting to look anything up; if either is missing,
it redirects back with a field-specific error message (`$_SESSION['username_err']` /
`$_SESSION['password_err']`) rather than attempting the database lookup.

## 11. Authentication

This module *is* authentication — see sections 6–7 above.

## 12. Authorization

Not applicable — see [`docs/features/access-control.md`](../features/access-control.md).

## 13. UI Components

None shared — `adminLogin.php` is a self-contained page with its own inline error
display, not using `includes/header.php`/`admin-head.php` (it has its own minimal
`<head>`, since it needs to render even when nothing else about the admin layout has
loaded yet).

## 14. JavaScript

None. The login form is a plain HTML form submit — no AJAX, no client-side validation
beyond the browser's own handling of unfilled `required`-less text inputs (this form
does not use the `required` attribute; validation is entirely server-side).

## 15. Error Handling

- Wrong username or password: a generic `"Invalid username or password."` message
  (deliberately not saying which one was wrong, to avoid confirming whether a
  username exists).
- Missing fields: field-specific messages shown inline next to the relevant input.
- A database error during the login query: `"Oops! Something went wrong. Please try
  again later."`

## 16. Edge Cases

- Running `actions/register.php` a **second time** causes an uncaught
  `mysqli_sql_exception` (PHP fatal error / HTTP 500), because `admin.username` is
  `UNIQUE` and the script always tries to insert the same hardcoded username
  (`admin`). This is expected, not a bug to fix — the script is meant to run exactly
  once. See the main README's Troubleshooting section.
- There is no way, through the UI, to add a second admin account or change the
  existing password — either would require a direct database change (see
  `docs/database.md` section 11) or new code.
- `actions/register.php` has **no login check** — by design, since it must be runnable
  before any admin account exists. This means it's reachable by anyone who knows the
  URL, until the admin account is created — see Security note below.

## 17. How to Modify

- To change the login redirect target after a successful login, edit
  `actions/login.php`'s `header("Location: ./../admin/dashboard.php");` line.
- To add a "remember me" option or session timeout, this is new functionality — there
  is currently no session-lifetime configuration beyond PHP's own default session
  cookie behavior.
- Do **not** remove `actions/register.php`'s lack of a login check casually without
  understanding it first — it's intentional (there's no account yet to log in with the
  first time it runs) — but see the Security note below for why it should not remain
  reachable indefinitely in a real deployment.
- **Security note**, carried over from the project's security review: `register.php`
  has a hardcoded username/password in its own source code and no protection against
  being run again by anyone who finds the URL before you've created your account. The
  README recommends treating this as a one-time setup step; consider removing or
  relocating this file out of the deployed web root after the admin account has been
  created, since it currently has no other safeguard.

## 18. Testing Checklist

```text
- [ ] Page loads (adminLogin.php shows the form when logged out)
- [ ] Authentication works (correct username/password logs in and redirects to the dashboard)
- [ ] Wrong password shows "Invalid username or password."
- [ ] Missing username/password shows the field-specific error and preserves the
      submitted username
- [ ] Already-logged-in visitor to adminLogin.php is redirected to the dashboard
- [ ] Already-logged-in visitor to any public page is redirected to the dashboard
- [ ] Logout destroys the session and redirects to adminLogin.php
- [ ] After logout, every admin page redirects back to index.php when visited directly
- [ ] Error handling works (a deliberately wrong password doesn't leak whether the
      username itself was valid)
```
