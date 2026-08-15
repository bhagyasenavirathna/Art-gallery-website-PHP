# Arts Gallery Premium - PHP/MySQL Project

A complete modern, professional, premium E-Commerce Art Gallery website built according to the provided project proposal and SRS.

## Main Features

- Premium responsive landing page with full hero background image
- Artwork gallery with search, category filter, artist filter
- Artwork detail pages
- Customer registration and secure email OTP login
- Shopping cart
- Artwork order placement
- Reference image upload for custom artwork requests
- Customer dashboard and order tracking
- Customer/admin messaging system
- Admin dashboard
- Manage artworks, artists, orders, customers, and messages
- Inventory, sales, and customer reports
- PHPMailer SMTP email system with local log fallback
- 6-digit OTP verification when admin/customer login
- Mobile responsive layout lock
- Real JPG artwork/article images included
- Sinhala comments added in code files for beginners

## Technologies

- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL
- Server: XAMPP / Apache
- Editor: Visual Studio Code

## PHPMailer OTP Email Setup

This update adds email OTP verification during login. After entering the correct email and password, the system sends a 6-digit OTP code to the user's email.

1. Open the project folder in VS Code.
2. Open Terminal inside the project folder and run:
   `composer install`
3. Open `config/mail.php`.
4. Add your SMTP email details:
   - `MAIL_USERNAME` = your SMTP/Gmail email
   - `MAIL_PASSWORD` = your SMTP password or Gmail App Password
   - `MAIL_FROM_EMAIL` = sender email
5. For Gmail, keep:
   - `MAIL_HOST` = `smtp.gmail.com`
   - `MAIL_PORT` = `587`
   - `MAIL_ENCRYPTION` = `tls`

Local testing fallback: if Composer/SMTP is not configured yet, the OTP email content is saved in `storage/logs/email.log` so you can still test the login flow in XAMPP.

## How to Run in XAMPP

1. Copy the folder `art-gallery-premium` into:
   `C:\xampp\htdocs\`

2. Open XAMPP Control Panel and start:
   - Apache
   - MySQL

3. Open phpMyAdmin:
   `http://localhost/phpmyadmin`

4. Import the database file:
   `database/art_gallery.sql`

5. Install PHPMailer using Composer inside the project folder:
   `composer install`

6. Configure SMTP details in:
   `config/mail.php`

7. Open the website:
   `http://localhost/art-gallery-premium/`

## Login Details

Admin:
- Email: `admin@artgallery.lk`
- Password: `admin123`

Customer:
- Email: `customer@artgallery.lk`
- Password: `customer123`

## Important Notes

- No online payment integration is included because the project proposal/SRS states that online payment is out of scope.
- Login now requires email OTP verification using PHPMailer SMTP.
- If SMTP is not configured yet, OTP and email notifications are written to `storage/logs/email.log` for local testing.
- If you rename the project folder, update `BASE_URL` in `config/app.php`.
- If you already imported the old database before this update, run `database/update_real_images.sql` once to update image paths.
- Uploaded reference images are saved in `uploads/reference/`.
