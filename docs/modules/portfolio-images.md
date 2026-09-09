# Module: Portfolio Images

## 1. Purpose

Manages the photos shown on the public portfolio gallery (`portfolio.php`) — upload
new ones, recategorize or replace existing ones, and delete them.

## 2. Users/Roles

- **Public visitors**: read-only, via `portfolio.php`.
- **Admin**: full management, via `admin/portfolio.php`.

## 3. Features

- Public filterable photo grid (filter by category via CSS classes + JavaScript,
  entirely client-side — no page reload).
- Admin table listing every portfolio image, with:
  - a category filter dropdown (server-round-trip, via AJAX),
  - click-to-replace on the thumbnail,
  - inline category change (dropdown, saved immediately),
  - delete with a confirmation dialog.
- Multi-file upload (select and upload several images at once, all assigned to one
  category).
- Duplicate-filename protection on upload.

## 4. Pages

| File | Purpose |
|---|---|
| `portfolio.php` | Public gallery grid (root-level page) |
| `admin/portfolio.php` | Admin management page (table + upload modal) |
| `actions/upload.php` | Handles the multi-file upload form |
| `actions/table-portfolio.php` | AJAX/JSON endpoint that returns the image list, all or filtered by category |
| `actions/updateImage.php` | AJAX endpoint — replaces one image's file |
| `actions/updateCategory.php` | AJAX endpoint — changes one image's category |
| `actions/deleteImage.php` | AJAX endpoint — deletes one image (row + file) |

## 5. Folder Structure

```text
portfolio.php
admin/
└── portfolio.php
actions/
├── upload.php
├── table-portfolio.php
├── updateImage.php
├── updateCategory.php
└── deleteImage.php
includes/
├── db/
│   └── images.php      # get_all_images(), get_images_by_category(), get_image_count()
└── functions.php       # sanitize_upload_filename() — used on every uploaded filename
image/
└── (loose files here)  # where every portfolio image file actually lives
```

## 6. Request Flow

Real example — **the admin uploading new images**:

```text
User (admin) opens the "Upload Portfolio Images" modal on admin/portfolio.php,
picks a category and one or more files
 ↓
Form POSTs to actions/upload.php
 ↓
require_login() — must be logged in
 ↓
For each file:
   sanitize_upload_filename()      (includes/functions.php)
   check it doesn't already exist  (SELECT id FROM image WHERE filename = ?)
   move_uploaded_file() into image/
   INSERT INTO image (filename, category) VALUES (?, ?)
 ↓
set_flash("Image uploaded successfully!"  — or a per-file skip/failure message)
 ↓
Redirect to admin/portfolio.php, which shows the flash message and (via its own
inline JavaScript) fetches the updated table from actions/table-portfolio.php
```

## 7. Business Rules

- **No duplicate filenames**: before inserting, `actions/upload.php` checks whether a
  row with that exact (sanitized) filename already exists; if so, that one file is
  skipped (the rest of a multi-file upload still proceeds).
- **Filenames are sanitized on upload**: `sanitize_upload_filename()` strips anything
  but letters, digits, `_`, and `-` from the name (keeping the extension), so a file
  like `"KEYCHAIN ORDERS.png"` is safe to use directly in a URL.
- **Only three categories are offered in the UI** (`wedding`, `birthday`, `others`) —
  this is enforced only by the `<select>` options in the upload/edit forms, not by the
  database (see `docs/database.md` section 12).
- **Replacing an image** (`actions/updateImage.php`) always generates a brand-new
  filename (`uniqid() . "_" . basename(...)`) rather than reusing the old one, and
  deletes the old file from disk once the new one is saved.

## 8. Database Tables

`image` only — see [`docs/database.md`](../database.md).

## 9. Database Operations

| Operation | Where |
|---|---|
| List all images | `get_all_images($db)` — `includes/db/images.php` |
| List images filtered by category | `get_images_by_category($db, $category)` — same file |
| Count all images | `get_image_count($db)` — same file |
| Check a filename isn't already used | inline in `actions/upload.php` |
| Insert a new image row | inline in `actions/upload.php` |
| Update filename (on replace) | inline in `actions/updateImage.php` |
| Update category | inline in `actions/updateCategory.php` |
| Delete | inline in `actions/deleteImage.php` |

