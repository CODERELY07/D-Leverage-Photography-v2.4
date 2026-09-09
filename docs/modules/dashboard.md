# Module: Dashboard

## 1. Purpose

The admin's landing page after logging in — a quick overview of how much content
exists (portfolio images, albums, unread messages), each linking to the module that
manages it.

## 2. Users/Roles

Admin only (see [`docs/features/access-control.md`](../features/access-control.md)).

## 3. Features

- Three summary cards: portfolio image count, album count, unread message count.
- Each card links to the relevant module's admin page.
- Shows today's date.

## 4. Pages

| File | Purpose |
|---|---|
| `admin/dashboard.php` | The only page in this module |

## 5. Folder Structure

```text
admin/
└── dashboard.php
```

This page has no `actions/*.php` handler of its own — it's read-only, with no forms.

## 6. Request Flow

```text
User (logged-in admin)
 ↓
admin/dashboard.php
 ↓
includes/admin-head.php (connects to DB, enforces login, prints page head)
 ↓
includes/admin-header.php (top nav bar)
 ↓
count_messages_by_status($db, 'unread')   — includes/db/messages.php
get_image_count($db)                      — includes/db/images.php
get_album_count($db)                      — includes/db/albums.php
 ↓
Page prints the three cards using those three numbers
 ↓
includes/admin-footer.php
```

## 7. Business Rules

None beyond what each count already means in its own module (an "unread" message is
one whose `status` column is `'unread'`; an image/album count is just every row in
that table — see `docs/database.md`).

## 8. Database Tables

`image`, `album`, `contactData` (read-only, count queries only).

## 9. Database Operations

Three read-only calls, one per table:
- `count_messages_by_status($db, 'unread')`
- `get_image_count($db)`
- `get_album_count($db)`

All three are shared functions (also used elsewhere — see
`docs/modules/messages-inbox.md`, `docs/modules/portfolio-images.md`,
`docs/modules/albums.md`), not dashboard-specific code.

## 10. Validation

Not applicable — this page has no form input.

## 11. Authentication

Enforced by `includes/admin-head.php`'s `require_login()` call, like every other admin
page.

## 12. Authorization

Not applicable — see [`docs/features/access-control.md`](../features/access-control.md).

## 13. UI Components

Three near-identical "stat card" blocks, written directly on the page (not extracted
into a shared component — each card's icon, color, and link target differ enough, and
there are only three of them, that a shared partial wasn't judged worth the
indirection; see `docs/coding-standards.md`'s DRY section for the general rule this
follows).

## 14. JavaScript

None on this page.

## 15. Error Handling

None specific to this page — if a database call fails, PHP's own error handling
applies (the shared `includes/db/*.php` functions don't have special-case error
handling for a `COUNT(*)` query failing).

## 16. Edge Cases

None specific to this module — the counts are always non-negative integers or zero;
there's no "loading" or "error" state shown if a count query somehow fails.

## 17. How to Modify

To add a fourth stat card, follow the pattern of the existing three: call the relevant
module's count function (or add one to its `includes/db/*.php` file if it doesn't
exist yet), then print a card block matching the existing markup style.

## 18. Testing Checklist

```text
- [ ] Page loads for a logged-in admin
- [ ] Authentication works (redirects to index.php when logged out)
- [ ] All three counts match the actual row counts in the database
- [ ] Each card's link goes to the correct module page
- [ ] The date shown matches today's date
```
