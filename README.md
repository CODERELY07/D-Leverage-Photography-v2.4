# D'Leverage Photography

## 1. Project Overview

D'Leverage Photography is the website for a Montreal-based wedding, portrait, and event
photography business. It has two sides:

- **A public website** where visitors can see the portfolio, browse photo albums by
  category (wedding, birthday, others), read about the photographer, and submit a
  booking/contact request.
- **A private admin area** where the photographer manages the site's content: uploading
  portfolio photos, creating and editing photo albums, and reading/managing booking
  messages sent through the contact form.

**Who uses it:**
- Website visitors / potential clients — browse the portfolio and submit a booking request.
- The photographer (site admin) — logs in to manage photos, albums, and incoming messages.

**Main purpose:** give the photographer a simple way to showcase work and receive booking
inquiries, without needing a third-party platform.

## 2. Features

- **Public photo portfolio** — a filterable grid of portfolio images, grouped by category.
- **Public photo albums** — browse albums by category (Wedding/Prenuptial, Birthday,
  Others), then view all photos in one album, each linking out to the full gallery
  (Facebook, Google Drive, etc. — whatever `album_link` points to).
- **Contact / booking form** — visitors submit their name, email, phone, shoot date,
  location, session type, and desired service package.
- **Admin authentication** — a single admin account logs in with a username/password to
  access the admin area.
- **Admin dashboard** — an overview page showing how many portfolio images, albums, and
  unread messages currently exist.
- **Portfolio image management** — upload, replace, recategorize, and delete portfolio
  images.
- **Album management** — create, edit, and delete albums (each with a cover photo, a
  link, and a category), plus add or remove individual gallery photos within an album.
- **Inbox / message management** — view unread and read booking messages, mark them as
  read/unread, and delete them.

## 3. Technology Stack

```text
PHP (procedural style, no framework)
MySQL (via the mysqli extension, prepared statements)
HTML
CSS
JavaScript (vanilla + jQuery, used only for the AJAX "add image to album" form)
Bootstrap 5.3.3 (via CDN)
SweetAlert2 (via CDN, for confirmation dialogs)
AOS – Animate On Scroll (via CDN, for public-page scroll animations)
Magnific Popup (via CDN, for the public site)
Font Awesome (via CDN, icons)
```

This developer's environment runs **PHP 8.4.2**. The code relies on features available
since **PHP 8.1** (specifically, `mysqli_stmt::execute()` accepting an array of bound
parameters in `actions/updateCategory.php`), so **PHP 8.1 or newer** is required.
TODO: confirm the exact PHP version on the production web host.

## 4. Requirements

- PHP 8.1 or newer, with the `mysqli` extension enabled.
- A MySQL (or MySQL-compatible, e.g. MariaDB) server.
- Any web server that can run PHP (Apache, Nginx + PHP-FPM, or PHP's own built-in
  development server for local testing).
- A modern web browser (the public site and admin area both use current CSS/JS
  features).

## 5. Installation

These steps take a fresh copy of the project to a working local install.

1. **Get the project**
   ```bash
   git clone https://github.com/CODERELY07/D-Leverage-Photography-v2.4.git
   cd D-Leverage-Photography-v2.4
   ```

2. **Create the database**
   Using the MySQL client of your choice, create an empty database (any name works,
   but the app defaults to `dleverage`):
   ```sql
   CREATE DATABASE dleverage;
   ```

3. **Import the database structure**
   ```bash
   mysql -u <your_user> -p dleverage < database/schema.sql
   ```
   This creates the `image`, `admin`, `contactData`, `album`, and `album_img` tables.
   See [`docs/database.md`](docs/database.md) for what each one stores.

4. **Configure database credentials**
   Open `config/connection.php` and set your own values:
   ```php
   $host     = "127.0.0.1";
   $username = "root";
   $password = "";          // your MySQL password
   $database = "dleverage";
   $port     = 3306;        // your MySQL port
   ```
   This file is the single place the whole app connects to the database from.

5. **Point your web server at the project root**
   The project has no `public/` folder — the whole project directory is the web root
   (e.g. XAMPP's `htdocs/`, or PHP's built-in server run from this directory).

