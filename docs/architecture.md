# Architecture

## 1. Architecture Overview

This is a **procedural PHP** application — there are no classes, no framework, and no
ORM. "Architecture" here means: how the files are organized, and which file is
responsible for which of these five things:

1. **UI / presentation** — HTML, printed directly from `.php` files or from shared
   partials under `includes/views/`.
2. **Request processing** — reading `$_POST`/`$_GET`/`$_FILES`, checking who's allowed
   to do this, and deciding what to do — lives in `actions/*.php` files.
3. **Business logic** — validation and rules that decide *whether* something is
   allowed — lives in `includes/logic/*.php` (currently built out for the Messages
   module only; see section 15 below for why).
4. **Database access** — prepared-statement functions that read/write MySQL — lives in
   `includes/db/*.php`.
5. **Reusable functions** — small helpers used by more than one file — live in
   `includes/functions.php`, `includes/auth.php`, `includes/flash.php`, and
   `includes/csrf.php`.

Every one of these layers is a plain `.php` file full of plain functions. A function is
called by requiring the file it lives in and calling it by name — there is no
autoloading, no dependency injection, no service container.

## 2. Technology Architecture

```text
Browser
 ↓  (HTTP request)
PHP (executed by the web server — Apache/Nginx+PHP-FPM, or php -S for local dev)
 ↓
The requested .php file runs top to bottom
 ↓
mysqli (config/connection.php holds the one shared $db connection)
 ↓
MySQL
```

There is one detail specific to this project worth understanding before you move any
page: **every HTML page sets a `<base href>` computed from its own URL**, and then
every CSS/JS/image link in the page is written as a plain relative path (`css/style.css`,
`image/logo.png`, `admin/portfolio.php`) rather than a path starting with `/`. This
means:

- Public pages (`includes/header.php`) compute `<base href>` from `dirname($_SERVER['SCRIPT_NAME'])`
  once — correct because every public page lives directly in the project root.
- Admin pages (`includes/admin-head.php`) compute it with `dirname()` applied **twice**,
  because every admin page lives one folder level down, in `admin/`. The code has a
  comment explaining exactly this.

**If you ever move a page to a different folder depth, you must update this
calculation** (or every relative link on that page breaks) — this is the single most
important thing to know about this app's plumbing before restructuring anything.

## 3. Folder Responsibilities

```text
config/
→ Configuration. Just one file: the mysqli connection (config/connection.php).

database/
→ The SQL schema (database/schema.sql) used to set up a new database. Not read by
  the application at runtime.

actions/
→ Request processing. Every file here is something a <form action="..."> points to,
  or something JavaScript sends an AJAX request to. Nothing in actions/ prints a full
  HTML page — each file either redirects, echoes a short status string, or echoes JSON.

admin/
→ The admin-only pages (the "views" a logged-in admin sees). Each page requires
  includes/admin-head.php first, which enforces login before printing anything.

includes/
→ Everything shared.
    includes/header.php, footer.php          → public page layout
    includes/admin-head.php, admin-header.php, admin-footer.php → admin page layout
    includes/functions.php                   → small general-purpose helpers
    includes/auth.php                        → require_login(), require_ajax_login()
    includes/flash.php                       → set_flash(), display_flash()
    includes/csrf.php                        → csrf_token(), csrf_field(), verify_csrf_token()
    includes/db/                             → one file per table group, prepared-statement functions
    includes/logic/                          → business-rule functions (currently: messages only)
    includes/views/                          → shared HTML partials, grouped by module

css/, js/
→ Public and admin stylesheets/scripts. See docs/coding-standards.md for which file
  page-specific vs. shared code belongs in.

image/
→ Uploaded and static images. Three different subfolders are used for three different
  kinds of image (see docs/database.md) — this predates the current refactor and
  hasn't been unified, to avoid rewriting every stored image path in one pass.

(project root)
→ The public-facing pages themselves: index.php, about.php, portfolio.php, photos.php,
  photos-category.php, view-album.php, contact.php, adminLogin.php. These live at the
  root (not in a "public pages" folder) because includes/header.php's <base href>
  calculation assumes that.
```

## 4. Request Lifecycle

What happens when a visitor requests an admin page, e.g. `admin/portfolio.php`:

```text
Browser Request
 ↓
admin/portfolio.php starts executing
 ↓
require includes/admin-head.php
   → connects to the database (config/connection.php)
   → require_login() — redirects to ../index.php and stops if not logged in
   → prints <!doctype html>...<head>...<base href>...<body>
 ↓
require includes/admin-header.php (prints the top navigation bar)
 ↓
require includes/db/images.php, then call get_image_count($db)
 ↓
The page prints its own HTML using that data
 ↓
require includes/admin-footer.php (prints closing scripts and </html>)
 ↓
HTML Response sent to the browser
```

A public page (e.g. `portfolio.php`) follows the same shape, but through
`includes/header.php`/`footer.php` instead, and with no login check.

