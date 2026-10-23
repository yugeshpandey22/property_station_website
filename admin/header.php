<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/../config.php';
}
require_admin();

$pageTitle = $pageTitle ?? 'Admin Console';
$activeNav = $activeNav ?? 'dashboard';

// Enquiries badge count
try {
    $newEnquiryCount = (int)$pdo->query("SELECT COUNT(*) FROM enquiries WHERE status = 'New'")->fetchColumn();
} catch (Exception $e) {
    $newEnquiryCount = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | Property Station Admin</title>
    <link rel="icon" href="../assets/logo.jpeg" type="image/jpeg">

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Admin Luxury CSS -->
    <link rel="stylesheet" href="admin-style.css?v=<?= filemtime(__DIR__ . '/admin-style.css') ?>">
</head>
<body class="admin-body">

    <!-- Luxury Sidebar Navigation -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand-box">
            <img src="../assets/logo.jpeg" alt="Property Station Logo" class="sidebar-logo">
            <div class="sidebar-brand-text">
                <h4>Property Station</h4>
                <span>Ghar Ka Aakhri Station</span>
            </div>
        </div>

        <div class="sidebar-nav-wrap">
            <div class="nav-section-title">Main Menu</div>
            <a href="index.php" class="admin-nav-item <?= $activeNav === 'dashboard' ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
            <a href="projects.php" class="admin-nav-item <?= $activeNav === 'projects' ? 'active' : '' ?>">
                <i class="fa-solid fa-building"></i>
                <span>Manage Projects</span>
            </a>
            <a href="project-form.php" class="admin-nav-item <?= $activeNav === 'add_project' ? 'active' : '' ?>">
                <i class="fa-solid fa-square-plus"></i>
                <span>Add New Project</span>
            </a>
            <a href="enquiries.php" class="admin-nav-item <?= $activeNav === 'enquiries' ? 'active' : '' ?>">
                <i class="fa-solid fa-envelope-open-text"></i>
                <span>Enquiries</span>
                <?php if ($newEnquiryCount > 0): ?>
                    <span class="nav-badge-count"><?= $newEnquiryCount ?></span>
                <?php endif; ?>
            </a>

            <div class="nav-section-title mt-4">Website Shortcuts</div>
            <a href="../index.php" target="_blank" class="admin-nav-item">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>View Live Website</span>
            </a>
            <a href="../pages/about.php" target="_blank" class="admin-nav-item">
                <i class="fa-solid fa-circle-info"></i>
                <span>About Us Page</span>
            </a>
            <a href="../pages/contact.php" target="_blank" class="admin-nav-item">
                <i class="fa-solid fa-headset"></i>
                <span>Contact Page</span>
            </a>
        </div>

        <div class="sidebar-footer-box">
            <div class="sidebar-user-card">
                <div class="sidebar-avatar">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div class="sidebar-user-meta">
                    <strong><?= e($_SESSION['admin_name'] ?? 'Administrator') ?></strong>
                    <small>Super Admin</small>
                </div>
                <a href="logout.php" class="text-danger fs-5" title="Sign Out" aria-label="Sign Out">
                    <i class="fa-solid fa-power-off"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main-wrap">
        <!-- Sticky Top Bar -->
        <header class="admin-topbar">
            <div class="topbar-left">
                <button type="button" class="sidebar-toggle-btn" id="sidebarToggle" aria-label="Toggle Sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="page-breadcrumb-title">
                    <h3><?= e($pageTitle) ?></h3>
                </div>
            </div>

            <div class="topbar-actions">
                <a href="../index.php" target="_blank" class="btn-view-site">
                    <i class="fa-solid fa-globe text-warning"></i>
                    <span>Live Website</span>
                </a>
                <a href="project-form.php" class="btn-adm-gold py-2 px-3">
                    <i class="fa-solid fa-plus"></i>
                    <span class="d-none d-sm-inline">Add Project</span>
                </a>
                <a href="logout.php" class="btn-admin-logout" title="Logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span class="d-none d-md-inline">Logout</span>
                </a>
            </div>
        </header>

        <!-- Page Dynamic Body Container -->
        <main class="admin-content-pad">