6. **Create the admin account**
   Visit `actions/register.php` once in your browser. It creates one hardcoded admin
   account (username `admin`). **Important**: this script has no protection and will
   throw an error if run a second time (the username is already taken) — see
   [Security](#16-security) and [Troubleshooting](#17-troubleshooting) below. After the
   account exists, there is no supported way to add more admin accounts or change the
   password through the UI — that would need a direct database update (see
   [`docs/database.md`](docs/database.md)).

7. **Start the application**
   - With XAMPP/WAMP/similar: start Apache and MySQL, then browse to the project folder.
   - With PHP's built-in server (development only):
     ```bash
     php -S localhost:8000
     ```

8. **Open it in the browser**
   - Public site: `http://localhost:8000/index.php`
   - Admin login: `http://localhost:8000/adminLogin.php`

## 6. Configuration

- **Database**: `config/connection.php` — host, username, password, database name, port.
  There is no `.env` file or environment-variable support; credentials are plain PHP
  variables in this one file. **Never commit real production credentials to git.**
- **Application settings**: there is no separate app-config file. The few
  application-wide constants that exist are hardcoded where they're used — e.g. the
  three allowed photo categories (`wedding`, `birthday`, `others`) appear in
  `photos-category.php`, and their display labels live in `category_label()`
  (`includes/functions.php`).
- **File uploads**: no PHP upload-size or MIME-type configuration is set by the app
  itself — it relies on PHP's own `php.ini` limits (`upload_max_filesize`,
  `post_max_size`). Uploaded filenames are sanitized by `sanitize_upload_filename()`
  (`includes/functions.php`) before being saved to disk.
- **Sessions**: uses PHP's default session handling (`session_start()`), no custom
  session configuration.

## 7. Folder Structure

```text
D-Leverage-Photography-v2.4/
├── config/
│   └── connection.php          # Database connection (credentials live here)
├── database/
│   └── schema.sql               # Table definitions — run this to set up the DB
├── actions/                     # Request handlers: forms POST here, AJAX calls here
│   ├── login.php / logout.php / register.php
│   ├── contactSubmit.php
│   ├── upload.php / updateImage.php / updateCategory.php / deleteImage.php / table-portfolio.php
│   ├── uploadAlbum.php / upload_album_img.php / delete_album.php / delete_album_image.php
│   └── inbox-markAsRead.php / deleteMessages.php
├── admin/                       # Admin-only pages (require login)
│   ├── dashboard.php
│   ├── portfolio.php
│   ├── albums.php / album-view.php
│   └── inbox.php / inbox-read.php
├── includes/                    # Shared PHP code
│   ├── header.php / footer.php                    # Public page layout
│   ├── admin-head.php / admin-header.php / admin-footer.php  # Admin page layout
│   ├── functions.php            # Small general-purpose helpers
│   ├── auth.php                 # require_login() / require_ajax_login()
│   ├── flash.php                # set_flash() / display_flash()
│   ├── csrf.php                 # csrf_token() / csrf_field() / verify_csrf_token()
│   ├── db/                      # Database-access functions, one file per table group
│   │   ├── images.php
│   │   ├── albums.php
│   │   └── messages.php
│   ├── logic/                   # Business-rule functions
│   │   └── messages.php
│   └── views/                   # Shared HTML partials
│       └── messages/
│           ├── message-details.php
│           └── summary-badges.php
├── css/
│   ├── style.css                # Public site styles
│   └── admin.css                # Admin area styles
├── js/
│   ├── script.js                # Public site behavior (nav, contact form AJAX)
│   ├── adminScript.js           # Admin dropdown, album modal, album search/AJAX
│   ├── delete-form.js           # Shared delete-confirmation dialog
│   └── magnificpopup.js         # Vendor-ish lightbox init
├── image/                       # All images — see docs/database.md for how paths are stored
│   ├── static-img/              # Hand-placed site assets (logo, banners)
│   ├── upload-album/            # Album cover photos
│   ├── uploads/                 # Album gallery photos
│   └── (loose files)            # Portfolio images, uploaded via admin/portfolio.php
├── index.php, about.php, portfolio.php, photos.php, photos-category.php,
│   view-album.php, contact.php, adminLogin.php   # Public-facing pages, all at the root
├── docs/                        # This documentation
├── README.md
└── .gitignore
```

## 8. Architecture Overview

```text
User (browser)
 ↓
Page (a public page at the root, or an admin/*.php page)
 ↓
Request Processing (an actions/*.php handler reads $_POST/$_GET, checks auth/CSRF)
 ↓
Business Logic (includes/logic/*.php — validation, rules — currently built out for
                the messages module; other modules keep this step inline for now)
 ↓
Database (includes/db/*.php — prepared-statement functions using the shared
          mysqli connection from config/connection.php)
 ↓
Result (PHP arrays/booleans)
 ↓
View/UI (the page's own HTML, plus shared partials under includes/views/)
 ↓
User (sees the rendered page, or the AJAX response)
```

This is plain procedural PHP — no framework, no classes for business logic, no ORM.
See [`docs/architecture.md`](docs/architecture.md) for the full explanation, including
which modules have a business-logic layer today and which still keep that logic inline
(a deliberate, incremental choice — see that document for why).

