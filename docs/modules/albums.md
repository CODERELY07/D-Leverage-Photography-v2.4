# Module: Albums

## 1. Purpose

Manages photo albums — each with a name, category, external link, and cover photo —
plus the individual gallery photos inside each album. Visitors browse albums by
category on the public site; the admin creates, edits, and deletes them.

## 2. Users/Roles

- **Public visitors**: read-only, via `photos.php` → `photos-category.php` →
  `view-album.php`.
- **Admin**: full management, via `admin/albums.php` and `admin/album-view.php`.

## 3. Features

- Public: browse albums grouped by category, then view every photo in one album.
- Admin: create an album (name, category, link, cover photo), edit one, delete one
  (with its gallery photos automatically removed by the database), and add or remove
  individual gallery photos.
- Client-side search box that filters the admin album table by name.

## 4. Pages

| File | Purpose |
|---|---|
| `photos.php` | Public category picker (Wedding/Prenuptial, Birthday, Others) |
| `photos-category.php` | Public list of albums within one category |
| `view-album.php` | Public single-album viewer (all gallery photos) |
| `admin/albums.php` | Admin album list, with create/edit/delete and "add image" modals |
| `admin/album-view.php` | Admin single-album viewer, with per-photo delete |
| `actions/uploadAlbum.php` | Handles both create and edit (based on whether an album ID was submitted) |
| `actions/upload_album_img.php` | AJAX endpoint — adds one gallery photo to an existing album |
| `actions/delete_album.php` | Deletes an album (row + cover file) |
| `actions/delete_album_image.php` | Deletes one gallery photo (row + file) |

## 5. Folder Structure

```text
photos.php
photos-category.php
view-album.php
admin/
├── albums.php
└── album-view.php
actions/
├── uploadAlbum.php
├── upload_album_img.php
├── delete_album.php
└── delete_album_image.php
includes/
└── db/
    └── albums.php      # get_album_by_id(), get_album_images(), get_album_count()
image/
├── upload-album/       # album cover photos live here
└── uploads/            # album gallery photos live here (a different folder from covers)
```

## 6. Request Flow

Real example — **the admin creating a new album**:

```text
User (admin) opens "Create New Album" on admin/albums.php, fills in name/category/
link, picks a cover image
 ↓
Form POSTs to actions/uploadAlbum.php (no album-id field, since this is a create)
 ↓
require_login() — must be logged in
 ↓
sanitize_upload_filename() on the cover image's name
 ↓
Check the sanitized filename doesn't already exist in image/upload-album/
 ↓
move_uploaded_file() into image/upload-album/
 ↓
INSERT INTO album (album_name, album_link, album_img, album_category) VALUES (...)
 ↓
set_flash("Album uploaded successfully")
 ↓
Redirect to admin/albums.php, which shows the flash message and the new row
```

## 7. Business Rules

- **Create vs. edit is decided by whether `album-id` was submitted** — `0` (or
  missing) means create; a positive integer means edit. Both flows share one file
  (`actions/uploadAlbum.php`).
- **No duplicate cover filenames on create** — if a sanitized filename already exists
  in `image/upload-album/`, the create is rejected with a flash message
  (`"IMAGE ALREADY EXISTS: ..."`), and no row is inserted.
- **Editing without picking a new image keeps the old cover** — the form's file input
  is optional on edit (its `required` attribute is removed by JavaScript once "Edit"
  mode is entered); if no new file is submitted, only the name/link/category columns
  are updated.
- **Editing with a new image replaces the cover file** — the old cover file is deleted
  from `image/upload-album/` once the new one is saved.
- **Deleting an album deletes its gallery photos' database rows automatically**, via
  the `album_img.album_id → album.id ON DELETE CASCADE` foreign key (see
  `docs/database.md` section 5) — the application code does not do this itself.
  **It does not, however, delete those gallery photos' files from disk** — only the
  album's own cover file is explicitly unlinked by `actions/delete_album.php`. This is
  a known gap (see Edge Cases below).
- **Gallery photos are added one at a time**, to an already-existing album, via a
  separate "Add Images to Existing Album" form — they are not part of the
  create/edit-album form.

## 8. Database Tables

`album` and `album_img` — see [`docs/database.md`](../database.md).

## 9. Database Operations

| Operation | Where |
|---|---|
| Get one album by ID | `get_album_by_id($db, $id)` — `includes/db/albums.php`, used by both `view-album.php` and `admin/album-view.php` |
| Get one album's gallery photos | `get_album_images($db, $albumId)` — same file, same two callers |
| Count all albums | `get_album_count($db)` — same file |
| List every album, newest first | inline `SELECT * FROM album ORDER BY id DESC` in `admin/albums.php` — not centralized, since it's the only place that needs the *full* list with *all* columns for a table display; the shared functions above only cover the two queries that were genuinely duplicated across files |
| List albums by category | inline in `photos-category.php` (prepared statement) |
| Create/update album | inline in `actions/uploadAlbum.php` |
| Delete album | inline in `actions/delete_album.php` (prepared statement — **this was previously raw SQL string interpolation and a real SQL-injection vulnerability**, fixed during the security review) |
| Add/delete gallery photo | inline in `actions/upload_album_img.php` / `actions/delete_album_image.php` |