## 5. Create Flow

Real example: **submitting the contact/booking form** (`contact.php` → `actions/contactSubmit.php`).

```text
contact.php (the <form id="contactForm">)
 ↓
JavaScript (js/script.js) intercepts submit, sends the form as an AJAX POST
 ↓
actions/contactSubmit.php — checks it was a real POST with a 'send' field
 ↓
submit_contact_message($db, $_POST)   (includes/logic/messages.php)
   → validate_contact_submission()  — are all 8 fields present?
   → filter_var(..., FILTER_VALIDATE_EMAIL) — is the email valid?
   → message_email_exists($db, $email)  (includes/db/messages.php) — business rule:
     one message per email address
   → insert_message($db, $data)  (includes/db/messages.php) — the actual INSERT,
     using a prepared statement
 ↓
actions/contactSubmit.php maps the result to a short code (0 / -1 / -2 / 1 / an error
string) and echoes it
 ↓
js/script.js reads that code and shows the visitor a SweetAlert success or error message
```

## 6. Read Flow

Real example: **the admin viewing unread messages** (`admin/inbox.php`).

```text
admin/inbox.php requires includes/admin-head.php (auth + layout), then includes/db/messages.php
 ↓
count_messages_by_status($db, 'unread'), count_messages_by_status($db, 'read'),
get_messages_by_status($db, 'unread')   — three prepared-statement calls
 ↓
The page loops over the returned array and prints one table row per message, using
htmlspecialchars() on every field
 ↓
Two shared partials fill in the repeated parts:
   includes/views/messages/summary-badges.php  (the unread/read counts)
   includes/views/messages/message-details.php (each message's modal content)
```

## 7. Update Flow