## 9. Authentication

- **Login**: `adminLogin.php` shows the login form; it POSTs to `actions/login.php`,
  which checks the submitted username/password against the `admin` table
  (`password_verify()` against a bcrypt hash) and, on success, sets
  `$_SESSION['loggedin'] = true`.
- **Logout**: `actions/logout.php` clears and destroys the session.
- **Sessions**: PHP's built-in session mechanism. `$_SESSION['loggedin']` is the single
  flag that marks a visitor as an authenticated admin.
- **Protected pages**: every page under `admin/`, and every state-changing handler
  under `actions/` (except `login.php`, `register.php`\*, and `contactSubmit.php`,
  which are public by design), calls `require_login()` or `require_ajax_login()`
  (`includes/auth.php`) before doing anything else.
- **Roles/permissions**: there is only one role — "logged in" or not. There is no
  per-user permission system; the `admin` table can hold multiple rows, but the
  application never distinguishes between them.

\* `actions/register.php` has no login check by design — it's a one-time setup script
(see [Security](#16-security)).

## 10. Database

The app uses 5 tables: `image`, `album`, `album_img`, `contactData`, and `admin`. Full
schema, relationships, and how the code reads/writes them are documented in
[`docs/database.md`](docs/database.md).

## 11. Modules

| Module | What it covers | Docs |
|---|---|---|
| Authentication | Login, logout, session, the one-time admin registration script | [`docs/modules/authentication.md`](docs/modules/authentication.md) |
| Dashboard | The admin landing page with content counts | [`docs/modules/dashboard.md`](docs/modules/dashboard.md) |
| Portfolio Images | Uploading, browsing, recategorizing, and deleting portfolio photos | [`docs/modules/portfolio-images.md`](docs/modules/portfolio-images.md) |
| Albums | Creating, editing, deleting albums and their gallery photos | [`docs/modules/albums.md`](docs/modules/albums.md) |
| Messages / Inbox | The contact form and the admin inbox that reads it | [`docs/modules/messages-inbox.md`](docs/modules/messages-inbox.md) |
| Public Pages | The static/informational pages (home, about) | [`docs/modules/public-pages.md`](docs/modules/public-pages.md) |

## 12. Development Workflow

```text
Understand the task
 ↓
Find the affected module (docs/modules/) or the affected feature (docs/features/)
 ↓
Implement the change (see docs/development-guide.md for how each layer works)
 ↓
Test manually against a real database (see "Testing" below)
 ↓
Update the relevant documentation file(s) if behavior changed
 ↓
Commit, with a message describing what changed and why
```

## 13. Creating a New Module

See [`docs/development-guide.md`](docs/development-guide.md) for full, step-by-step
instructions, using this project's actual folder layout as the template.

## 14. Creating a New Feature

A "feature" here usually means: a new page (or new behavior on an existing page), a
new form or AJAX action, and — if it touches the database — new functions in
`includes/db/` and, if there are real business rules, `includes/logic/`. See
[`docs/development-guide.md`](docs/development-guide.md) section 5 for the full
walk-through.

## 15. Coding Standards

See [`docs/coding-standards.md`](docs/coding-standards.md) for naming conventions,
how PHP/HTML/CSS/JS/SQL are organized in this project, and when to (and not to) write
comments.

## 16. Security

- **SQL injection prevention**: every database query that includes a value from the
  user (form input, URL parameters) uses a **prepared statement** with bound
  parameters. This was not always true historically — `actions/delete_album.php` used
  to build a query by concatenating a string, and has since been fixed.
- **XSS prevention**: output that comes from the database or from user input is passed
  through `htmlspecialchars()` before being printed into HTML. Filenames used inside
  URLs are additionally passed through `rawurlencode()`.
- **Authentication**: every admin page and action checks `require_login()` /
  `require_ajax_login()` before doing anything (see section 9 above).
- **Authorization**: not applicable beyond "logged in or not" — see section 9.
- **CSRF**: implemented (`includes/csrf.php`) and fully applied to the **Messages /
  Inbox module's** state-changing actions (mark as read/unread, delete message) — see
  [`docs/features/csrf-protection.md`](docs/features/csrf-protection.md). **It is not
  yet applied to the Portfolio Images or Albums modules' forms** — this is a known,
  explicitly tracked gap, not an oversight; extending it is the recommended next step.
- **Input validation**: the contact form (`includes/logic/messages.php`) validates that
  all required fields are present and that the email address is a valid format before
  writing to the database. The admin-side upload/album forms rely mostly on the HTML
  `required` attribute and file-type restrictions in the browser — server-side
  validation there is minimal (see the relevant module docs for specifics).
- **File upload security**: uploaded filenames are sanitized (`sanitize_upload_filename()`
  in `includes/functions.php`) to strip anything but letters, numbers, `_` and `-`
  before the file is saved, which also prevents path-traversal via the filename itself.
- **Session security**: relies on PHP's default session cookie behavior; no additional
  hardening (e.g. `session_regenerate_id()` on login) is currently implemented.

## 17. Troubleshooting

```text
Problem:
Database connection failed / "Connection failed: ..." message on every page

Possible cause:
Incorrect database credentials, or MySQL isn't running

Solution:
Check config/connection.php's $host/$username/$password/$database/$port values,
and confirm your MySQL server is running and reachable on that host/port.
```

```text
Problem:
Visiting actions/register.php a second time shows a PHP fatal error
("Uncaught mysqli_sql_exception: Duplicate entry 'admin' for key 'admin.username'")

Possible cause:
The admin account already exists — this script has no guard against running twice.

Solution:
This is expected. The account was already created the first time you ran it. Do not
run register.php again once the admin account exists.
```

```text
Problem:
An uploaded portfolio or album image doesn't display (broken image icon)

Possible cause:
The file wasn't sanitized before this project's current version (old rows may have
filenames with spaces or symbols), or the file was deleted from image/ manually
without also deleting its database row.

Solution:
Check that the file still exists at the path stored in the database (see
docs/database.md for how each table stores its image path), and that the filename in
the URL is being rawurlencode()'d if it contains special characters.
```

```text
Problem:
Submitting the contact form always returns "You're Already Send a message" (-2)

Possible cause:
The `contactData.email` column has a UNIQUE constraint — this is a deliberate
business rule ("one booking request per email address"), not a bug. If you're testing
repeatedly with the same email, either delete that row or use a different email.

Solution:
See docs/features/contact-booking-form.md for the exact rule.
```

```text
Problem:
An admin form submission is rejected with "Invalid or expired form submission"

Possible cause:
This only happens on the Messages/Inbox module's forms, which are CSRF-protected. It
means the page was loaded a long time ago (its token expired with the session) or the
form was submitted from somewhere other than the rendered page.

Solution:
Refresh the page and try again. See docs/features/csrf-protection.md.
```

```text
Problem:
Pages/CSS/JS break (unstyled page, broken links) after moving a file to a new folder

Possible cause:
This app computes its <base href> from the current page's URL depth (see
includes/header.php and includes/admin-head.php). Moving a page to a different folder
depth without updating that calculation, or without updating its relative links, will
break every relative asset link on that page.

Solution:
See docs/architecture.md section 2 ("Technology Architecture") for exactly how this
works before moving any page file.
```

## 18. Testing

There is no automated test suite in this project. Testing is manual. A practical
checklist:

```text
- [ ] php -l on every changed file (catches syntax errors before you even open a browser)
- [ ] Log in and out
- [ ] Visit every page you touched, logged in and logged out
- [ ] For a Create/Update/Delete change: perform the action, then verify the result
      both in the UI and directly in the database
- [ ] Check that a matching flash message (if any) appears once, then disappears on
      the next page load
- [ ] For AJAX actions: check the browser's Network tab for the actual response, not
      just "did the page look okay"
- [ ] For anything touching file uploads: confirm the file actually landed in the
      right folder on disk, not just that the database row was created
```

Each module's documentation includes a module-specific checklist — see the "Testing
Checklist" section in each file under `docs/modules/`.

## 19. Project Status

This project is a **working, actively-developed personal/small-business website**, not
a public open-source library or a large team project. TODO: confirm with the project
owner whether it is currently live in production, or still in local development —
this could not be determined from the code alone.

---

## Documentation

```text
Documentation
│
├── Architecture
│   └── docs/architecture.md
│
├── Database
│   └── docs/database.md
│
├── Development Guide
│   └── docs/development-guide.md
│
├── Coding Standards
│   └── docs/coding-standards.md
│
├── Modules
│   ├── docs/modules/authentication.md
│   ├── docs/modules/dashboard.md
│   ├── docs/modules/portfolio-images.md
│   ├── docs/modules/albums.md
│   ├── docs/modules/messages-inbox.md
│   └── docs/modules/public-pages.md
│
└── Features
    ├── docs/features/contact-booking-form.md
    ├── docs/features/photo-gallery-browsing.md
    ├── docs/features/album-browsing.md
    ├── docs/features/access-control.md
    ├── docs/features/flash-messages.md
    └── docs/features/csrf-protection.md
```
