<?php
/**
 * Shared Header Component for Property Station
 * Supports dynamic $pageTitle, $pageDescription, $activePage, $extraHead
 */
if (!isset($pdo)) {
    require_once __DIR__ . '/../config.php';
}

$isSubPage = (basename(dirname($_SERVER['SCRIPT_FILENAME'] ?? '')) === 'pages');
$fWebRoot = $isSubPage ? '../' : '';
$fPagesDir = $isSubPage ? '' : 'pages/';

// Page Defaults
$pageTitle = $pageTitle ?? 'Property Station | Ghar Ka Aakhri Station — Luxury Real Estate in Faridabad & Delhi NCR';
$pageDescription = $pageDescription ?? 'Property Station — Ghar Ka Aakhri Station. Premier real estate consultancy and luxury builder floors in Faridabad, Gurugram and Delhi NCR.';
$activePage = $activePage ?? '';

// Fetch all active projects for dropdown if not already defined
if (!isset($navProjects) && isset($pdo)) {
    try {
        $navProjects = $pdo->query("SELECT id, title, slug, location, price, category FROM projects WHERE is_active = 1 ORDER BY featured DESC, id DESC")->fetchAll();
    } catch (Exception $e) {
        $navProjects = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($pageDescription) ?>">
    <link rel="icon" href="<?= $fWebRoot ?>assets/logo.jpeg" type="image/jpeg">

    <!-- Preconnect & Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Poppins:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Custom Luxury Stylesheet -->
    <link rel="stylesheet" href="<?= $fWebRoot ?>assets/style.css?v=<?= @filemtime(__DIR__ . '/../assets/style.css') ?>">

    <?php if (!empty($extraHead)) echo $extraHead; ?>
</head>
<body>

    <!-- Slim Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">
                <div class="top-bar-left">
                    <span class="top-bar-tag"><i class="fa-solid fa-location-dot"></i> <?= e(SITE_LOCATION) ?></span>
                    <span class="divider">|</span>
                    <span class="top-bar-tag"><i class="fa-solid fa-envelope"></i> <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></span>
                </div>
                <div class="top-bar-right">
                    <span class="top-bar-tag"><i class="fa-solid fa-phone"></i> <a href="tel:<?= e(SITE_PHONE) ?>"><?= e(SITE_PHONE) ?></a></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <nav class="navbar navbar-expand-lg main-header sticky-top" id="mainHeader">
        <div class="container">
            <a class="navbar-brand brand-wrapper" href="<?= $fWebRoot ?>index.php">
                <img src="<?= $fWebRoot ?>assets/logo.jpeg" alt="Property Station Logo" class="brand-logo-img">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#propertyNavbar" aria-controls="propertyNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars text-dark"></i>
            </button>

            <div class="collapse navbar-collapse" id="propertyNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= ($activePage === 'home') ? 'active' : '' ?>" href="<?= $fWebRoot ?>index.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($activePage === 'about') ? 'active' : '' ?>" href="<?= $fPagesDir ?>about.php">ABOUT US</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= ($activePage === 'projects') ? 'active' : '' ?>" href="<?= $fPagesDir ?>projects.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            PROJECTS
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (!empty($navProjects)): ?>
                                <?php foreach ($navProjects as $np): ?>
                                    <li>
                                        <a class="dropdown-item" href="<?= $fPagesDir ?>project.php?slug=<?= urlencode($np['slug']) ?>">
                                            <?= e($np['title']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item fw-bold text-warning" href="<?= $fPagesDir ?>projects.php">View All Projects <i class="fa-solid fa-arrow-right ms-1"></i></a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($activePage === 'specifications') ? 'active' : '' ?>" href="<?= $fPagesDir ?>specifications.php">SPECIFICATIONS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($activePage === 'gallery') ? 'active' : '' ?>" href="<?= $fPagesDir ?>gallery.php">GALLERY</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($activePage === 'contact') ? 'active' : '' ?>" href="<?= $fPagesDir ?>contact.php">CONTACT US</a>
                    </li>
                </ul>

                <div class="header-actions">
                    <a href="tel:<?= e(SITE_PHONE) ?>" class="btn-header-phone">
                        <i class="fa-solid fa-phone"></i>
                        <span><?= e(SITE_PHONE) ?></span>
                    </a>
                    <button type="button" class="btn-luxury" data-bs-toggle="modal" data-bs-target="#enquireModal">
                        <i class="fa-solid fa-paper-plane"></i> Enquire Now
                    </button>
                </div>
            </div>
        </div>
    </nav>