Real example: **marking a message as read** (`admin/inbox.php`'s form → `actions/inbox-markAsRead.php`).

```text
Form (inside a message's modal) — POST rowId + markReadBtn + a CSRF token
 ↓
actions/inbox-markAsRead.php
   → require_login()
   → verify_csrf_token() — rejects with 403 if missing/wrong
   → set_message_status($db, $id, 'read')  (includes/db/messages.php) — UPDATE via
     a prepared statement
 ↓
On success: redirect to admin/inbox.php (the message is no longer unread, so it drops
off that list)
```

## 8. Delete Flow

Real example: **deleting a portfolio image** (`admin/portfolio.php`'s delete button).

```text
Admin clicks the trash icon on a table row
 ↓
JavaScript: confirmDelete() (js/delete-form.js) shows a SweetAlert "Are you sure?"
 ↓
If confirmed: an XMLHttpRequest POSTs img_id + filename to actions/deleteImage.php
 ↓
actions/deleteImage.php
   → require_login()
   → DELETE FROM image WHERE id = ? (prepared statement)
   → if that succeeded, unlink() the actual file from image/
 ↓
JavaScript re-fetches the table (fetchImages('all')) so the row disappears without a
full page reload
```

Every delete flow in this app follows the same two-step shape: **delete the database
row, then delete the file from disk** (or vice versa, in `uploadAlbum.php`'s update
path) — never one without the other, to avoid orphaned files or broken image links.

## 9. Authentication Flow

```text
Visitor submits username/password on adminLogin.php
 ↓
actions/login.php
   → looks up the username with a prepared statement
   → password_verify($password, $hashed_password)
   → on success: $_SESSION['loggedin'] = true, redirect to admin/dashboard.php
   → on failure: $_SESSION['login_erro'] = "...", redirect back to adminLogin.php
 ↓
Every subsequent admin page/action calls require_login() or require_ajax_login()
(includes/auth.php), which just checks $_SESSION['loggedin'] === true
 ↓
actions/logout.php clears $_SESSION and destroys the session
```

Full detail in [`docs/modules/authentication.md`](modules/authentication.md).

## 10. Authorization Flow

There is no authorization layer beyond authentication — see
[`docs/features/access-control.md`](features/access-control.md). Every logged-in admin
can do everything; there are no roles or permission levels in this application.

## 11. Error Handling

There is no centralized error handler. The pattern used consistently across the app is:

```text
A database operation fails (returns false / mysqli throws for a rare case like
actions/register.php's duplicate-key insert)
 ↓
The function that called it either:
   a) returns the raw error string up to its caller (includes/db/messages.php's
      functions do this — see set_message_status(), insert_message(), delete_message())
   b) or die()s immediately with a short message (e.g. view-album.php: "Album not found.")
 ↓
The caller either echoes it (AJAX endpoints), or shows it in a flash message
(set_flash("Upload failed: " . $stmt->error) in actions/uploadAlbum.php)
```

This means some raw database error text can reach the browser in failure cases (see
`docs/coding-standards.md` for the standing recommendation on this). It is not hidden
from the visitor, but it's also not exploited anywhere as a way to leak sensitive
schema information beyond what a determined visitor could already guess.

## 12. Reusable Functions

| Function | Lives in | What it does |
|---|---|---|
| `require_login($redirectTo)` | `includes/auth.php` | Redirects to login if the session isn't authenticated (full-page requests) |
| `require_ajax_login()` | `includes/auth.php` | Same check, responds 403 instead (AJAX endpoints) |
| `set_flash($message)` / `display_flash()` | `includes/flash.php` | One-time session status message |
| `csrf_token()` / `csrf_field()` / `verify_csrf_token()` | `includes/csrf.php` | CSRF token generation, rendering, verification |
| `sanitize_upload_filename()` | `includes/functions.php` | Strips an uploaded filename down to safe URL characters |
| `url_encode_path()` | `includes/functions.php` | URL-encodes just the filename part of a stored image path |
| `category_label()` | `includes/functions.php` | One place that maps a category slug (`wedding`) to its display text (`Wedding / Prenuptial`) |
| `get_all_images()`, `get_images_by_category()`, `get_image_count()` | `includes/db/images.php` | Read access to the `image` table |
| `get_album_by_id()`, `get_album_images()`, `get_album_count()` | `includes/db/albums.php` | Read access to the `album`/`album_img` tables |
| `get_messages_by_status()`, `count_messages_by_status()`, `message_email_exists()`, `insert_message()`, `set_message_status()`, `delete_message()` | `includes/db/messages.php` | All database access for `contactData` |
| `validate_contact_submission()`, `submit_contact_message()` | `includes/logic/messages.php` | The contact form's validation and business rules |

## 13. Reusable UI Components

| Component | Lives in | Used by |
|---|---|---|
| Public page shell | `includes/header.php` / `footer.php` | Every public page |
| Admin page shell | `includes/admin-head.php` / `admin-header.php` / `admin-footer.php` | Every admin page |
| Message summary badges | `includes/views/messages/summary-badges.php` | `admin/inbox.php`, `admin/inbox-read.php` |
| Message detail block | `includes/views/messages/message-details.php` | Same two pages, inside each message's modal |

Other modules (Portfolio Images, Albums) don't yet have their own shared view
components — their HTML is still written inline on each page, since no clearly
duplicated block was found there that would justify one (see
[`docs/development-guide.md`](development-guide.md) for the rule used to decide this).

## 14. Security Architecture

- **SQL injection**: prevented by using prepared statements (`$db->prepare(...)` +
  `bind_param(...)`) for every query that includes a user-supplied value.
- **XSS**: output escaping with `htmlspecialchars()`, plus `rawurlencode()` for
  filenames used inside URLs.
- **CSRF**: `includes/csrf.php`, currently applied to the Messages/Inbox module only.
  See [`docs/features/csrf-protection.md`](features/csrf-protection.md).
- **Authentication**: session-based, `password_hash()`/`password_verify()` with bcrypt.
- **File uploads**: filenames are sanitized before being saved; there is currently no
  server-side MIME-type or file-content check beyond the browser's `accept="image/*"`
  hint on the `<input>` — see the relevant module docs for exactly where this applies.

## 15. Design Principles

- **Procedural PHP.** No classes, no framework. Every unit of reusable code is a plain
  function, grouped into a file by what it's about (auth, flash messages, one table's
  database access, etc.).
- **Separation of concerns.** A page's own `.php` file is responsible for: checking
  auth, gathering data (by calling `includes/db/`/`includes/logic/` functions), and
  printing HTML. It is not responsible for writing SQL itself where a shared function
  already exists for that read.
- **DRY, but not at the cost of clarity.** Code was pulled into a shared function only
  when it was genuinely duplicated in more than one place, or was a security-relevant
  check that needed to behave identically everywhere. Things that only *look* similar
  (e.g. two pages both printing an `<h1>`) were deliberately left inline — see
  `docs/coding-standards.md`'s DRY section for the exact reasoning.
- **Reusability without over-engineering.** `includes/db/` functions always accept the
  `mysqli` connection as a parameter rather than reaching for a global, so they're easy
  to reuse without hidden dependencies — but there's no query builder, no ORM, no
  abstract "repository" layer on top of them.
- **Simplicity.** New code follows the same plain style as the surrounding code, even
  where a "more elegant" pattern exists — this keeps the whole codebase readable by
  someone who only knows core PHP.
- **Security.** Prepared statements and output escaping are the default, not an
  afterthought, in every file created or touched during the refactor. Older files that
  predate this had two real gaps found and fixed (a raw-SQL injection point, a missing
  login check) — see `docs/modules/albums.md` for both.
- **Maintainability.** Business-area code is grouped by module (Portfolio Images,
  Albums, Messages) rather than by technical layer alone, so a developer working on
  "why doesn't album deletion work" can find every relevant file by looking at one
  module's documentation page instead of hunting across the whole `includes/` tree.
