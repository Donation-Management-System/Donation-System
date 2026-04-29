# DonateToCare — Donation Management System

A full-stack web-based donation platform built with **HTML + JavaScript (frontend)** and **PHP + MySQL (backend)**. Supports SSLCommerz payment gateway, donor dashboard, admin panel, PDF receipt generation, and donation tracking.

---

## Features

- Donor registration & login
- Multiple donation categories (Education, Zakat, Religion, Human Welfare, Student Support)
- SSLCommerz payment integration (sandbox & live)
- Donor dashboard with donation history
- Admin panel — manage donations, upload images, add notes, update statuses
- PDF receipt download (FPDF library)
- Donation tracking page

---

## Tech Stack

| Layer    | Technology                        |
|----------|-----------------------------------|
| Frontend | HTML5, Tailwind CSS (CDN), Vanilla JS |
| Backend  | PHP 8+                            |
| Database | MySQL                             |
| Payment  | SSLCommerz                        |
| PDF      | FPDF library (included)           |
| Hosting  | Any PHP host (InfinityFree, cPanel, localhost) |

---

## Project Structure

```
donation-system/
├── index.html                  # Home page
├── register.html               # Donor registration
├── donor.html                  # Donor login
├── donor-dashboard.html        # Donor dashboard
├── categories.html             # Donation categories
├── education.html              # Education category page
├── religion.html               # Religion category page
├── humanWelfare.html           # Human welfare category
├── religion-payment.html       # Religion payment form
├── student-lib-payment.html    # Student library payment
├── student-sp-payment.html     # Student sponsorship payment
├── donation-receipt.html       # Donation receipt page
├── donation-lib-receipt.html   # Library donation receipt
├── tracking.html               # Donation tracking
├── admin.html                  # Admin login
├── admin-dashboard.html        # Admin dashboard
├── admin-update.html           # Admin content manager
├── config.js                   # ← Frontend URL config (auto-detects localhost vs live)
├── css/
│   └── tailwind.css
├── database/
│   └── donation.sql            # Database schema
└── backend/
    ├── config/
    │   ├── config.php          # .env loader
    │   └── db.php              # Database connection
    ├── api/
    │   ├── login.php
    │   ├── register.php
    │   ├── donate.php
    │   ├── ssl_payment.php
    │   ├── success.php
    │   ├── fail.php
    │   ├── cancel.php
    │   ├── admin.php
    │   ├── admin_status.php
    │   ├── get_donation.php
    │   ├── get_user_donations.php
    │   ├── update_status.php
    │   ├── tracking.php
    │   ├── get_receipt.php
    │   ├── download_receipt.php
    │   ├── get_note.php
    │   ├── save_note.php
    │   ├── get_images.php
    │   ├── upload_image.php
    │   └── delete_image.php
    ├── uploads/                # Admin-uploaded images (gitignored)
    └── fpdf/                   # FPDF library for PDF generation
```

---

## Local Setup

### Requirements
- PHP 8.0+
- MySQL 5.7+ or MariaDB
- Web server: Apache / Nginx / XAMPP / WAMP / Laragon

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/your-username/donation-system.git
cd donation-system
```

**2. Create your `.env` file**
```bash
cp .env.example .env
```
Edit `.env` with your actual database and SSLCommerz credentials.

**3. Import the database**
- Open phpMyAdmin (or MySQL CLI)
- Create a new database (same name as `DB_NAME` in your `.env`)
- Import `database/donation.sql`

**4. Set up default admin account**

After importing, update the admin password hash in the database:
```sql
UPDATE users
SET password = '$2y$10$YOUR_HASH_HERE'
WHERE email = 'admin@gmail.com';
```

To generate a bcrypt hash, run this PHP snippet:
```php
<?php echo password_hash('your_password', PASSWORD_DEFAULT); ?>
```

**5. Start the server**

Using XAMPP/WAMP, place the project inside `htdocs/` folder and visit:
```
http://localhost/donation-system/
```

---

## Environment Variables

Copy `.env.example` to `.env` and fill in your values:

```env
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=donation_db

SITE_URL=http://localhost/donation-system

SSL_STORE_ID=your_store_id
SSL_STORE_PASS=your_store_password
SSL_MODE=sandbox
```

> Set `SSL_MODE=live` and update store credentials when deploying to production.

---

## Deployment (InfinityFree / cPanel)

1. Upload all files via FileZilla or cPanel File Manager to `htdocs/`
2. Create a MySQL database in your hosting control panel
3. Import `database/donation.sql` via phpMyAdmin
4. Create a `.env` file in the root (`htdocs/`) with your live credentials
5. Update `SITE_URL` to your actual domain
6. Set `SSL_MODE=live` and use real SSLCommerz credentials

---

## Branching Strategy

```
main        ← stable production code only
└── dev     ← active development
    ├── feature/your-feature-name
    └── fix/bug-description
```

**Workflow:**
```bash
git checkout dev
git checkout -b feature/my-new-feature
# ... make changes ...
git add .
git commit -m "feat: describe your change"
git checkout dev
git merge feature/my-new-feature
git push origin dev
# When ready for production:
git checkout main
git merge dev
git push origin main
```

---

## Default Admin Login

| Field    | Value             |
|----------|-------------------|
| Email    | admin@gmail.com   |
| Password | admin123          |

> Change this password immediately after deployment!

---

## License

MIT License — free to use and modify.
