<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';
require_admin();

// Fetch summary metrics
$totalProjects = (int)$pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn();
$activeProjects = (int)$pdo->query('SELECT COUNT(*) FROM projects WHERE is_active = 1')->fetchColumn();
$featuredProjects = (int)$pdo->query('SELECT COUNT(*) FROM projects WHERE featured = 1')->fetchColumn();
$newEnquiries = (int)$pdo->query("SELECT COUNT(*) FROM enquiries WHERE status = 'New'")->fetchColumn();
$allEnquiries = (int)$pdo->query('SELECT COUNT(*) FROM enquiries')->fetchColumn();

// Fetch recent 8 enquiries with project titles
$recentEnquiries = $pdo->query('
    SELECT e.*, p.title AS project_title, p.slug AS project_slug 
    FROM enquiries e 
    LEFT JOIN projects p ON p.id = e.project_id 
    ORDER BY e.created_at DESC 
    LIMIT 6
')->fetchAll();

// Fetch recent 4 projects
$recentProjects = $pdo->query('
    SELECT * FROM projects 
    ORDER BY id DESC 
    LIMIT 4
')->fetchAll();

// Handle quick inline status update from dashboard
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['update_enquiry_status'])) {
    verify_csrf();
    $eid = (int)($_POST['enquiry_id'] ?? 0);
    $newStatus = trim($_POST['status'] ?? 'New');
    if ($eid && in_array($newStatus, ['New', 'Contacted', 'Closed'], true)) {
        $stmt = $pdo->prepare('UPDATE enquiries SET status = ? WHERE id = ?');
        $stmt->execute([$newStatus, $eid]);
    }
    header('Location: index.php?updated=1');
    exit;
}

$pageTitle = 'Executive Dashboard';
$activeNav = 'dashboard';
require_once __DIR__ . '/header.php';
?>

<!-- Welcome Banner -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="font-size: 1.75rem; color: #0f172a;">
            Welcome back, <span style="color: var(--adm-gold);"><?= e($_SESSION['admin_name'] ?? 'Administrator') ?></span> 👋
        </h2>
        <p class="text-secondary mb-0">Overview of portfolio inventory, customer leads, and architectural assets.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="enquiries.php" class="btn-adm-outline">
            <i class="fa-solid fa-inbox"></i> View All Enquiries
        </a>
        <a href="project-form.php" class="btn-adm-gold">
            <i class="fa-solid fa-plus"></i> Add New Project
        </a>
    </div>
</div>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="fa-solid fa-circle-check text-success fs-5"></i>
        <div>Enquiry status updated successfully.</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- 4 Key Metrics Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card-luxury">
            <div class="kpi-icon-box kpi-icon-gold">
                <i class="fa-solid fa-building"></i>
            </div>
            <div class="kpi-meta">
                <span>Total Projects</span>
                <h3><?= $totalProjects ?></h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card-luxury">
            <div class="kpi-icon-box kpi-icon-green">
                <i class="fa-solid fa-house-circle-check"></i>
            </div>
            <div class="kpi-meta">
                <span>Live Listings</span>
                <h3><?= $activeProjects ?></h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card-luxury">
            <div class="kpi-icon-box kpi-icon-rose">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <div class="kpi-meta">
                <span>New Enquiries</span>
                <h3 class="<?= $newEnquiries > 0 ? 'text-danger' : '' ?>"><?= $newEnquiries ?></h3>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card-luxury">
            <div class="kpi-icon-box kpi-icon-blue">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="kpi-meta">
                <span>Total Leads</span>
                <h3><?= $allEnquiries ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Two-Column Layout: Recent Enquiries (Left) & Projects Spotlight (Right) -->
