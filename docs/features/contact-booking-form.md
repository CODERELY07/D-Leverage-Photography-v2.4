# Feature: Contact / Booking Form

## 1. Purpose

Lets a website visitor request a photography booking without needing to email or call
directly. It's the site's primary lead-generation mechanism — every other page's
"Book Now"/"Send a Message" links point here.

## 2. User Flow

```text
Visitor opens contact.php (from any "Book Now" link on the site)
 ↓
Fills in: full name, email, phone, shoot date, location, session type (Wedding/
Prenuptial, Birthday, Others), service package (Basic, Essential, Elite), and a message
 ↓
Clicks Send
 ↓
JavaScript sends the form as an AJAX POST (page does not reload)
 ↓
Server validates: all fields present? email format valid? already booked with this email?
 ↓
If any check fails: a SweetAlert popup explains which one, form stays filled in
If everything passes: the message is saved, a SweetAlert success popup shows, form clears
```

## 3. Requirements

- The visitor must fill in all 8 fields — there is no "optional" field on this form.
- The email address must not already exist in the `contactData` table.

## 4. Business Rules

- **All fields required.** See `docs/modules/messages-inbox.md` section 7 for the
  exact emptiness check used (including its "0" edge case).
- **Valid email format required** (`FILTER_VALIDATE_EMAIL`).
- **One submission per email address.** A second submission with an email that's
  already in `contactData` is rejected with a distinct "already booked" message — it
  does not overwrite or add to the existing request. This is enforced both in code
  (checked before inserting) and by the database (`UNIQUE` constraint on `email`).

## 5. Database

Writes to `contactData` only (via `insert_message()`, after `message_email_exists()`
confirms no row already has that email). See [`docs/database.md`](../database.md).

## 6. Files

| File | Role |
|---|---|
| `contact.php` | The form's HTML |
| `js/script.js` | Intercepts the submit, sends the AJAX request, shows the result |
| `actions/contactSubmit.php` | Request processing — decides whether to act, maps the result to a wire code |
| `includes/logic/messages.php` | `validate_contact_submission()`, `submit_contact_message()` — all the actual rules |
| `includes/db/messages.php` | `message_email_exists()`, `insert_message()` |

## 7. Logic

All business logic lives in `submit_contact_message($db, $post)`
(`includes/logic/messages.php`), which runs in this exact order: required-fields
check → email format check → duplicate-email check → insert. It returns one of
`'missing_fields'`, `'invalid_email'`, `'duplicate_email'`, `'success'`, or a raw
database error string — `actions/contactSubmit.php` is the only place that translates
that into the specific numeric codes (`0`, `-1`, `-2`, `1`) the JavaScript expects.

## 8. UI

A single-page Bootstrap form (`contact.php`), no multi-step wizard. All fields are
plain text/email/date/select inputs; none have a `required` HTML attribute (validation
is entirely server-side, and the visitor finds out what's wrong via the SweetAlert
popup after submitting, not before).

## 9. Validation

See section 4 (Business Rules) above — validation and business rules are the same
thing for this feature; there's no separate "does the input look well-formed" step
beyond what's described there.

## 10. Authentication

None required — this form is intentionally open to any visitor.

## 11. Authorization

Not applicable.

## 12. Error Handling

| Wire code | Meaning | What the visitor sees |
|---|---|---|
| `0` | A required field was missing | "All Data is required" |
| `-1` | Email format invalid | "Please provide correct email!" |
| `-2` | Email already used | "Already Book! You're Already Send a message, Please wait a minute for the response" (form is reset) |
| `1` | Success | A success SweetAlert (form is reset) |
| `"Error: ..."` | A database failure occurred | Logged to the browser console only — no popup is shown for this case (see Edge Cases) |

## 13. Edge Cases

- If the database insert itself fails (not a validation problem — an actual database
  error), the JavaScript's `else` branch (anything that isn't exactly `0`, `-1`, or
  `-2`) currently treats it the same as success and shows the success popup, even
  though the message was **not** saved. This is a real, existing gap worth knowing
  about — it would only occur if the database write genuinely failed after passing
  validation, which is rare, but the visitor would be misled if it happened.
- The JavaScript's page-detection check (`location.pathname ==
  "/D-Leverage-Photography-v2.4/contact.php"`) is a hardcoded absolute path. If the
  site is ever deployed under a different folder name or path, the contact form's
  submit handler will silently not attach, and clicking Send will do nothing (a normal
  form submit / page reload would happen instead, which would not go through the AJAX
  handler at all — since the form has no `action` attribute, this would likely just
  reload the page with the fields in the URL query string).

## 14. Testing

```text
- [ ] Submitting with a field missing shows "All Data is required"
- [ ] Submitting with an invalid email format shows "Please provide correct email!"
- [ ] Submitting with a valid, new email succeeds and shows the success message
- [ ] Submitting again with that same email shows the "Already Book!" message
- [ ] A successful submission actually appears in admin/inbox.php as unread
- [ ] The form resets after both a successful submission and a duplicate-email rejection
```

## 15. How to Modify

To add or remove a required field, see `docs/modules/messages-inbox.md` section 17 —
it needs coordinated changes across the form, the validation function, the database
table, and the insert function. To change what happens on a genuine database failure
(see Edge Cases above), the fix belongs in `js/script.js`'s response handler — it
would need to check for the exact success code (`1`) rather than treating "anything
that isn't 0/-1/-2" as success.
