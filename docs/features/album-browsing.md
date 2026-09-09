# Feature: Album Browsing

## 1. Purpose

Lets a visitor browse photo albums by category, then view every photo inside one
album. This is the site's main way of showcasing full photo sets (as opposed to the
single-image portfolio grid).

## 2. User Flow

```text
Visitor opens photos.php
 ↓
Sees three category cards: Wedding/Prenuptial, Birthday, Others
 ↓
Clicks one → photos-category.php?category=wedding
 ↓
Sees every album in that category (cover photo + name), or a friendly "no albums yet"
message with a "Book a Session" call-to-action if there are none
 ↓
Clicks an album → view-album.php?id=123
 ↓
Sees every gallery photo in that album, plus a "Back to [category]" link
```

## 3. Requirements

At least one row in `album` with a matching `album_category` for a category page to
show any albums; at least one row in `album_img` for the album-view page to show any
photos (an album with zero gallery photos shows "No images found for this album.").

## 4. Business Rules

- Only three categories are recognized: `wedding`, `birthday`, `others`. Any other
  value in the `category` URL parameter on `photos-category.php` is rejected outright
  (`die("Invalid category selected.")`).
- An invalid or missing album `id` on `view-album.php` is rejected the same way
  (`die("Invalid album ID.")` / `die("Album not found.")`).
- Category display names are centralized in one place
  (`category_label()`, `includes/functions.php`) so `wedding` always displays as
  "Wedding / Prenuptial" everywhere it's shown, never drifting to a different label on
  a different page.

## 5. Database

Reads `album` and `album_img`. `photos-category.php` queries `album` directly
(filtered by category, prepared statement); `view-album.php` uses the shared
`get_album_by_id()` and `get_album_images()` (`includes/db/albums.php`) — the same two
functions the admin's `admin/album-view.php` uses, so both sides of the app always
see identical data for identical logic.

## 6. Files

| File | Role |
|---|---|
| `photos.php` | The three category cards |
| `photos-category.php` | Albums within one category |
| `view-album.php` | One album's gallery photos |
| `includes/functions.php` | `category_label()` |
| `includes/db/albums.php` | `get_album_by_id()`, `get_album_images()` |

## 7. Logic

Almost entirely display logic — the one real rule is the category allow-list on
`photos-category.php` (`$allowed_categories = ['wedding', 'birthday', 'others'];`),
which both validates the incoming URL parameter and drives the "Check Also" related-
category links at the bottom of the page.

## 8. UI

Plain Bootstrap-styled cards and a responsive grid — no JavaScript-driven behavior on
any of these three pages (unlike the portfolio gallery's client-side filtering).

## 9. Validation

- `photos-category.php`: the `category` URL parameter is lower-cased and checked
  against the allow-list; anything else stops the page immediately.
- `view-album.php`: the `id` URL parameter is cast to an integer
  (`intval($_GET['id'])`) and must be a positive number that actually matches a row in
  `album`.

## 10. Authentication

None — fully public, like every other page in this feature.

## 11. Authorization

Not applicable.

## 12. Error Handling

An invalid category or album ID stops the page with a plain `die()` message rather
than a styled error page — this is consistent with how the rest of the public site
handles a genuinely malformed URL (not a normal user-facing error state, since a
visitor would only hit this by typing/editing the URL directly).

## 13. Edge Cases

- A category with zero albums shows a dedicated empty state (icon + message +
  "Book a Session" button) rather than an empty grid — this is a deliberate UX touch,
  worth preserving if this page is ever changed.
- Album cover images and gallery images are both `rawurlencode()`'d /
  `htmlspecialchars()`'d on these public pages, so a filename with special characters
  displays correctly (this was verified during the security review).

## 14. Testing

```text
- [ ] photos.php shows all three category cards
- [ ] photos-category.php shows every album in the chosen category
- [ ] An invalid category in the URL is rejected
- [ ] A category with no albums shows the empty state, not a blank grid
- [ ] view-album.php shows every gallery photo for a valid album ID
- [ ] An invalid or missing album ID is rejected
- [ ] The "Back to [category]" link on view-album.php goes to the correct category page
- [ ] Category display names (e.g. "Wedding / Prenuptial") are consistent across
      photos-category.php and view-album.php
```

## 15. How to Modify

To add a fourth category, you would need to update it in every place the current three
are hardcoded: `photos.php`'s category cards, `photos-category.php`'s
`$allowed_categories` array, `category_label()`'s label map, and every admin `<select>`
that offers a category choice (see `docs/modules/portfolio-images.md` and
`docs/modules/albums.md`) — there is no single shared list of categories to edit once;
this is a known, tracked piece of duplication (see `docs/coding-standards.md`'s DRY
section for why it was deliberately left this way rather than being centralized during
the current refactor pass).
