# Module: Public Pages

## 1. Purpose

The site's static/informational pages — the home page and the about/rates page. These
carry no database-driven content of their own (unlike Portfolio Images, Albums, and
Messages, which each manage a table).

## 2. Users/Roles

Public visitors. No authentication involved — though both pages redirect an
**already-logged-in admin** straight to `admin/dashboard.php` (see
`includes/header.php`), same as every other public page.

## 3. Features

- Home page: hero banner, a client testimonial quote, an introductory blurb, and a
  call-to-action linking to the contact form.
- About page: the photographer's story, an "expanding our team" note, and a static
  pricing table (Basic/Essential/Elite).

## 4. Pages

| File | Purpose |
|---|---|
| `index.php` | Home page |
| `about.php` | About and rates page |

## 5. Folder Structure

```text
index.php
about.php
includes/
├── header.php   # shared with every public page (nav, <head>)
└── footer.php   # shared with every public page (footer, closing scripts)
```

## 6. Request Flow

```text
User
 ↓
index.php or about.php
 ↓
includes/header.php — redirects to admin/dashboard.php if already logged in as
                       admin; otherwise prints the page <head> and top navigation
 ↓
The page's own static HTML
 ↓
includes/footer.php — prints the site footer and closing scripts
```

Neither page touches the database or reads any request parameters — they are the
simplest pages in the application.

## 7. Business Rules

None — these pages present fixed content. The "Basic/Essential/Elite" prices and
package details on `about.php` are hardcoded in the page's own HTML, not stored in the
database.

## 8. Database Tables

None.

## 9. Database Operations

None.

## 10. Validation

Not applicable — no form input on either page.

## 11. Authentication

Neither page requires login. Both redirect an already-authenticated admin away (to
`admin/dashboard.php`), via the same check every public page shares in
`includes/header.php`.

## 12. Authorization

Not applicable.

## 13. UI Components

Both pages use the shared `includes/header.php`/`includes/footer.php` shell — the same
one used by every other public page (`portfolio.php`, `photos.php`,
`photos-category.php`, `view-album.php`, `contact.php`).

## 14. JavaScript

Nothing page-specific. Both pages load the shared `js/script.js`, whose only relevant
behavior for these pages is the sticky-header-on-scroll effect and the mobile menu
toggle (both apply site-wide, not specific to these two pages).

## 15. Error Handling

Not applicable — no operations that can fail.

## 16. Edge Cases

None identified.

## 17. How to Modify

These are the two easiest pages in the app to change — they're static HTML inside a
`.php` wrapper. Edit the HTML directly; there's no data-binding or templating beyond
plain PHP `<?php ... ?>` tags (which aren't even used for anything dynamic on these two
pages beyond the shared header/footer includes).

If the pricing table on `about.php` should ever become admin-editable, that would be
genuinely new functionality (a new database table, an admin page to manage it, and a
change to how `about.php` renders it) — not a small tweak to the existing files.

## 18. Testing Checklist

```text
- [ ] Page loads (index.php and about.php both render correctly)
- [ ] Navigation links work and highlight the current page correctly
- [ ] An already-logged-in admin visiting either page is redirected to admin/dashboard.php
- [ ] "Send a Message" / "Book Now" links correctly go to contact.php
- [ ] CSS renders correctly (hero image, testimonial quote, rates cards)
- [ ] JavaScript works (mobile menu toggle, sticky header on scroll)
```
