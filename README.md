# Property Station — Core PHP + Bootstrap 5

A responsive real-estate website with a public project catalogue and a PHP/MySQL admin area.

## Requirements
- PHP 8.1+ (8.2/8.3 recommended)
- MySQL 5.7+ or MariaDB
- PHP extensions: PDO MySQL, Fileinfo
- Apache/cPanel or a PHP development server

## Setup
1. Create a MySQL database and user in cPanel/phpMyAdmin.
2. Import `database.sql` into the database.
3. Edit `config.php` and set `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, phone and email.
4. Copy your approved logo image to `assets/logo.jpeg`. The supplied logo is included in this package.
5. Ensure `uploads/projects/` is writable by PHP (usually 755; hosting may require 775).
6. Create the first admin password hash by running:
   `php -r "echo password_hash('ChooseYourStrongPassword', PASSWORD_DEFAULT), PHP_EOL;"`
7. In phpMyAdmin run this query, replacing the hash and email:
   `INSERT INTO admins (name,email,password_hash) VALUES ('Administrator','admin@yourdomain.com','PASTE_GENERATED_HASH');`
8. Upload all files to your hosting `public_html` directory (or a subdirectory).
9. Open the website, then visit `/admin/login.php`.

## Admin features
- Dashboard with project and enquiry totals
- Add/edit/delete projects
- Project image upload (JPG, PNG, WebP, max 5 MB)
- Category, location, price, status, featured and visibility fields
- View enquiries and update status
- Public project search and detail pages

## Important before launch
- Replace the placeholder phone and email in `config.php`.
- Add only verified project names, prices, locations, status, RERA details and images.
- Change the default/example admin email and use a strong unique password.
- Enable HTTPS on hosting.
- Configure automated database backups.
- Consider rate limiting and a CAPTCHA for public forms before running high-volume ad campaigns.
- The site uses Bootstrap 5 CDN and remote Unsplash placeholder photos for cards without uploaded project images. Replace these with authorised project photos.
- This is a starter implementation. Have your hosting/security provider review production configuration before collecting real customer data.

## Pages
- `index.php` — home, featured projects, total count, enquiry form
- `projects.php` — searchable project catalogue
- `project.php?slug=...` — project detail and enquiry form
- `admin/login.php` — admin login
- `admin/index.php` — dashboard
- `admin/projects.php` — manage projects
- `admin/project-form.php` — create/edit project
- `admin/enquiries.php` — enquiry inbox
