# Adolphe Civil Engineering — Muganza, Rwanda

Modern futuristic civil engineering website — PHP 8+ • MySQL • Bootstrap 5 • PDO

Tagline: **Engineering the Future. Building with Precision.**

## Quick Start (XAMPP)

1. Install XAMPP (PHP 8.1+ / MySQL / Apache).
2. Copy project folder to `C:\xampp\htdocs\adolphe-engineering` (or `adolphe`).
3. Start Apache + MySQL in XAMPP Control Panel.
4. Create database: open `http://localhost/phpmyadmin` → Import `database/adolphe_engineering.sql`.
5. Edit `config/database.php` if needed (default: root / no password, db `adolphe_engineering`).
6. Visit `http://localhost/adolphe-engineering/index.php`.
7. Admin: `http://localhost/adolphe-engineering/admin/login.php` — **admin@adolphe.rw / Admin@123**

To change admin password: login → Settings → Change Password, or reset via SQL: `UPDATE admins SET password = '$2y$10$...' WHERE email='admin@adolphe.rw'` (generate with `password_hash('newpass', PASSWORD_DEFAULT)`).

## Deployment (cPanel / Shared Hosting)

1. Upload all files via FTP / File Manager to `public_html/`.
2. Create MySQL database + user in cPanel, import `database/adolphe_engineering.sql`.
3. Update `config/database.php` with host/db/user/pass.
4. Set folder permissions: `uploads/` → 755.
5. Ensure PHP 8+.
6. Set site URL in sitemap.xml and settings.

## Security

- PDO prepared statements, password_hash/verify, CSRF tokens, XSS escaping (htmlspecialchars), MIME+extension validation, unique filenames, 5MB limit, thumbnail generation, session timeout 30min, login rate limiting.

## Structure

See `database/adolphe_engineering.sql` for schema, `admin/` for dashboard CRUD.
