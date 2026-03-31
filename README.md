# DigitalTable (PHP + MySQL)

Production-ready lightweight restaurant ordering system for shared hosting (Hostinger compatible).

## Features
- Customer panel with table-based ordering (QR URL format: `public/index.php?table_id=1`)
- Menu categories, cart, GST, checkout, order tracking
- Admin login with dashboard metrics and live updates every 3s
- Order actions (accept/reject), sound alert, search/filter
- Menu/category/table CRUD, availability toggle, image upload
- QR generation/download for tables (cached PNG with dynamic fallback endpoint)
- Analytics with Chart.js (daily/monthly/top items)
- CSV export
- Invoice generation and month-wise storage
- Dark/light mode toggle

## Folder Structure
- `public/` customer UI
- `admin/` admin panel
- `api/` AJAX endpoints
- `config/` DB and helpers
- `assets/` CSS/JS
- `schema/` SQL
- `uploads/` menu and QR files
- `invoices/` generated invoices

## Setup
1. Create MySQL DB and import `schema/digitaltable.sql` via phpMyAdmin.
2. Update `config/config.php` with DB host/name/user/password and `BASE_URL`.
3. Upload project files to shared hosting `public_html` folder.
4. Ensure write permissions (`775`) for:
   - `uploads/menu_images/`
   - `uploads/qrcodes/`
   - `invoices/`
5. Open admin: `/admin/login.php`
   - Email: `admin@digitaltable.com`
   - Password: `admin123`

If you already imported an older schema, reset admin password with:
```sql
UPDATE admins SET password_hash = '$2y$10$d5t1OkOHZIpIoou9nvtrZOahnr/hYYa3pgxUL9bO/nkbEToBp4.ku' WHERE email='admin@digitaltable.com';
```

## Security + Performance Notes
- Uses prepared statements (PDO) against SQL injection
- Session-based admin auth
- Indexed tables for high read/write performance
- Mobile-first UI and lightweight fetch polling

## Invoice PDF Note
Current implementation stores invoice as downloadable HTML invoice (shared-hosting friendly, zero dependency).
To use strict PDF output, integrate a small PHP PDF library (FPDF/TCPDF) and replace invoice writer in `api/update_order_status.php`.
