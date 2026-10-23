<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';
require_admin();

// Handle deletion
$msg = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['delete_project'])) {
    verify_csrf();
    $delId = (int)($_POST['id'] ?? 0);
    if ($delId) {
        $stmt = $pdo->prepare('DELETE FROM projects WHERE id = ?');
        $stmt->execute([$delId]);
        $msg = 'Project permanently removed from catalogue.';
    }
}

// Category filter
$catFilter = trim($_GET['category'] ?? '');
if ($catFilter) {
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE category = ? ORDER BY featured DESC, id DESC');
    $stmt->execute([$catFilter]);
    $projects = $stmt->fetchAll();
} else {
    $projects = $pdo->query('SELECT * FROM projects ORDER BY featured DESC, id DESC')->fetchAll();
}

$categories = ['Residential', 'Commercial', 'Plots', 'Villa', 'Other'];

$pageTitle = 'Manage Projects';
$activeNav = 'projects';
require_once __DIR__ . '/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="font-size: 1.75rem; color: #0f172a;">Portfolio Management</h2>
        <p class="text-secondary mb-0">Add, update, feature, or publish luxury builder floors & commercial real estate.</p>
    </div>
    <a href="project-form.php" class="btn-adm-gold">
        <i class="fa-solid fa-plus"></i> Add New Project
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="fa-solid fa-circle-check fs-5"></i>
        <div>Project specifications and media successfully saved!</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($msg): ?>
    <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="fa-solid fa-trash-can fs-5"></i>
        <div><?= e($msg) ?></div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Category Filter Pills Bar -->
<div class="d-flex flex-wrap align-items-center gap-2 mb-4">
    <a href="projects.php" class="btn btn-sm <?= empty($catFilter) ? 'btn-dark' : 'btn-outline-secondary' ?> rounded-pill px-3 py-2 fw-semibold">
        All Categories (<?= count($projects) ?>)
    </a>
    <?php foreach ($categories as $c): ?>
        <a href="projects.php?category=<?= urlencode($c) ?>" class="btn btn-sm <?= $catFilter === $c ? 'btn-dark' : 'btn-outline-secondary' ?> rounded-pill px-3 py-2 fw-semibold">
            <?= $c ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Projects Table -->
<div class="admin-table-wrap">
    <div class="table-responsive">
        <table class="table admin-table align-middle">
            <thead>
                <tr>
                    <th style="width: 80px;">Media</th>
                    <th>Project Details</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Visibility</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $p): ?>
                    <tr>
                        <td>
                            <img src="<?= e(project_image($p['image'])) ?>" alt="<?= e($p['title']) ?>" class="project-thumb-preview" onerror="this.src='../uploads/projects/adore-legend-2.jpg'">
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <strong class="fs-6 text-dark"><?= e($p['title']) ?></strong>
                                <?php if ($p['featured']): ?>
                                    <span class="badge bg-warning text-dark px-2 py-0 fw-bold" style="font-size: 0.68rem;" title="Featured on Homepage">
                                        <i class="fa-solid fa-star"></i> FEATURED
                                    </span>
                                <?php endif; ?>
                            </div>
                            <span class="text-secondary small fw-semibold">
                                <?= e($p['price'] ?: 'Price on Request') ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 py-1 rounded-pill">
                                <?= e($p['category']) ?>
                            </span>
                        </td>
                        <td>
                            <small class="text-secondary">
                                <i class="fa-solid fa-location-dot text-danger me-1"></i> <?= e($p['location']) ?>
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">
                                <?= e($p['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($p['is_active']): ?>
                                <span class="status-pill status-pill-active">
                                    <i class="fa-solid fa-circle-check"></i> Published
                                </span>
                            <?php else: ?>
                                <span class="status-pill status-pill-inactive">
                                    <i class="fa-solid fa-eye-slash"></i> Hidden
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="../pages/project.php?slug=<?= urlencode($p['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill me-1" title="View Public Page">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                            <a href="project-form.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-adm-outline rounded-pill me-1" title="Edit Project">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete <?= e(addslashes($p['title'])) ?>?')">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <input type="hidden" name="delete_project" value="1">
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" title="Delete Project">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($projects)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-secondary">
                            <i class="fa-regular fa-building fs-1 d-block mb-3 text-muted"></i>
                            No projects found matching criteria.
                            <div class="mt-3">
                                <a href="project-form.php" class="btn-adm-gold btn-sm">Create First Project</a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>