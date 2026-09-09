# Coding Standards

These are the conventions actually followed across this codebase — written down so
new code matches old code, not a wish-list of practices the project doesn't use yet.

## PHP

**Naming**
- Functions: `snake_case`, verb-first, describing exactly what they do —
  `get_album_by_id()`, `set_message_status()`, `require_login()`,
  `sanitize_upload_filename()`. Boolean-returning "does this exist" functions use a
  noun-then-verb shape instead, which reads naturally as a question:
  `message_email_exists()`.
- Variables: `camelCase` for most variables (`$albumId`, `$imageCount`), but
  `$_POST`/`$_SESSION` array keys and some older variables use `snake_case`
  (`$album_id`, `$img_id`) — both exist side by side in this codebase; match whichever
  convention the file you're editing already uses rather than mixing both in one file.
- Files: match the thing they contain — `includes/db/messages.php` holds functions for
  the `messages` module's database access; `actions/deleteMessages.php` is what a
  delete-message request POSTs to. Existing `actions/`/`admin/` filenames use a mix of
  `camelCase` and `kebab-case` inherited from before the refactor
  (`deleteMessages.php`, `inbox-markAsRead.php`) — new files in those folders should
  prefer `kebab-case` or a short lowercase name (matching the newer files:
  `table-portfolio.php`, `dashboard.php`) rather than introducing a third style.

**Functions**
- Every shared function is wrapped in `if (!function_exists('name')) { ... }`. This
  lets the same file be `require_once`'d from multiple entry points without a
  "Cannot redeclare function" fatal error, and is used consistently in every file
  under `includes/`.
- A function takes the `mysqli $db` connection as its first parameter — it never
  reaches for a global `$db`. This keeps `includes/db/*.php` functions usable from
  any file that already has its own `$db`.
- Type-hint parameters and return types where the existing code already does (see
  `includes/db/*.php` — `mysqli $db`, `int $id`, `: array`, `: ?array`, `: bool`).
  Older files predating the refactor (`actions/uploadAlbum.php`,
  `actions/updateCategory.php`) don't type-hint — that's acceptable there, but new
  functions should.

**Includes**
- Use `__DIR__ . '/relative/path.php'` for every `require`/`require_once` in a file
  that could be reached from more than one directory depth (which, in this app, is
  every file — admin pages are one level deep, actions are one level deep, public
  pages are at the root). A bare `'connection.php'` broke exactly this way once during
  the folder restructuring; `__DIR__` makes the path unambiguous regardless of which
  script included the file.
- `require_once` for anything that defines functions (you never want it loaded twice);
  plain `require`/`include` only where the original code already used it and it's
  provably safe (rare in this codebase — most includes are `require_once`).

**Error handling**
- Database write functions in `includes/db/*.php` return `true` on success or the raw
  mysqli error string on failure — never `false` alone, since the caller often wants to
  show the actual error (e.g. in a flash message). Callers check `$result === true`.
- `die()` is used sparingly, only for truly can't-continue cases with no form data to
  preserve (e.g. `view-album.php`: `die("Album not found.")` for an invalid ID in the
  URL) — never inside a function meant to be reusable.
- Don't let a raw exception surface as an unhandled PHP fatal error where it's
  avoidable — e.g. a `try`/`catch` around a genuinely-expected failure case. (One
  known exception: `actions/register.php` will fatal-error with an uncaught
  `mysqli_sql_exception` if run a second time — documented as expected behavior in
  the README's Troubleshooting section, not something to silently catch, since the
  script should not be run twice at all.)

**Type declarations**
- Use scalar/array type hints (`string`, `int`, `bool`, `array`) and return types on
  new functions, matching `includes/db/`, `includes/logic/`, `includes/auth.php`,
  `includes/flash.php`, and `includes/csrf.php`. Nullable return (`?array`) is used
  where "not found" is a valid, expected outcome (`get_album_by_id()`).

**Prepared statements**
- **Required** for every query that includes a value from `$_POST`, `$_GET`,
  `$_FILES`, or any other user-controlled source. Use `?` placeholders and
  `bind_param('type', $var)`. This is non-negotiable — the one place it wasn't
  followed (`actions/delete_album.php`, before the DRY pass) was a real SQL-injection
  vulnerability.
