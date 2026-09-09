# Module: Messages / Inbox

## 1. Purpose

The public contact/booking form and the admin inbox that reads its submissions. This
is the most fully-separated module in the codebase — it has its own dedicated
business-logic layer, database layer, and shared view partials (the other modules
don't yet, see `docs/architecture.md` section 15).

## 2. Users/Roles

- **Public visitors**: can submit the contact form. Cannot read any submitted
  messages.
- **Admin**: reads, marks read/unread, and deletes messages. Cannot submit through the
  admin UI (that's only the public form).

## 3. Features

- Public contact/booking form with server-side validation.
- "One booking per email address" business rule.
- Admin inbox split into two views: unread and read.
- Mark a message as read or unread.
- Delete a message.
- CSRF protection on both state-changing admin actions (the only module with this so
  far — see [`docs/features/csrf-protection.md`](../features/csrf-protection.md)).

## 4. Pages

| File | Purpose |
|---|---|
| `contact.php` | The public contact/booking form |
| `admin/inbox.php` | Unread messages list |
| `admin/inbox-read.php` | Read messages list |
| `actions/contactSubmit.php` | Handles the public form's AJAX submission |
| `actions/inbox-markAsRead.php` | Toggles a message's status (handles both "mark read" and "mark unread") |
| `actions/deleteMessages.php` | Deletes a message |

## 5. Folder Structure

```text
contact.php
admin/
├── inbox.php
└── inbox-read.php
actions/
├── contactSubmit.php
├── inbox-markAsRead.php
└── deleteMessages.php
includes/
├── db/
│   └── messages.php          # get_messages_by_status(), count_messages_by_status(),
│                              # message_email_exists(), insert_message(),
│                              # set_message_status(), delete_message()
├── logic/
│   └── messages.php          # validate_contact_submission(), submit_contact_message()
└── views/
    └── messages/
        ├── message-details.php   # shared modal content (Contact Info/Shoot Details/Message)
        └── summary-badges.php    # shared unread/read count badges
```

## 6. Request Flow

Real example — **submitting the contact form**:

```text
User (visitor)
 ↓
contact.php — fills in the form, clicks Send
 ↓
js/script.js — intercepts submit, sends an AJAX POST to actions/contactSubmit.php
 ↓
actions/contactSubmit.php (request processing)
   → confirms it's a real POST with a 'send' field
   → calls submit_contact_message($db, $_POST)
 ↓
includes/logic/messages.php (business logic)
   → validate_contact_submission()   — are all 8 fields present?
   → filter_var(FILTER_VALIDATE_EMAIL)
   → message_email_exists($db, $email)   — the "one per email" rule
   → insert_message($db, $data)
 ↓
includes/db/messages.php (database)
   → INSERT INTO contactData (...) VALUES (...)   — prepared statement
 ↓
Result travels back up: 'success' → actions/contactSubmit.php echoes 1
 ↓
js/script.js shows a SweetAlert success message
```

## 7. Business Rules

- **All 8 fields are required**: full name, email, phone, date, location, session
  type, service, message. Mirrors a loose-comparison emptiness check
  (`== false`) inherited from the original implementation — an edge case worth
  knowing: a field whose value is the literal string `"0"` would be treated as empty
  by this check, same as a genuinely empty field.
- **Email must be a valid format** (`FILTER_VALIDATE_EMAIL`).
- **One booking request per email address**: if a message already exists with that
  email, the new submission is rejected (not silently ignored — the visitor sees a
  distinct "already booked" message). This is enforced both in application code
  (`message_email_exists()` checked before inserting) and at the database level
  (`contactData.email` is `UNIQUE`) — belt and suspenders.
- **A message's status is always exactly `'unread'` or `'read'`** — set to `'unread'`
  by default on insert (the schema's column default), and only ever changed by the
  admin's mark-as-read/unread action.

## 8. Database Tables

`contactData` only — see [`docs/database.md`](../database.md).

## 9. Database Operations

| Operation | Function | Used by |
|---|---|---|
| List messages by status | `get_messages_by_status($db, $status)` | `admin/inbox.php` ('unread'), `admin/inbox-read.php` ('read') |
| Count messages by status | `count_messages_by_status($db, $status)` | `admin/dashboard.php`, `admin/inbox.php`, `admin/inbox-read.php` |
| Check if an email already submitted | `message_email_exists($db, $email)` | `submit_contact_message()` |
| Insert a message | `insert_message($db, $data)` | `submit_contact_message()` |
| Change status | `set_message_status($db, $id, $status)` | `actions/inbox-markAsRead.php` |
| Delete a message | `delete_message($db, $id)` | `actions/deleteMessages.php` |

Every one of these is a prepared statement. All six live in
`includes/db/messages.php`.

## 10. Validation

`validate_contact_submission($post)` (`includes/logic/messages.php`) checks all 8
required fields and returns an array of field-level errors (empty array = valid).
`submit_contact_message()` then separately checks the email format and the duplicate
rule, in that order — matching the exact order the original single-file implementation
checked them in, so the visitor sees the same error for the same mistake as before.

## 11. Authentication

- `contact.php`, `actions/contactSubmit.php`: **no authentication** — this is the
  public booking form, open to anyone.
- `admin/inbox.php`, `admin/inbox-read.php`: enforced via `includes/admin-head.php`.
- `actions/inbox-markAsRead.php`, `actions/deleteMessages.php`: `require_login()`.

## 12. Authorization

Not applicable beyond "logged in or not" — see
[`docs/features/access-control.md`](../features/access-control.md).

## 13. UI Components

- `includes/views/messages/summary-badges.php` — the unread/read count badges,
  identical on both `admin/inbox.php` and `admin/inbox-read.php`.
- `includes/views/messages/message-details.php` — the "Contact Information / Shoot
  Details / Message" block inside each message's modal, identical on both pages. The
  modal's *header* and *footer* (which differ — Reply+Mark-as-read vs.
  Close+Mark-as-unread+Delete) are **not** shared, since forcing them into one
  component would mean passing raw HTML as a parameter for no real benefit.

## 14. JavaScript

- `admin/inbox-read.php` has an inline `<script>` that wires up its delete buttons:
  reads a `CSRF_TOKEN` printed into the page, calls the shared `confirmDelete()`
  (`js/delete-form.js`), then sends an `XMLHttpRequest` to
  `actions/deleteMessages.php` with the token included.
- `js/script.js`'s contact-form handler (see Request Flow above) only runs when
  `location.pathname` matches `contact.php` exactly — a pre-existing hardcoded check,
  worth knowing if the page is ever moved or the site deployed under a different path.

## 15. Error Handling

- Contact form: `actions/contactSubmit.php` maps the logic layer's result to one of
  five outcomes the front-end understands: `0` (missing fields), `-1` (invalid
  email), `-2` (duplicate email), `1` (success), or `"Error: <db error>"` (a genuine
  database failure, which the visitor would see as a generic-looking error — this is
  an existing, low-probability failure path, not something visitors normally hit).
- Admin actions: `actions/inbox-markAsRead.php` and `actions/deleteMessages.php` echo
  short plain-text results; a rejected CSRF check responds with HTTP 403 and a short
  message before anything else runs.

## 16. Edge Cases

- Submitting the exact string `"0"` in a required field is treated as "missing" (see
  Business Rules above) — an inherited quirk, not something recently introduced.
- A message can only be marked read/unread one at a time (no bulk actions).
- There is no way to reply to a message from within the admin UI beyond a `mailto:`
  link that opens the visitor's own email client.

## 17. How to Modify

- To add a new required field to the contact form, add it to
  `validate_contact_submission()`'s `$required` array, the `contactData` table (new
  column), `insert_message()`'s bind list, and the form itself in `contact.php` — all
  four need to change together.
- To change the duplicate-email business rule (e.g. allow a second submission after
  some time has passed), that logic lives entirely in `submit_contact_message()` — the
  database's `UNIQUE` constraint on `email` would also need to be reconsidered, since
  it currently enforces the rule at the database level too.
- This module is the reference example for how the *other* modules (Portfolio Images,
  Albums) should eventually be restructured — see `docs/development-guide.md` section
  4 and copy this module's shape (`includes/db/`, `includes/logic/`,
  `includes/views/`) when doing that work.

## 18. Testing Checklist

```text
- [ ] Public contact.php form loads
- [ ] Create: a valid submission is saved and appears in admin/inbox.php as unread
- [ ] Validation works: missing fields, invalid email, and a duplicate email each show
      their own distinct message to the visitor
- [ ] Read: admin/inbox.php shows unread messages; admin/inbox-read.php shows read ones
- [ ] Update: marking a message as read moves it from inbox.php to inbox-read.php
- [ ] Update: marking a message as unread moves it back
- [ ] Delete: deleting a message removes it from the database
- [ ] Authentication works (admin pages and actions redirect/403 when logged out)
- [ ] CSRF protection works: a request without a valid token is rejected (403); a
      request with the real, page-rendered token succeeds
- [ ] Error handling works (a rejected CSRF attempt shows a clear message, not a blank page)
- [ ] Database operations work (verify directly in the database)
- [ ] UI works (both inbox pages' summary badges show correct counts; each message's
      modal shows the correct details)
- [ ] JavaScript works (delete confirmation dialog, AJAX delete, contact form AJAX submit)
```