## 10. Validation

- Name, link, and category are required on the create/edit form (`required`
  attribute); cover image is required only when creating.
- No server-side format validation on the `album-link` field (it's stored and
  displayed as-is, escaped with `htmlspecialchars()` on output) — a non-URL string
  would be accepted.
- `actions/uploadAlbum.php` does not validate that `album-category` is one of the
  three allowed values, same latent gap as `actions/updateCategory.php` in the
  Portfolio Images module.

## 11. Authentication

- `admin/albums.php`, `admin/album-view.php`: enforced via `includes/admin-head.php`.
- `actions/uploadAlbum.php`, `delete_album.php`, `delete_album_image.php`:
  `require_login()`.
- `actions/upload_album_img.php`: `require_ajax_login()` (called only via AJAX).
- Public pages (`photos.php`, `photos-category.php`, `view-album.php`): no
  authentication.

**Security note carried over from the review**: `actions/uploadAlbum.php` previously
had **no login check at all** — meaning anyone who found the form field names could
create or overwrite albums without being signed in. This has been fixed
(`require_login()` was added); every write handler in this module now checks
authentication before doing anything.

## 12. Authorization

Not applicable beyond "logged in or not" — see
[`docs/features/access-control.md`](../features/access-control.md).

## 13. UI Components

None shared with other modules. `admin/albums.php`'s create/edit modal and its
JavaScript-driven "edit mode" (pre-filling the form from the clicked row's
`data-*` attributes, in `js/adminScript.js`) is specific to this page.

## 14. JavaScript

- `js/adminScript.js` — the largest chunk of this module's behavior:
  - Enter/exit "edit mode" on the create/edit modal (swaps title, icon, button text,
    and makes the cover-image field optional).
  - Client-side search box filtering the album table by name.
  - "Add Images to Existing Album" AJAX form submission (via jQuery's `$.ajax()`).
  - A submit-button double-click guard, same pattern as the Portfolio Images module.
- `js/delete-form.js`'s shared `confirmDelete()` — used by both the album-delete form
  (`admin/albums.php`) and the gallery-photo-delete form (`admin/album-view.php`),
  both of which are plain `<form class="delete-form">` submissions (not AJAX).

## 15. Error Handling

Every write handler in this module sets a flash message
(`docs/features/flash-messages.md`) describing success or failure, then redirects
back to `admin/albums.php`. `actions/upload_album_img.php` (AJAX) instead echoes a
short plain-text result, read by `js/adminScript.js` to show a green/red inline
message inside the modal.

## 16. Edge Cases

- **Orphaned gallery-photo files**: deleting an album cascades the `album_img`
  *database rows* automatically, but does not delete their *files* from
  `image/uploads/` — those files remain on disk with nothing pointing to them. This
  is a known limitation, not something the current code attempts to clean up.
- **Two different upload folders**: album cover photos live in
  `image/upload-album/`; gallery photos live in `image/uploads/` — a different folder
  for what might look like "the same kind of thing." This predates the refactor and
  was deliberately left as-is (unifying it would mean rewriting every stored path in
  the database — see `docs/architecture.md`).
- **`album_img.img` stores a full path, not just a filename** — unlike every other
  image column in the schema. See `docs/database.md` section 2 for why this matters
  if you're writing new code against this table.

## 17. How to Modify

- To change which fields are required on create vs. edit, look at
  `actions/uploadAlbum.php`'s `if ($albumId > 0)` branch — the two flows are easy to
  tell apart in that one file.
- To fix the orphaned-gallery-file gap (Edge Cases above), `actions/delete_album.php`
  would need to fetch and delete each `album_img` row's file *before* deleting the
  `album` row (since the cascade happens at the database level, after which the rows
  are gone and their file paths are no longer retrievable).
- To validate `album-category`/`new_category` against the allowed list, add a check
  against a shared list of allowed categories (currently only defined inline in
  `photos-category.php` as `$allowed_categories`) before trusting the submitted value.

## 18. Testing Checklist

```text
- [ ] Public photos.php shows the three category cards
- [ ] Public photos-category.php shows every album in that category
- [ ] Public view-album.php shows every gallery photo in that album
- [ ] admin/albums.php page loads, with the redundant duplicate auth check removed
      (verify it still redirects correctly when logged out)
- [ ] Authentication works on every actions/*.php handler in this module, including
      uploadAlbum.php (previously missing — confirm it now redirects when logged out)
- [ ] Create: a new album with a cover image is created correctly
- [ ] Create: a duplicate cover filename is rejected with a flash message, no row inserted
- [ ] Read: admin/album-view.php and public view-album.php show the same gallery photos
- [ ] Update: editing an album without a new image keeps the old cover
- [ ] Update: editing an album with a new image replaces the cover (old file deleted)
- [ ] Delete: deleting an album removes the row, its album_img rows (via cascade), and
      its cover file
- [ ] Delete: adding then deleting a gallery photo removes both its row and its file
- [ ] Validation works (required fields on the create/edit form)
- [ ] Error handling works (flash messages appear for both success and failure)
- [ ] Database operations work (verify directly in the database)
- [ ] AJAX requests work ("Add Images to Existing Album")
- [ ] JavaScript works (edit-mode pre-fill, search box, double-submit guard)
```
