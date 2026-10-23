<?php

declare(strict_types=1);
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'property_station');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SITE_NAME', 'Property Station');
define('SITE_TAGLINE', 'Ghar Ka Aakhri Station');
define('SITE_PHONE', '+91 80008 10016');
define('SITE_EMAIL', 'info@propertystation.in');
define('SITE_WHATSAPP', '918000810016');
define('SITE_LOCATION', 'Sector 15 Green Corridor, Faridabad');
define('SITE_OFFICE', 'Legal & Sales: Sector 15 Green Corridor, Faridabad, Delhi NCR');
define('UPLOAD_DIR', __DIR__ . '/uploads/projects/');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed. Check config.php and import database.sql.');
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf(): void
{
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        http_response_code(419);
        exit('Invalid security token. Please go back and try again.');
    }
}
function is_admin(): bool
{
    return !empty($_SESSION['admin_id']);
}
function require_admin(): void
{
    if (!is_admin()) {
        header('Location: login.php');
        exit;
    }
}
function project_image(?string $image): string
{
    $prefix = (basename(dirname($_SERVER['SCRIPT_FILENAME'] ?? '')) === 'pages') ? '../' : '';
    return $image ? $prefix . 'uploads/projects/' . rawurlencode($image) : 'https://images.unsplash.com/photo-160059_scoped?auto=format&fit=crop&w=900&q=80';
}
