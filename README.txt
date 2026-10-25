PHINMA AU CANTEEN — PHP + MySQL BACKEND
=======================================

FEATURES
- Student account registration and login with password_hash/password_verify
- Registration restricted to @phinmaed.com or @phinma.edu.ph (edit config.php if the school confirms another official domain)
- PHP sessions, CSRF tokens, prepared SQL statements, escaped HTML output
- Product menu loaded from MySQL
- Session cart, quantity update/removal, checkout, order records and student order tracking
- Admin dashboard with counts, product create/edit/show/hide, and order status updates
- No real payment processing. GCash is only a payment-method label and must be manually verified.

REQUIREMENTS
- XAMPP (Apache + PHP 8.1+ + MySQL/MariaDB) or another PHP/MySQL server.

INSTALL ON XAMPP
1. Extract the folder `phinma_au_canteen_backend` into `C:\xampp\htdocs\`.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Open http://localhost/phpmyadmin
4. Import `schema.sql` using the Import tab. This creates the `phinma_canteen` database and starter menu products.
5. If your MySQL root user has a password, edit DB_PASS in `config.php`.
6. Open http://localhost/phinma_au_canteen_backend/create_admin.php
7. Create an admin account with a strong password. The admin email can be your chosen administrator email.
8. IMPORTANT: Delete `create_admin.php` after creating the admin account.
9. Open http://localhost/phinma_au_canteen_backend/auth.php to log in or create a student account.

HOW TO USE
- Students: register/login, add items, update cart quantities, place an order, then open My orders to track its status.
- Admin: log in with the account created by create_admin.php. Admin users are redirected to the dashboard. Use Products to manage the menu and order dropdowns to update order statuses.
- To change accepted student email domains, edit ALLOWED_EMAIL_DOMAINS in config.php. Confirm the official domain with your school first.

IMPORTANT SECURITY NOTES
- This is a learning/demo project, not a production-ready campus payment system.
- Do not collect or store GCash PINs, OTPs, or bank credentials. This app does not process payments.
- Before deploying publicly, configure HTTPS, strong database credentials, backups, rate limiting, logging, account recovery, and a proper admin provisioning process.
- Delete create_admin.php after first use. Never publish database passwords.