<div class="row g-4">
    <!-- Left Column: Recent Enquiries -->
    <div class="col-xl-8">
        <div class="admin-card p-0 overflow-hidden">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-1 text-dark" style="font-size: 1.15rem;">
                        <i class="fa-solid fa-bell text-warning me-2"></i> Recent Inquiries & Leads
                    </h4>
                    <small class="text-secondary">Prospective buyers requesting consultation and site visits.</small>
                </div>
                <a href="enquiries.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    View All (<?= $allEnquiries ?>) <i class="fa-solid fa-chevron-right ms-1"></i>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table admin-table align-middle">
                    <thead>
                        <tr>
                            <th>Client / Lead</th>
                            <th>Contact Desk</th>
                            <th>Interested In</th>
                            <th>Status</th>
                            <th>Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentEnquiries as $re): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="sidebar-avatar" style="width: 34px; height: 34px; font-size: 0.82rem;">
                                            <?= strtoupper(substr($re['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <strong class="d-block text-dark"><?= e($re['name']) ?></strong>
                                            <?php if (!empty($re['message'])): ?>
                                                <small class="text-muted d-inline-block text-truncate" style="max-width: 180px;" title="<?= e($re['message']) ?>">
                                                    <?= e($re['message']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="tel:<?= e($re['phone']) ?>" class="text-dark fw-bold text-decoration-none d-block small">
                                        <i class="fa-solid fa-phone text-secondary me-1"></i> <?= e($re['phone']) ?>
                                    </a>
                                    <?php if ($re['email']): ?>
                                        <a href="mailto:<?= e($re['email']) ?>" class="text-secondary small text-decoration-none">
                                            <?= e($re['email']) ?>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-semibold">
                                        <?= e($re['project_title'] ?? 'General Consultation') ?>
                                    </span>
                                    <?php if ($re['budget']): ?>
                                        <small class="d-block text-secondary mt-1"><?= e($re['budget']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                        <input type="hidden" name="enquiry_id" value="<?= $re['id'] ?>">
                                        <input type="hidden" name="update_enquiry_status" value="1">
                                        <select name="status" class="form-select form-select-sm status-pill <?= $re['status'] === 'New' ? 'status-pill-new' : ($re['status'] === 'Contacted' ? 'status-pill-contacted' : 'status-pill-closed') ?>" onchange="this.form.submit()" style="cursor: pointer;">
                                            <option value="New" <?= $re['status'] === 'New' ? 'selected' : '' ?>>🔴 New</option>
                                            <option value="Contacted" <?= $re['status'] === 'Contacted' ? 'selected' : '' ?>>🔵 Contacted</option>
                                            <option value="Closed" <?= $re['status'] === 'Closed' ? 'selected' : '' ?>>🟢 Closed</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <small class="text-secondary" title="<?= e($re['created_at']) ?>">
                                        <?= date('d M, h:i A', strtotime($re['created_at'])) ?>
                                    </small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recentEnquiries)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-secondary">
                                    <i class="fa-regular fa-folder-open fs-2 d-block mb-2 text-muted"></i>
                                    No customer enquiries logged yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Projects Quick Showcase -->
    <div class="col-xl-4">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem;">
                    <i class="fa-solid fa-crown text-warning me-2"></i> Inventory Snapshot
                </h4>
                <a href="projects.php" class="small text-secondary fw-semibold text-decoration-none">
                    View All
                </a>
            </div>

            <div class="d-flex flex-column gap-3">
                <?php foreach ($recentProjects as $rp): ?>
                    <div class="p-3 rounded-3 border bg-light bg-opacity-50 d-flex align-items-center gap-3">
                        <img src="<?= e(project_image($rp['image'])) ?>" alt="<?= e($rp['title']) ?>" class="project-thumb-preview" onerror="this.src='../uploads/projects/adore-legend-2.jpg'">
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="mb-0 fw-bold text-dark text-truncate" title="<?= e($rp['title']) ?>">
                                <?= e($rp['title']) ?>
                            </h6>
                            <small class="text-secondary d-block text-truncate">
                                <i class="fa-solid fa-location-dot me-1 text-danger"></i> <?= e($rp['location']) ?>
                            </small>
                            <span class="badge bg-warning bg-opacity-25 text-dark fw-bold mt-1" style="font-size: 0.72rem;">
                                <?= e($rp['price'] ?: 'On Request') ?>
                            </span>
                        </div>
                        <div>
                            <a href="project-form.php?id=<?= $rp['id'] ?>" class="btn btn-sm btn-outline-dark rounded-circle" style="width: 32px; height: 32px; display: grid; place-items: center;" title="Edit Project">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($recentProjects)): ?>
                    <p class="text-secondary text-center py-4 mb-0">No projects added yet.</p>
                <?php endif; ?>
            </div>

            <div class="mt-4 pt-2 border-top">
                <a href="project-form.php" class="btn btn-outline-secondary w-100 rounded-pill py-2 fw-semibold">
                    <i class="fa-solid fa-plus me-1 text-warning"></i> Add Another Project
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>