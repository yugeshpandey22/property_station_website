<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';

$msg = '';
$err = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && (isset($_POST['contact_submit']) || isset($_POST['modal_enquiry']) || isset($_POST['enquiry_submit']) || !empty($_POST['name']))) {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $budget = trim($_POST['budget'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $projectId = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;

    if ($name && $phone) {
        $stmt = $pdo->prepare('INSERT INTO enquiries (project_id, name, phone, email, budget, message) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $projectId,
            $name,
            $phone,
            filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null,
            $budget ?: null,
            $message ?: null
        ]);
        $msg = 'Thank you! Your message has been received. Our senior property advisor will call you shortly.';
    } else {
        $err = 'Please provide both your name and phone number.';
    }
}

// Fetch active projects for dropdown
$allProjects = $pdo->query("SELECT id, title, slug, location, price, category FROM projects WHERE is_active = 1 ORDER BY featured DESC, id DESC")->fetchAll();
$pageTitle = 'Contact Us | Property Station - Sector 15 Green Corridor, Faridabad';
$pageDescription = 'Contact Property Station at Sector 15 Green Corridor, Faridabad. Call +91 80008 10016 for luxury builder floors, plots, and commercial properties in Delhi NCR.';
$activePage = 'contact';
require_once __DIR__ . '/../includes/header.php';
?>

    <!-- Page Hero Banner -->
    <section class="about-page-hero">
        <div class="container text-center position-relative">
            <div class="breadcrumb-gold">
                <a href="../index.php"><i class="fa-solid fa-house"></i> Home</a>
                <i class="fa-solid fa-chevron-right fs-xs text-secondary"></i>
                <span>Legal & Sales Office</span>
            </div>
            <h1 class="display-4 fw-bold font-heading text-white mb-3">
                Connect With Our <span class="gold-text">Advisory Desk</span>
            </h1>
            <div class="gold-line-center"></div>
            <p class="text-light mx-auto fs-5 mb-0" style="max-width: 740px; font-weight: 300; line-height: 1.8;">
                Visit our executive sales lounge in Sector 15 Green Corridor, Faridabad, or speak directly with our senior consultant.
            </p>
        </div>
    </section>

    <!-- Main Content Grid -->
    <section class="section-pad bg-light">
        <div class="container">
            <?php if ($msg): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> <?= e($msg) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($err): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= e($err) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Contact Touchpoint Cards -->
            <div class="row g-4 mb-5">
                <div class="col-md-6 col-lg-3">
                    <div class="pillar-card text-center">
                        <div class="pillar-icon-box mx-auto">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <h4>Office Address</h4>
                        <p><strong>Sector 15 Green Corridor</strong><br>Faridabad, Delhi NCR, Haryana - 121007</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="pillar-card text-center">
                        <div class="pillar-icon-box mx-auto">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <h4>Phone Desk</h4>
                        <p>
                            Direct Call Support:<br>
                            <a href="tel:<?= e(SITE_PHONE) ?>" class="text-dark fw-bold text-decoration-none"><?= e(SITE_PHONE) ?></a>
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="pillar-card text-center">
                        <div class="pillar-icon-box mx-auto">
                            <i class="fa-brands fa-whatsapp text-success"></i>
                        </div>
                        <h4>WhatsApp Desk</h4>
                        <p>
                            Instant Assistance:<br>
                            <a href="https://wa.me/<?= e(SITE_WHATSAPP) ?>" target="_blank" class="text-success fw-bold text-decoration-none">+91 80008 10016</a>
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="pillar-card text-center">
                        <div class="pillar-icon-box mx-auto">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <h4>Visiting Hours</h4>
                        <p>
                            Monday to Sunday:<br>
                            <strong>9:30 AM - 7:30 PM</strong><br>
                            <span class="text-success small fw-semibold">Open 7 Days a Week</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form + Map Section -->
            <div class="row g-5 align-items-stretch">
                <!-- Contact Form -->
                <div class="col-lg-6">
                    <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm h-100" style="border: 1px solid #e2e8f0;">
                        <span class="micro-badge-tag">SCHEDULE A PRIVATE VISIT</span>
                        <h2 class="section-title-italic mb-3">Send a Direct <span>Message</span></h2>
                        <div class="gold-line-center ms-0 mb-4"></div>
                        <p class="text-secondary small mb-4">Fill out the brief form below and our senior property consultant will reach out with customized brochures, price sheets, and floor plans.</p>

                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Your Full Name *</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Phone Number *</label>
                                    <input type="tel" name="phone" class="form-control" placeholder="Enter your phone number" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter your email address">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Investment Budget</label>
                                    <select name="budget" class="form-select">
                                        <option value="">Select Range</option>
                                        <option value="₹ 45L - ₹ 75L">₹ 45 Lakhs - ₹ 75 Lakhs</option>
                                        <option value="₹ 75L - ₹ 1.5 Cr">₹ 75 Lakhs - ₹ 1.5 Cr</option>
                                        <option value="₹ 1.5 Cr - ₹ 3 Cr">₹ 1.5 Cr - ₹ 3.0 Cr</option>
                                        <option value="₹ 3 Cr+">₹ 3.0 Cr & Above</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Interested In Project (Optional)</label>
                                    <select name="project_id" class="form-select">
                                        <option value="">General Consultation / Builder Floors</option>
                                        <?php foreach ($allProjects as $ap): ?>
                                            <option value="<?= $ap['id'] ?>"><?= e($ap['title']) ?> — <?= e($ap['location']) ?> (<?= e($ap['price']) ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Message / Specific Requirements</label>
                                    <textarea name="message" class="form-control" rows="4" placeholder="Mention preferred sectors, 3 BHK / 4 BHK preference, or request a weekend site tour..."></textarea>
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" name="contact_submit" class="btn-luxury w-100 justify-content-center py-3">
                                        <i class="fa-solid fa-paper-plane me-2"></i> Submit Consultation Request
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Office Location & Interactive Map -->
                <div class="col-lg-6">
                    <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between" style="border: 1px solid #e2e8f0;">
                        <div>
                            <span class="micro-badge-tag">EXECUTIVE LOUNGE</span>
                            <h2 class="section-title-italic mb-3">Our Prime <span>Location</span></h2>
                            <div class="gold-line-center ms-0 mb-4"></div>
                            
                            <p class="text-secondary mb-3">
                                Centrally positioned at <strong>Sector 15 Green Corridor, Faridabad</strong>, our legal and sales office offers valet parking, an executive client conference lounge, and comprehensive physical project models.
                            </p>

                            <div class="d-flex align-items-center gap-3 mb-4">
                                <span class="badge bg-dark px-3 py-2 text-warning"><i class="fa-solid fa-car me-1"></i> Easy Metro & Expressway Access</span>
                                <span class="badge bg-warning text-dark px-3 py-2 fw-bold"><i class="fa-solid fa-square-parking me-1"></i> Valet Parking Available</span>
                            </div>
                        </div>

                        <!-- Office Storefront Facade Photo -->
                        <div class="rounded-4 overflow-hidden shadow-sm mb-3" style="border: 1px solid rgba(255, 103, 2, 0.4); max-height: 200px;">
                            <img src="../uploads/projects/office-facade.jpg" alt="Property Station Office - Sector 15 Green Corridor" class="w-100 h-100" style="object-fit: cover;">
                        </div>

                        <!-- Embedded Google Map -->
                        <div class="rounded-4 overflow-hidden shadow-sm" style="height: 350px; border: 1px solid #cbd5e1;">
                            <iframe 
                                src="https://maps.google.com/maps?q=Sector+15+Faridabad+Haryana+121007&t=&z=14&ie=UTF8&iwloc=&output=embed" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Property Station Location Map">
                            </iframe>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2">
                            <span class="small text-muted"><i class="fa-solid fa-shield-halved text-warning me-1"></i> Certified Legal Sales Lounge</span>
                            <a href="https://maps.google.com/?q=Sector+15+Faridabad+Haryana" target="_blank" class="small fw-bold text-dark text-decoration-none">
                                Open in Google Maps <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shared Luxury Footer Component -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
