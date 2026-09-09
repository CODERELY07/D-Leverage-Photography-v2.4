# Database Documentation

This documents the actual schema in `database/schema.sql`, cross-checked against every
query in the codebase that reads or writes it.

## 1. Database Overview

The database (default name `dleverage`) stores everything the site needs beyond static
files: the admin account, the portfolio photo catalog, photo albums and their gallery
images, and incoming contact/booking messages. It's a single MySQL database with 5
tables and no schema versioning/migration tool — `database/schema.sql` is applied once,
by hand, when setting up a new environment.

## 2. Tables

### `image`

Purpose: one row per portfolio photo shown on the public `portfolio.php` gallery and
managed on `admin/portfolio.php`.

| Column | Type | Nullable | Key | Description |
|---|---|---|---|---|
| id | INT(11) AUTO_INCREMENT | No | PK | Row identifier |
| filename | VARCHAR(100) | No | | The file's name inside `image/` (e.g. `photo.jpg`) — **not** a full path |
| category | VARCHAR(100) | Yes | | One of `wedding`, `birthday`, `others` (enforced in application code, not by the database) |

### `admin`

Purpose: the site's admin account(s). In practice, exactly one row exists.

| Column | Type | Nullable | Key | Description |
|---|---|---|---|---|
| id | INT AUTO_INCREMENT | No | PK | Row identifier |
| username | VARCHAR(50) | No | UNIQUE | Login username |
| password | VARCHAR(255) | No | | Bcrypt hash, via PHP's `password_hash()` |
| created_at | DATETIME | Yes | | Defaults to `CURRENT_TIMESTAMP` |

### `contactData`

Purpose: one row per booking/contact-form submission.

| Column | Type | Nullable | Key | Description |
|---|---|---|---|---|
| id | INT AUTO_INCREMENT | No | PK | Row identifier |
| fullname | VARCHAR(100) | No | | Visitor's name |
| email | VARCHAR(100) | No | UNIQUE | Visitor's email — the UNIQUE constraint backs the "one booking per email" business rule |
| phonenumber | VARCHAR(20) | No | | Visitor's phone number |
| shootdate | DATETIME | No | | Requested shoot date |
| location | VARCHAR(255) | No | | Requested venue/location |
| service | VARCHAR(100) | No | | Selected service package (`basic`/`essential`/`elite`) |
| session | VARCHAR(100) | No | | Selected session type (`wedding`/`birthday`/`others`) |
| message | TEXT | No | | Free-text message from the visitor |
| status | VARCHAR(10) | No | | `'unread'` or `'read'`; defaults to `'unread'` (added by an `ALTER TABLE` in the schema file) |

Note: the column is named `service` (singular) and there's a separate `session` column
— easy to confuse with the `session` PHP concept, but this `session` column just means
"type of photo session" (wedding/birthday/others), unrelated to PHP's `$_SESSION`.

### `album`

Purpose: one row per photo album shown on the public site and managed on
`admin/albums.php`.

| Column | Type | Nullable | Key | Description |
|---|---|---|---|---|
| id | INT AUTO_INCREMENT | No | PK | Row identifier |
| album_name | VARCHAR(100) | No | | Display name |
| album_link | VARCHAR(100) | No | | External link (e.g. to a full gallery elsewhere) |
| album_img | VARCHAR(100) | No | | Cover photo's filename — **not** a full path, just the filename inside `image/upload-album/` |
| album_category | VARCHAR(100) | No | | One of `wedding`, `birthday`, `others` (application-enforced) |

### `album_img`

Purpose: one row per gallery photo inside an album (separate from the album's single
cover photo).

| Column | Type | Nullable | Key | Description |
|---|---|---|---|---|
| id | INT AUTO_INCREMENT | No | PK | Row identifier |
| album_id | INT | No | FK → `album.id` | Which album this photo belongs to |
| img | VARCHAR(100) | No | | **A full relative path from the project root**, e.g. `image/uploads/1750666193_photo.jpg` — unlike every other image column in this schema, this one is not just a bare filename |

**Important inconsistency to know about**: `image.filename` and `album.album_img`
store *bare filenames* (the calling code prepends `image/` or `image/upload-album/`
when building a URL or a filesystem path). `album_img.img` stores the *entire relative
path* already. This is how the original application was built and the refactor
deliberately preserved it rather than silently changing what gets written to the
database — see `includes/db/albums.php` and `actions/upload_album_img.php` for where
this distinction is handled.

## 3. Relationships

```text
album
  │
  └── album_img   (one album has many gallery images)
```

That is the only foreign-key relationship in the schema. `image`, `admin`, and
`contactData` are all standalone tables with no relationships to anything else.

- **One-to-many**: `album` → `album_img` (one album, many gallery photos).
- No one-to-one or many-to-many relationships exist in this schema.

## 4. Primary Keys

Every table has a single-column, auto-incrementing integer primary key named `id`.
Nothing unusual here.

## 5. Foreign Keys

Only one exists:

```sql
album_img.album_id → album.id
    ON DELETE CASCADE
    ON UPDATE CASCADE
```

**What `ON DELETE CASCADE` means in practice**: deleting a row from `album` (via
`actions/delete_album.php`) automatically deletes every `album_img` row that pointed
to it. The application does *not* separately delete those rows — the database does it
for you. It does **not**, however, delete the actual image *files* on disk for those
cascaded rows — only the album cover's own file is explicitly unlinked by
`delete_album.php`. This is a known gap: deleting an album can leave orphaned gallery
image files in `image/uploads/`.