- A hardcoded literal with no user input at all (e.g. `"SELECT DISTINCT category FROM
  image"` in `portfolio.php`) doesn't need a prepared statement — but if in doubt, use
  one anyway; it costs almost nothing.

**Input validation**
- Validate before you write to the database, not after. See
  `includes/logic/messages.php` for the pattern: check required fields, check format
  (email), check business rules (duplicate), *then* insert.
- Escape on output (`htmlspecialchars()`), not on input — this project stores raw
  values in the database and escapes when printing, which is why
  `actions/uploadAlbum.php` has an explicit comment warning against escaping twice
  (it previously turned `"Ej & Dyessel"` into `"Ej &amp; Dyessel"` in the database).

## HTML

**Semantic HTML**
- Use `<main>`, `<header>`, `<footer>`, `<nav>`, `<table>` for tabular data — the
  existing pages already do this consistently; match it rather than defaulting to
  `<div>` for everything.

**Form structure**
- Every state-changing `<form>` sets `method="POST"`.
- File-upload forms set `enctype="multipart/form-data"`.
- A required field gets the HTML `required` attribute as a first line of defense, even
  though the real validation happens server-side.

**Accessibility basics**
- Icon-only buttons get `aria-label` (see `icon-btn` buttons throughout the admin UI).
- Modals get `aria-labelledby`/`aria-hidden` (Bootstrap's own convention, followed
  throughout).
