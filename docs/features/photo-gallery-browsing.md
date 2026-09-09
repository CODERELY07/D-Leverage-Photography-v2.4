# Feature: Portfolio Gallery Browsing

## 1. Purpose

Lets a visitor browse the photographer's portfolio images on `portfolio.php`, with a
category filter, entirely client-side (no page reload when switching categories).

## 2. User Flow

```text
Visitor opens portfolio.php
 ↓
The full image grid loads, plus a row of filter buttons ("All", plus one per category
that actually has at least one image)
 ↓
Visitor clicks a category button
 ↓
JavaScript (js/magnificpopup.js's isotope-like filtering, driven by CSS classes) shows
only the images matching that category's CSS class, hides the rest — no server request
 ↓
Visitor clicks an image
 ↓
A lightbox (Magnific Popup) opens showing the full-size image
```

## 3. Requirements

At least one row in the `image` table for anything to display; the "All" filter always
shows, but a category filter button only appears for categories that have at least one
image (`SELECT DISTINCT category FROM image`).

## 4. Business Rules

None beyond what images exist in the database — this is a pure display feature with no
validation or business logic of its own.

## 5. Database

Reads `image` only, via `get_all_images($db)` (`includes/db/images.php`) for the grid,
and a direct `SELECT DISTINCT category FROM image` (in `portfolio.php` itself, not
centralized — it's the only place this specific query is needed) for the filter
buttons.

## 6. Files

| File | Role |
|---|---|
| `portfolio.php` | The page — prints the filter buttons and the image grid |
| `includes/db/images.php` | `get_all_images($db)` |
| `js/magnificpopup.js` | Wires up the category filter buttons and the image lightbox |
| `css/style.css` | The grid layout and the category-based show/hide classes |

## 7. Logic

None beyond `get_all_images($db)` — a plain, unfiltered read of the whole `image`
table. The category *filtering itself* happens entirely in the browser (CSS
class-matching), not on the server — clicking a filter button does not trigger a new
page load.

## 8. UI

A CSS grid of images, each wrapped in an `<a>` tag pointing at the image's own file
(also usable as the lightbox trigger). Each image carries its category as a CSS class
(`class="image <?php echo htmlspecialchars($data['category']); ?> img"`), which the
filter buttons toggle visibility against.

## 9. Validation

Not applicable — no user input.

## 10. Authentication

None — fully public.

## 11. Authorization

Not applicable.

## 12. Error Handling

Not applicable — there's no failure mode here beyond "no images exist yet", which
simply renders an empty grid (no explicit "no images" message is shown).

## 13. Edge Cases

- A category value containing characters that would also match Magnific Popup's/CSS's
  filtering logic unexpectedly (e.g. spaces) could behave oddly as a CSS class name —
  in practice, categories are always one of the three fixed values
  (`wedding`/`birthday`/`others`), so this hasn't been observed as a real problem, but
  it's a latent risk if `actions/updateCategory.php`'s missing validation (see
  `docs/modules/portfolio-images.md`) is ever exploited to store an unusual value.
- Every image filename in the URL/`src` is `rawurlencode()`'d, and every printed
  category/filename value is `htmlspecialchars()`'d — this was a real, fixed gap
  during the security review (previously, a filename containing a space, like the
  real `"KEYCHAIN ORDERS.png"`, produced a broken image link on this specific page).

## 14. Testing

```text
- [ ] The page shows every uploaded portfolio image
- [ ] The "All" filter button shows every image
- [ ] Each category filter button shows only that category's images
- [ ] A category with zero images does not show a filter button for it
- [ ] Clicking an image opens the lightbox with the correct full-size image
- [ ] A filename containing a space (or other special character) still displays correctly
```

## 15. How to Modify

To change what data appears per image (e.g. adding a caption), that would require a
new column on `image`, a change to `get_all_images()`'s implicit `SELECT *`
(no change needed there, since it already selects every column), and a change to how
`portfolio.php` prints each image. To change the filtering mechanism itself (e.g. to a
server-rendered filter instead of CSS-class toggling), that's a bigger change — see
`admin/portfolio.php`'s AJAX-based filter (`actions/table-portfolio.php`) for an
example of the alternative approach already used elsewhere in this app.
