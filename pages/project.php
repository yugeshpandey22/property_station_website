<?php
require __DIR__ . '/../config.php';

$slug = trim($_GET['slug'] ?? '');
$stmt = $pdo->prepare('SELECT * FROM projects WHERE slug = ? AND is_active = 1 LIMIT 1');
$stmt->execute([$slug]);
$p = $stmt->fetch();

if (!$p) {
    http_response_code(404);
    $pageTitle = 'Project Not Found | Property Station';
} else {
    $pageTitle = $p['title'] . ' | Property Station';
}

$sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $p) {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $budget = trim($_POST['budget'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || !preg_match('/^[0-9+() .-]{7,20}$/', $phone)) {
        $error = 'Please enter your name and a valid phone number.';
    } else {
        $s = $pdo->prepare('INSERT INTO enquiries (project_id, name, phone, email, budget, message) VALUES (?, ?, ?, ?, ?, ?)');
        $s->execute([
            $p['id'],
            $name,
            $phone,
            filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null,
            $budget ?: null,
            $message ?: null
        ]);
        $sent = true;
    }
}

$allProjects = $pdo->query("SELECT id, title, slug FROM projects WHERE is_active=1 ORDER BY title ASC")->fetchAll();
$pageDescription = substr(strip_tags($p['description'] ?? 'Property Station Luxury Project'), 0, 160);
$activePage = 'projects';
require_once __DIR__ . '/../includes/header.php';
?>

    <?php if (!$p): ?>
        <section class="py-5 text-center my-5">
            <div class="container py-5">
                <i class="fa-solid fa-building-circle-xmark fs-1 text-muted mb-3"></i>
                <h1 class="display-6 fw-bold">Project Not Found</h1>
                <p class="text-muted">The project you are looking for does not exist or has been archived.</p>
                <a href="projects.php" class="btn-luxury mt-3">Browse Available Projects</a>
            </div>
        </section>
    <?php else: ?>
        <!-- Project Detail Header -->
        <header class="py-5 bg-white border-bottom shadow-sm">
            <div class="container">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb small">
                        <li class="breadcrumb-item"><a href="../index.php" class="text-decoration-none text-muted">Home</a></li>
                        <li class="breadcrumb-item"><a href="projects.php" class="text-decoration-none text-muted">Projects</a></li>
                        <li class="breadcrumb-item active text-dark fw-bold" aria-current="page"><?= e($p['title']) ?></li>
                    </ol>
                </nav>

                <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                    <span class="badge px-3 py-2 text-uppercase fw-bold" style="background: rgba(255, 103, 2,0.15); color: #ff6702; border: 1px solid rgba(255, 103, 2,0.4);">
                        <i class="fa-solid fa-crown me-1"></i> <?= e($p['category']) ?>
                    </span>
                    <span class="badge bg-dark px-3 py-2 text-uppercase fw-semibold">
                        <?= e($p['status']) ?>
                    </span>
                    <span class="text-muted small">
                        <i class="fa-solid fa-location-dot text-danger me-1"></i> <?= e($p['location']) ?>
                    </span>
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mt-2">
                    <div>
                        <h1 class="font-heading display-6 fw-bold text-dark mb-0"><?= e($p['title']) ?></h1>
                    </div>
                    <div class="text-lg-end">
                        <div class="small text-muted text-uppercase fw-semibold">Investment Range</div>
                        <div class="fs-3 fw-bold text-dark font-heading"><?= e($p['price'] ?: 'Price on Request') ?></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Details Content -->
        <main class="py-5">
            <div class="container">
                <div class="row g-5">
                    <!-- Left: Project Visuals & Description -->
                    <div class="col-lg-8">
                        <div class="bg-white p-3 p-md-4 rounded-4 shadow-sm border mb-4">
                            <div class="position-relative overflow-hidden rounded-3 mb-4" style="max-height: 500px;">
                                <img src="<?= e(project_image($p['image'])) ?>" alt="<?= e($p['title']) ?>" class="w-100 h-100 object-fit-cover" style="max-height: 500px; display: block;" onerror="this.src='https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80'">
                            </div>

                            <h3 class="font-heading fw-bold mb-3">Project Overview</h3>
                            <p class="lead fs-6 text-muted mb-4" style="line-height: 1.8;">
                                <?= nl2br(e($p['description'] ?: 'Property Station brings you this premier residential masterpiece engineered for generational elegance, high safety benchmarks, and luxurious comfort in Delhi NCR.')) ?>
                            </p>

                            <!-- Highlights & Amenities -->
                            <?php if (!empty($p['amenities'])): ?>
                                <h3 class="font-heading fw-bold mb-3 mt-4">Key Amenities & Highlights</h3>
                                <div class="row g-3 mb-4">
                                    <?php 
                                    $amenitiesList = array_map('trim', explode(',', $p['amenities']));
                                    foreach ($amenitiesList as $am): 
                                        if (!$am) continue;
                                    ?>
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-3 border d-flex align-items-center gap-3 bg-light">
                                                <i class="fa-solid fa-circle-check text-warning fs-5"></i>
                                                <span class="fw-semibold text-dark"><?= e($am) ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Specifications Checklist -->
                            <h3 class="font-heading fw-bold mb-3 mt-4">Architectural Specifications</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold bg-light" style="width: 30%;">Structure</td>
                                            <td>Earthquake-resistant RCC frame designed under senior structural engineering supervision.</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-light">Flooring</td>
                                            <td>Premium imported Italian marble in living zones & high-gloss vitrified tiles in bedrooms.</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-light">Kitchen</td>
                                            <td>Pre-polished granite platform with dual stainless sink and branded modular storage fittings.</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold bg-light">Electrical</td>
                                            <td>ISI certified copper concealed wiring with modular switches and dedicated inverter lines.</td>
                                        </tr>
                                        <?php if (!empty($p['rera_number'])): ?>
                                        <tr>
                                            <td class="fw-bold bg-light">RERA Registration</td>
                                            <td><span class="badge bg-success-subtle text-success fw-bold px-3 py-2"><?= e($p['rera_number']) ?></span></td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Sticky Enquiry Card -->
                    <div class="col-lg-4">
                        <div class="bg-white p-4 rounded-4 shadow-sm border position-sticky" style="top: 100px;">
                            <div class="border-bottom pb-3 mb-3">
                                <span class="micro-badge-tag mb-1">DIRECT INQUIRY</span>
                                <h4 class="font-heading fw-bold mb-1">Book a Site Walkthrough</h4>
                                <p class="text-muted small mb-0">Get detailed floor plans, cost breakdown, and arrange a private site visit.</p>
                            </div>

                            <?php if ($sent): ?>
                                <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
                                    <i class="fa-solid fa-circle-check fs-5"></i>
                                    <div><strong>Request Sent!</strong> Our dedicated advisor will call you shortly.</div>
                                </div>
                            <?php elseif ($error): ?>
                                <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Your Full Name *</label>
                                    <input type="text" name="name" class="form-control py-2" placeholder="Full name" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Mobile Number *</label>
                                    <input type="tel" name="phone" class="form-control py-2" placeholder="10-digit number" required pattern="[0-9]{10}" maxlength="10">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Email Address (Optional)</label>
                                    <input type="email" name="email" class="form-control py-2" placeholder="you@domain.com">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Preferred Budget</label>
                                    <select name="budget" class="form-select py-2">
                                        <option value="">Select budget</option>
                                        <option value="Under ₹50 Lakh">Under ₹50 Lakh</option>
                                        <option value="₹50 Lakh – ₹1 Cr">₹50 Lakh – ₹1 Cr</option>
                                        <option value="₹1 Cr – ₹2 Cr">₹1 Cr – ₹2 Cr</option>
                                        <option value="₹2 Cr+">₹2 Cr & Above</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Specific Question / Notes</label>
                                    <textarea name="message" class="form-control py-2" rows="2" placeholder="e.g. 3 BHK floor plan, payment plan..."></textarea>
                                </div>

                                <button type="submit" class="btn-luxury w-100 justify-content-center py-3">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Request Brochure & Pricing
                                </button>
                            </form>

                            <hr class="my-4">

                            <div class="d-flex align-items-center gap-3">
                                <a href="tel:<?= e(SITE_PHONE) ?>" class="btn btn-outline-dark w-50 py-2 small fw-semibold">
                                    <i class="fa-solid fa-phone me-1"></i> Call Now
                                </a>
                                <a href="https://wa.me/<?= e(SITE_WHATSAPP) ?>?text=I%20am%20interested%20in%20<?= urlencode($p['title']) ?>" target="_blank" class="btn btn-success w-50 py-2 small fw-semibold">
                                    <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <?php endif; ?>

    <!-- Shared Luxury Footer Component -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>