- Images get an `alt` attribute — even a generic one (`alt="Album Image"`) is present
  everywhere; a more specific `alt` is preferred when the data is available (see
  `photos-category.php`'s `alt="<?= htmlspecialchars($row['album_name']) ?>"`).

**Output escaping**
- Every value printed from the database or from user input goes through
  `htmlspecialchars()`. Filenames used inside a `src`/`href` additionally go through
  `rawurlencode()` (or `url_encode_path()` for a value that's a path with a leading
  directory, like `album_img.img`).

## CSS

**Naming**
- Class names are plain, descriptive, lowercase-with-hyphens (`icon-btn`,
  `page-heading`, `admin-box-count`) — no BEM, no CSS-in-JS, no utility-class
  framework beyond Bootstrap's own classes, which are used alongside the custom ones.

**Organization**
- Two files total: `css/style.css` (public site) and `css/admin.css` (admin area) —
  no per-page stylesheets. Each file is organized with section-header comments (see
  the existing numbered sections in `css/style.css`, e.g. "11. ALBUM LISTING
  (photos-category.php)") — add new rules under a matching or new section comment
  rather than appending to the end of the file.

**Reusable styles**
- Shared components (buttons, modals, badges, alerts) use Bootstrap's own classes
  first; custom classes on top of them only for this site's specific look (colors,
  spacing).

**Page-specific styles**
- Page-specific rules stay in the same shared stylesheet, under a comment naming the
  page — there is no mechanism for a stylesheet scoped to one page only, other than
  the rare inline `<style>` block (`admin/album-view.php` has one, for its own small
  `.image-card` hover-button positioning).

## JavaScript

**Naming**
- Functions and variables: `camelCase` (`confirmDelete`, `toggleMobileMenu`,
  `fetchImages`). Constants that shouldn't change: `UPPER_SNAKE_CASE`
  (`CSRF_TOKEN`).

**Organization**
- Shared behavior lives in its own file under `js/` (`script.js` for public,
  `adminScript.js`/`delete-form.js` for admin-wide behavior).
- One page's own behavior is an inline `<script>` at the bottom of that page's HTML.
  Move it to a shared file only once a second page needs the same code.

**DOM handling**
- Plain `document.querySelector`/`addEventListener` — no framework. jQuery (`$`) is
  loaded and used in exactly one place: the "Add Images to Existing Album" AJAX form
  in `js/adminScript.js`, which uses `$.ajax()`. Everything else uses vanilla
  `fetch()` or `XMLHttpRequest`.

**AJAX/fetch**
- Both `fetch()` and `XMLHttpRequest` are used in this codebase (not one exclusively)
  — match whichever the file you're editing already uses, rather than mixing both in
  one page. New code can prefer `fetch()`, which is the more modern of the two and
  already used in `admin/portfolio.php`'s image-replace/category-change handlers.

**Error handling**
- AJAX failures are caught and logged with `console.error(...)`, and where relevant,
  shown to the user via a SweetAlert (`Swal.fire(...)`). Silent failures (a caught
  error with nothing shown or logged) don't appear anywhere in this codebase and
  shouldn't be introduced.

## SQL

**Naming**
- Table names: lowercase, mostly singular (`image`, `album`, `admin`), with two
  exceptions already in the schema (`contactData` — mixed case; `album_img` —
  plural-ish "images" abbreviated). Match the existing table's exact name and casing
  exactly when writing queries — MySQL table-name case-sensitivity depends on the
  server's configuration, and getting it wrong can work locally and fail elsewhere.
- Column names: `snake_case` (`album_name`, `phonenumber` — note some multi-word
  columns like `phonenumber` were never split with an underscore; match the schema
  exactly, don't "fix" the naming inconsistently in new queries against old columns).

**Prepared statements**
See the PHP section above — required for anything with user input.

**Query organization**
- One query per `includes/db/*.php` function, doing one thing. Don't combine an
  unrelated read and write in one function.

**Transactions**
Not currently used anywhere in this codebase (see `docs/database.md` section 8). If a
future change needs to make two writes atomic, that would be new territory for this
project — introduce `$db->begin_transaction()` / `$db->commit()` /
`$db->rollback()` deliberately and document why in the module doc.

## Security

- Prepared statements for all user-supplied query values (no exceptions).
- `htmlspecialchars()` on all output derived from the database or user input.
- `require_login()`/`require_ajax_login()` on every admin page and admin action
  handler — no exceptions, including AJAX endpoints.
- CSRF tokens (`includes/csrf.php`) on state-changing forms in modules that have been
  updated to use them (currently: Messages/Inbox — see
  `docs/features/csrf-protection.md` for the pattern and its current scope).
- Uploaded filenames are sanitized (`sanitize_upload_filename()`) before being used as
  a real filesystem path.
- Never commit real database credentials — `config/connection.php` should hold
  placeholder or local-only values in any shared copy of this project.

## Comments

- Comments explain **why**, not what — the existing codebase's best comments explain a
  non-obvious reason for a decision (see `includes/functions.php`'s
  `sanitize_upload_filename()` comment explaining *why* it exists: unescaped filenames
  with spaces silently broke `<img src>` tags). A comment that just restates the code
  in English (`// increment i` above `$i++`) doesn't appear anywhere in this codebase
  and shouldn't be added.
- A comment explaining a fix (`// Was previously a raw string-interpolated query...`)
  is left in place after the fix, not deleted — it prevents the same mistake from
  being reintroduced later, and several exist throughout the code specifically for
  this reason.

## DRY

> Do not duplicate reusable logic.

If the exact same query, validation rule, or HTML block appears in two or more files,
pull it into a shared function (`includes/db/`, `includes/logic/`) or a shared view
partial (`includes/views/`). This project's DRY pass found and fixed several real
examples of this — see `includes/db/images.php`'s and `includes/db/albums.php`'s file
comments for what they replaced.

> But also: do not create unnecessary abstractions.

Two pieces of code that merely *look* similar, but serve genuinely different purposes,
should stay separate. Real examples from this project where duplication was found and
**deliberately left alone**:
- `date('F j, Y')` appears identically on 3 admin pages — it's a single built-in PHP
  call, not logic; wrapping it in a custom function would add indirection for no
  benefit.
- The `<span class="eyebrow">`/`<h1>` "page heading" markup appears on 6 admin pages
  with the same 2-line shape, but a different, non-trivial right-hand side on every
  page (a date, a count-plus-button, a back-link) — extracting it would mean passing
  raw HTML as a function parameter, which is harder to read than the current inline
  markup.
- The 3 category `<option>` tags (`wedding`/`birthday`/`others`) are hardcoded in two
  different forms — with only 3 rarely-changing items and two forms that pre-select
  the current value differently, a shared partial would save very little typing at
  the cost of an extra indirection.

The rule used throughout this project: extract something only when (1) it's genuinely
reused, (2) it has one clear responsibility, (3) extracting it makes the code *easier*
to follow, not just shorter, and (4) it reduces real, ongoing maintenance risk (a rule
that must stay correct in more than one place is a maintenance risk; a coincidental
two-line similarity is not).
