# Feature: Flash Messages

## 1. Purpose

A "flash message" is a one-time status message — "Album updated successfully",
"Upload failed: ...", and similar — shown once, right after the admin is redirected
back to a page following some action, then automatically gone on the next page load.
This is how the admin area tells you whether the thing you just did actually worked.

## 2. User Flow

```text
Admin performs an action (e.g. uploads an image)
 ↓
actions/upload.php calls set_flash("Image uploaded successfully!")
 ↓
Redirect to admin/portfolio.php
 ↓
admin/portfolio.php calls display_flash() near the top of its markup
 ↓
The message appears as a dismissible Bootstrap alert, once
 ↓
The admin reloads or navigates elsewhere — the message is gone (it was deleted from
the session the moment display_flash() showed it)
```

## 3. Requirements

`session_start()` must already be active (flash messages are stored in
`$_SESSION['status']`).

## 4. Business Rules

- Only one flash message can be "in flight" at a time — setting a second one before
  the first is displayed overwrites it. This is used deliberately in one place
  (`actions/delete_album.php`, where a "file not found" message is set and then
  immediately overwritten by a "deleted successfully" message on the same request —
  see that module's docs for why this is intentional, inherited behavior, not a bug).
- The message is always shown with the same visual style (a Bootstrap
  `alert-warning`), regardless of whether it represents success or failure — there is
  no separate "success" vs. "error" styling.

## 5. Database

None — flash messages live entirely in the PHP session, never the database.

## 6. Files

| File | Role |
|---|---|
| `includes/flash.php` | `set_flash($message)`, `display_flash()` |

Used by (setting): `actions/upload.php`, `actions/uploadAlbum.php`,
`actions/delete_album.php`.
Used by (displaying): `admin/portfolio.php`, `admin/albums.php`, `admin/inbox.php`,
`admin/inbox-read.php`.

## 7. Logic

```php
function set_flash(string $message): void {
    $_SESSION['status'] = $message;
}

function display_flash(): void {
    if (empty($_SESSION['status'])) {
        return;
    }
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">'
       . '<strong>' . htmlspecialchars($_SESSION['status']) . '</strong>'
       . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'
       . '</div>';
    unset($_SESSION['status']);
}
```

That's the entire implementation — two small functions, no configuration.

## 8. UI

A single Bootstrap `alert-warning` block with a dismiss (×) button — visually
identical everywhere it appears, since it's printed by the same `display_flash()` call
on every page that uses it.

## 9. Validation

Not applicable.

## 10. Authentication

Not applicable to the mechanism itself — in practice, only admin pages currently use
it (the public contact form does not use flash messages; it responds via AJAX
instead — see `docs/features/contact-booking-form.md`).

## 11. Authorization

Not applicable.

## 12. Error Handling

`display_flash()` safely does nothing if no message was set (`empty()` check) — this
is exactly why every admin page that displays a flash message can call
`display_flash()` unconditionally on every load, not just after redirect from an action.

## 13. Edge Cases

- The message text is escaped with `htmlspecialchars()` before being printed — flash
  messages can include values that were technically influenced by user input (e.g.
  `"Upload failed: " . $stmt->error`, where the underlying error came from data the
  admin submitted), so this escaping matters even though every current message is
  server-authored text, not raw user input.
- If two different actions redirect to the same page in quick succession (not
  currently possible in this app's normal usage, since each action does its own
  redirect-then-stop), only the most recently set message would ever be seen — see
  Business Rules above.

## 14. Testing

```text
- [ ] Performing an action that sets a flash message shows it once after redirect
- [ ] Reloading the page after that does not show the message again
- [ ] The message text is HTML-escaped (test with a value containing < or & if possible)
- [ ] A page that calls display_flash() with nothing set shows nothing (no empty alert box)
```

## 15. How to Modify

To use flash messages in a new module, `require_once
__DIR__ . '/../includes/flash.php'`, call `set_flash($message)` before your redirect,
and call `display_flash();` near the top of the page you redirect back to — copy the
exact pattern from `admin/portfolio.php` or `actions/upload.php`. To add
success/error styling instead of one fixed style, `set_flash()` and `display_flash()`
would need a second parameter for the message "type" (e.g. `set_flash($message,
'success')`), and every call site across the app would need updating to pass it —
currently no call site does this, so it wasn't added speculatively.