## 6. Important Indexes

Beyond the primary keys (which are indexed automatically) and the two `UNIQUE`
constraints (`admin.username`, `contactData.email`, both of which create an index),
there are no additional indexes defined in the schema. TODO: for a larger dataset, an
index on `image.category`, `album.album_category`, and `contactData.status` would
speed up the filtered queries in `includes/db/images.php`,
`photos-category.php`, and `includes/db/messages.php` — not needed at the project's
current data volume, but worth knowing if it grows.

## 7. CRUD Operations

How the application performs each operation, by table:

**INSERT**
- `image`: `actions/upload.php` (one row per uploaded file, after checking the
  filename doesn't already exist)
- `album`: `actions/uploadAlbum.php` (only when no `album-id` was submitted)
- `album_img`: `actions/upload_album_img.php`
- `contactData`: `insert_message()` in `includes/db/messages.php`, called from
  `submit_contact_message()`
- `admin`: `actions/register.php` only (a one-time setup script)

**SELECT**
- `image`: `get_all_images()`, `get_images_by_category()`, `get_image_count()` in
  `includes/db/images.php`
- `album` / `album_img`: `get_album_by_id()`, `get_album_images()`, `get_album_count()`
  in `includes/db/albums.php`; `admin/albums.php` also does its own direct
  `SELECT * FROM album ORDER BY id DESC` (see `docs/modules/albums.md` for why this
  one wasn't centralized)
- `contactData`: `get_messages_by_status()`, `count_messages_by_status()`,
  `message_email_exists()` in `includes/db/messages.php`
- `admin`: `actions/login.php` (look up by username, to verify the password)

**UPDATE**
- `image`: `actions/updateImage.php` (replace the file, then the filename column),
  `actions/updateCategory.php` (recategorize)
- `album`: `actions/uploadAlbum.php` (when an `album-id` *was* submitted)
- `contactData`: `set_message_status()` in `includes/db/messages.php`

**DELETE**
- `image`: `actions/deleteImage.php`
- `album`: `actions/delete_album.php` (cascades to `album_img` automatically, see
  section 5 above)
- `album_img`: `actions/delete_album_image.php`
- `contactData`: `delete_message()` in `includes/db/messages.php`

## 8. Transactions

Not used anywhere in this codebase. Every write is a single `INSERT`/`UPDATE`/`DELETE`
statement executed on its own. This is worth being deliberate about if a future change
ever needs to make two related writes atomic (e.g. "delete the album row and its file
together, or neither").

## 9. Database Configuration

PHP connects via the `mysqli` extension. The **one and only** place the connection is
created is `config/connection.php`:

```php
$db = new mysqli($host, $username, $password, $database, $port);
```

Every other file that needs the database `require_once`s this file (directly, or
transitively through `includes/admin-head.php`) and then uses the `$db` variable it
defines. Credentials are plain PHP variables in that one file — they are **not**
committed with real values in this repository's shared documentation; see
`config/connection.php` directly on your own machine, and never commit real production
credentials to version control.

## 10. Database Setup

```bash
mysql -u <your_user> -p <your_database_name> < database/schema.sql
```

This creates all 5 tables in the order they depend on each other (`album` before
`album_img`, since the latter has a foreign key to the former). After that, run
`actions/register.php` once in the browser to create the single admin account (see the
main [`README.md`](../README.md) installation steps).

## 11. Database Modification Rules

If you need to change the schema (add a column, add a table, etc.):

1. **Never edit `database/schema.sql` to match a database you've already changed by
   hand.** Write the change as SQL first, apply it to your own database, *then* update
   `schema.sql` to match — so a fresh install still produces the same structure.
2. **Check every file in `includes/db/` for the table you're changing** before writing
   new code — if a function already exists for the read/write you need, use it instead
   of writing a new raw query.
3. **Use a prepared statement for anything involving a value that isn't a hardcoded
   literal in your own code.** See `docs/coding-standards.md`.
4. **If you add a new table that a page needs to read or write**, create a
   corresponding `includes/db/<table-or-module>.php` file for it rather than putting
   raw SQL directly in the page — that's the established pattern for every table that
   went through the refactor (`images.php`, `albums.php`, `messages.php`).
5. There is no migration tool — communicate schema changes to anyone else working on
   this project directly (e.g. in a commit message), since there's no automatic way for
   their local database to pick up the change.

## 12. Data Integrity

- The only enforced referential integrity is `album_img.album_id → album.id`
  (`ON DELETE CASCADE`, `ON UPDATE CASCADE`) — see section 5.
- `admin.username` and `contactData.email` are the only two `UNIQUE` constraints;
  the latter is actively used as a business rule (an admin can't have two open booking
  requests from the same email address at once, since a second submission with the
  same email gets a "duplicate" response before the database is even touched — see
  `message_email_exists()` in `includes/db/messages.php`).
- Every "category" column (`image.category`, `album.album_category`,
  `contactData.session`) is a plain `VARCHAR` with **no database-level constraint**
  limiting it to the three valid values (`wedding`/`birthday`/`others`) — validity is
  enforced only in application code (e.g. the `<select>` dropdowns, and
  `photos-category.php`'s `$allowed_categories` array). A row with an unexpected value
  in one of these columns is possible if inserted outside the normal admin UI (e.g.
  directly via SQL), and would fall back to `category_label()`'s default of
  `ucfirst($slug)` when displayed.