The **write** operations (insert/update/delete) stay inline in their `actions/*.php`
files rather than being pulled into `includes/db/images.php` — only the **read**
operations were centralized, since those were the ones actually duplicated across
multiple pages (`portfolio.php` and `actions/table-portfolio.php` both needed "all
images"; `admin/dashboard.php` and `admin/portfolio.php` both needed a count). See
`docs/architecture.md` section 15 for why write logic wasn't pulled out too — that's a
larger "separate logic from UI" pass this module hasn't been through yet.

## 10. Validation

- Category and file(s) are required on the upload form (`required` attribute); no
  additional server-side check exists beyond that and the duplicate-filename check
  above.
- `actions/updateCategory.php` does not validate that `new_category` is one of the
  three allowed values — it will save whatever string is submitted. Since the value
  normally only comes from the admin page's own `<select>`, this is a latent gap
  rather than an observed problem, but it means a hand-crafted request could store an
  arbitrary category string.

## 11. Authentication

- `admin/portfolio.php`: enforced via `includes/admin-head.php`.
- `actions/upload.php`, `deleteImage.php`: `require_login()` (redirects on failure).
- `actions/table-portfolio.php`, `updateImage.php`, `updateCategory.php`:
  `require_ajax_login()` (responds 403 on failure — appropriate since these are only
  ever called via AJAX/`fetch()`, never by directly visiting the URL).
- `portfolio.php` (public gallery): no authentication — open to everyone.

## 12. Authorization

Not applicable beyond "logged in or not" — see
[`docs/features/access-control.md`](../features/access-control.md).

## 13. UI Components

None shared with other modules. The admin table's rows are built entirely in
JavaScript (`admin/portfolio.php`'s inline `<script>`, using template literals),
because the table content is fetched and re-fetched via AJAX rather than rendered by
PHP on every filter change.

## 14. JavaScript

All inline in `admin/portfolio.php`:
- `fetchImages(filter)` — calls `actions/table-portfolio.php`, builds the table rows.
- Category filter dropdown click handler.
- Delete button click handler — calls the shared `confirmDelete()`
  (`js/delete-form.js`), then an `XMLHttpRequest` to `actions/deleteImage.php`.
- Click-to-replace: clicking a thumbnail opens a hidden file input; selecting a file
  `fetch()`es `actions/updateImage.php`.
- Category `<select>` change: `fetch()`es `actions/updateCategory.php`.
- A submit-button double-click guard on the upload form (disables the button one tick
  after submit, not synchronously, since disabling it inside the `submit` handler
  itself drops the field from the request the browser actually sends).

## 15. Error Handling

- Upload: a per-file `$status` message (success, duplicate-skip, or failure), shown as
  a flash message after redirecting back to `admin/portfolio.php`. With multiple
  files, only the *last* file's status message survives (each iteration overwrites
  `$status`) — a known limitation, not a bug fix target unless requested.
- AJAX endpoints (`table-portfolio.php`, `updateImage.php`, `updateCategory.php`,
  `deleteImage.php`) echo a short plain-text result; the page's JavaScript logs
  failures to the console rather than showing a dedicated failure UI (they generally
  just leave the previous state visible rather than showing an error banner).

## 16. Edge Cases

- Uploading a file whose sanitized name collides with an existing one is silently
  skipped (not overwritten) — see Business Rules above.
- `actions/updateCategory.php` does not validate the category value (see Validation
  above).
- If `move_uploaded_file()` fails (e.g. disk permissions), the database row is never
  inserted — the file and row are always kept in sync in the upload path.

## 17. How to Modify

- To add a new category, update the `<option>` list in **both**
  `admin/portfolio.php`'s upload modal **and** the client-side filter list in the same
  file, plus `category_label()` in `includes/functions.php` if you want a
  human-friendly display label anywhere that uses it (this module's own pages print
  the raw category value, not the label — only the Albums/public-photos pages use
  `category_label()`).
- To centralize the write operations into `includes/db/images.php` (matching what was
  already done for reads), follow the pattern already used in
  `includes/db/messages.php` for `insert_message()`/`set_message_status()`/
  `delete_message()` — return `true` or an error string, no `die()`, no `echo`.

## 18. Testing Checklist

```text
- [ ] Public portfolio.php page loads and shows every uploaded image
- [ ] Public category filter buttons show/hide the correct images (client-side)
- [ ] admin/portfolio.php page loads
- [ ] Authentication works (redirects when logged out; AJAX endpoints 403 when logged out)
- [ ] Create: uploading one file works; uploading multiple files at once works
- [ ] Create: uploading a file with a duplicate (sanitized) filename is skipped, not overwritten
- [ ] Read: the admin table shows every image; filtering by category via the dropdown works
- [ ] Update: clicking a thumbnail and choosing a new file replaces it (old file is deleted)
- [ ] Update: changing the category dropdown saves immediately
- [ ] Delete: deleting an image removes both the database row and the file on disk
- [ ] Validation works (category + file are required on the upload form)
- [ ] Error handling works (a failed upload shows a status message)
- [ ] Database operations work (verify directly in the database, not just the UI)
- [ ] AJAX requests work (check the Network tab, not just visual behavior)
```
