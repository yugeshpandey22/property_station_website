<?php
require __DIR__ . '/../config.php';

$where = ['is_active=1'];
$params = [];

if (!empty($_GET['category'])) {
    $where[] = 'category = ?';
    $params[] = $_GET['category'];
}
if (!empty($_GET['location'])) {
    $where[] = 'location LIKE ?';
    $params[] = '%' . trim($_GET['location']) . '%';
}
if (!empty($_GET['q'])) {
    $where[] = '(title LIKE ? OR description LIKE ? OR location LIKE ?)';
    $q = '%' . trim($_GET['q']) . '%';
    array_push($params, $q, $q, $q);
}

$sql = 'SELECT * FROM projects WHERE ' . implode(' AND ', $where) . ' ORDER BY featured DESC, id ASC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$projects = $stmt->fetchAll();

$allProjects = $pdo->query("SELECT id, title, slug FROM projects WHERE is_active=1 ORDER BY title ASC")->fetchAll();
$activeCategory = $_GET['category'] ?? '';

$enquiryMsg = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && (isset($_POST['modal_enquiry']) || isset($_POST['enquiry_submit']) || !empty($_POST['name']))) {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $projectId = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;

    if ($name && $phone) {
        $stmt = $pdo->prepare('INSERT INTO enquiries (project_id, name, phone, email, budget, message) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $projectId,
            $name,
            $phone,
            filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null,
            null,
            $message ?: null
        ]);
        $enquiryMsg = 'Thank you! Your enquiry has been received. Our senior property advisor will reach out shortly.';
    }
}
$pageTitle = 'Curated Projects & Builder Floors | Property Station';
$pageDescription = 'Explore luxury builder floors, residential apartments, and commercial projects across Faridabad and Delhi NCR with Property Station.';
$activePage = 'projects';
require_once __DIR__ . '/../includes/header.php';
?>

    <!-- Page Header Banner -->
    <section class="py-5" style="background: linear-gradient(135deg, #0b132b 0%, #0f172a 100%); color: #fff;">
        <div class="container py-4 text-center">
            <span class="micro-badge-tag">EXCLUSIVE INVENTORY</span>
            <h1 class="display-5 fw-bold font-heading text-white">Curated Real Estate <span>Collection</span></h1>
            <div class="gold-line-center"></div>
            <p class="text-light mx-auto" style="max-width: 650px;">Browse our portfolio of luxury builder floors, commercial landmarks, and independent plotted developments across Delhi NCR.</p>

            <!-- Search Filter Form -->
            <form method="GET" class="mt-4 p-3 rounded-4 mx-auto" style="max-width: 950px; background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 103, 2, 0.3);">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="q" value="<?= e($_GET['q'] ?? '') ?>" class="form-control form-control-lg bg-dark text-white border-secondary" placeholder="Search project name, keyword...">
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select form-select-lg bg-dark text-white border-secondary">
                            <option value="">All Categories</option>
                            <?php foreach (['Residential', 'Commercial', 'Plots', 'Villa'] as $cat): ?>
                                <option value="<?= $cat ?>" <?= $activeCategory === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="location" value="<?= e($_GET['location'] ?? '') ?>" class="form-control form-control-lg bg-dark text-white border-secondary" placeholder="Location">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn-luxury w-100 justify-content-center py-3">
                            <i class="fa-solid fa-magnifying-glass"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Category Pills -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                <a href="projects.php" class="btn btn-sm <?= empty($activeCategory) ? 'btn-luxury' : 'btn-outline-light rounded-pill' ?>">All (<?= count($allProjects) ?>)</a>
                <a href="projects.php?category=Residential" class="btn btn-sm <?= $activeCategory === 'Residential' ? 'btn-luxury' : 'btn-outline-light rounded-pill' ?>">Residential</a>
                <a href="projects.php?category=Commercial" class="btn btn-sm <?= $activeCategory === 'Commercial' ? 'btn-luxury' : 'btn-outline-light rounded-pill' ?>">Commercial</a>
                <a href="projects.php?category=Plots" class="btn btn-sm <?= $activeCategory === 'Plots' ? 'btn-luxury' : 'btn-outline-light rounded-pill' ?>">Plots</a>
                <a href="projects.php?category=Villa" class="btn btn-sm <?= $activeCategory === 'Villa' ? 'btn-luxury' : 'btn-outline-light rounded-pill' ?>">Villa / Builder Floors</a>
            </div>
        </div>
    </section>

    <!-- Projects Grid Section -->
    <section class="section-pad bg-light">
        <div class="container">
            <?php if ($enquiryMsg): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> <?= e($enquiryMsg) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 mb-0 fw-bold"><?= count($projects) ?> Project<?= count($projects) !== 1 ? 's' : '' ?> Available</h2>
                <a href="../index.php" class="text-decoration-none fw-semibold text-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Back to Home</a>
            </div>

            <div class="row g-4">
                <?php foreach ($projects as $p): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="project-card-luxury">
                            <div class="project-card-thumb">
                                <span class="card-crown-badge"><i class="fa-solid fa-crown"></i> PREMIUM RESIDENCE</span>
                                <span class="card-status-pill"><?= e($p['status']) ?></span>
                                <img src="<?= e(project_image($p['image'])) ?>" alt="<?= e($p['title']) ?>" onerror="this.src='https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80'">
                            </div>
                            <div class="project-card-body">
                                <span class="card-cat-tag"><?= e($p['category']) ?></span>
                                <h3 class="project-card-title">
                                    <a href="project.php?slug=<?= urlencode($p['slug']) ?>"><?= e($p['title']) ?></a>
                                </h3>
                                <p class="card-location-text">
                                    <i class="fa-solid fa-location-dot"></i> <?= e($p['location']) ?>
                                </p>
                                <p class="card-desc-snippet"><?= e($p['description']) ?></p>
                                <div class="project-card-footer">
                                    <div class="card-price-value">
                                        <small>Price</small>
                                        <?= e($p['price'] ?: 'Price on Request') ?>
                                    </div>
                                    <a href="project.php?slug=<?= urlencode($p['slug']) ?>" class="btn-skew-explore">
                                        View Details <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (!$projects): ?>
                <div class="text-center py-5 my-5">
                    <i class="fa-solid fa-building-circle-xmark text-muted fs-1 mb-3"></i>
                    <h3 class="fw-bold">No Projects Found</h3>
                    <p class="text-muted">No projects matched your search criteria. Try selecting another category or clear filters.</p>
                    <a href="projects.php" class="btn-luxury mt-2">View All Projects</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Shared Luxury Footer Component -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>