<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enquiry_submit'])) {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $budget = trim($_POST['budget'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $projectId = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;

    if ($name && $phone) {
        $stmt = $pdo->prepare('INSERT INTO enquiries (project_id, name, phone, email, budget, message) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$projectId, $name, $phone, $email, $budget, $message]);
        $msg = 'Thank you for reaching out! Our senior property advisor will contact you shortly.';
    }
}

// Fetch all active projects for navbar dropdown & modals
$allProjects = $pdo->query("SELECT id, title, slug, location, price, category FROM projects WHERE is_active = 1 ORDER BY featured DESC, id DESC")->fetchAll();
$pageTitle = 'About Us | Property Station - Ghar Ka Aakhri Station';
$pageDescription = "Learn about Property Station, Delhi NCR's premier luxury real estate advisory and builder floor developers based in Sector 15 Green Corridor, Faridabad. 25+ years of trust & excellence.";
$activePage = 'about';
require_once __DIR__ . '/../includes/header.php';
?>

    <!-- Page Hero Banner -->
    <section class="about-page-hero">
        <div class="container text-center position-relative">
            <div class="breadcrumb-gold">
                <a href="../index.php"><i class="fa-solid fa-house"></i> Home</a>
                <i class="fa-solid fa-chevron-right fs-xs text-secondary"></i>
                <span>About Property Station</span>
            </div>
            <h1 class="display-4 fw-bold font-heading text-white mb-3">
                A Legacy of Trust & <span class="gold-text">Architectural Brilliance</span>
            </h1>
            <div class="gold-line-center"></div>
            <p class="text-light mx-auto fs-5 mb-0" style="max-width: 780px; font-weight: 300; line-height: 1.8;">
                Redefining modern living through meticulously engineered builder floors, legally verified land assets, and prime commercial investments across Faridabad & Delhi NCR.
            </p>
        </div>
    </section>

    <!-- Success Message Alert -->
    <?php if ($msg): ?>
        <div class="container mt-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> <?= e($msg) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Who We Are / Heritage Section -->
    <section class="section-pad bg-white">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="about-visual-column">
                        <div class="about-img-box position-relative" style="border-radius: 20px; overflow: hidden; border: 2px solid rgba(255, 103, 2, 0.4); box-shadow: 0 20px 45px rgba(15, 23, 42, 0.15);">
                            <img src="../uploads/projects/office-facade.jpg" alt="Property Station Architecture & Office" class="img-fluid w-100" style="height: 520px; object-fit: cover;" onerror="this.src='../uploads/projects/about-img.png'">
                        </div>
                        <div class="about-floating-card">
                            <span class="about-card-number">25+</span>
                            <span class="about-card-text">Years of Trust & Excellence</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="about-content-column">
                        <span class="micro-badge-tag">OUR ORIGIN & VALUES</span>
                        <h2 class="section-title-italic mb-3">Welcome to <br><span>Property Station</span></h2>
                        <div class="gold-line-center ms-0 mb-4"></div>

                        <p class="about-lead-quote mb-4">
                            "Ghar Ka Aakhri Station — Where your search for the ideal home terminates with unquestioned trust, legal perfection, and architectural majesty."
                        </p>

                        <p class="text-secondary" style="line-height: 1.85; font-size: 0.98rem;">
                            Headquartered in the prestigious <strong>Sector 15 Green Corridor, Faridabad</strong>, Property Station stands at the forefront of Delhi NCR’s luxury residential revolution. We are more than real estate consultants; we are passionate curators of high-standard living spaces, uniting premier builders with discerning homeowners.
                        </p>

                        <p class="text-secondary" style="line-height: 1.85; font-size: 0.98rem;">
                            For over two and a half decades, we have bridged the gap between raw construction and refined elegance. Every builder floor, penthouse, and commercial unit under our banner undergoes rigorous architectural scrutiny, structural load auditing, and uncompromised title deed due diligence.
                        </p>

                        <div class="row g-3 mt-2 mb-4">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-warning fs-5"></i>
                                    <span class="fw-semibold text-dark">100% RERA Approved</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-warning fs-5"></i>
                                    <span class="fw-semibold text-dark">Direct Developer Handshake</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-warning fs-5"></i>
                                    <span class="fw-semibold text-dark">Italian Marble & Automation</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-warning fs-5"></i>
                                    <span class="fw-semibold text-dark">Transparent Registry Support</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <a href="projects.php" class="btn-luxury">Explore Properties <i class="fa-solid fa-arrow-right ms-1"></i></a>
                            <a href="tel:<?= e(SITE_PHONE) ?>" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-semibold">
                                <i class="fa-solid fa-phone me-1"></i> Call Advisor
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Metrics Counter Strip -->
    <section class="trust-metrics-section">
        <div class="container">
            <div class="row g-3">
                <div class="col-6 col-lg-3">
                    <div class="metric-item">
                        <div class="metric-icon-box"><i class="fa-solid fa-medal"></i></div>
                        <div class="metric-text">
                            <h4>25+ Years Legacy</h4>
                            <p>Proven excellence in Delhi NCR</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="metric-item">
                        <div class="metric-icon-box"><i class="fa-solid fa-users"></i></div>
                        <div class="metric-text">
                            <h4>500+ Families</h4>
                            <p>Settled in dream homes</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="metric-item">
                        <div class="metric-icon-box"><i class="fa-solid fa-building-circle-check"></i></div>
                        <div class="metric-text">
                            <h4>50+ Projects</h4>
                            <p>Delivered on time</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="metric-item">
                        <div class="metric-icon-box"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                        <div class="metric-text">
                            <h4>0% Brokerage</h4>
                            <p>Direct developer pricing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission Dual Cards -->
    <section class="section-pad" style="background: #080d1a;">
        <div class="container">
            <div class="section-heading-center text-center mb-5">
                <span class="micro-badge-tag">OUR PURPOSE</span>
                <h2 class="section-title-italic text-white">Vision & <span>Core Mission</span></h2>
                <div class="gold-line-center"></div>
                <p class="section-subtext text-secondary">Guiding every step of our enterprise with ethical principles and customer-first commitment.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="vision-card">
                        <span class="vision-badge"><i class="fa-solid fa-eye me-1"></i> OUR VISION</span>
                        <h3>To Be Delhi NCR's Most Cherished Real Estate Destination</h3>
                        <p>
                            We envision an ecosystem where property purchasing is entirely devoid of hidden costs, deceptive promises, and tedious paperwork. Our vision is to empower every Indian family to own an architectural masterpiece that appreciates in commercial value while providing lifetime security and peace of mind.
                        </p>
                        <div class="mt-4 pt-2 d-flex align-items-center gap-3 text-warning">
                            <i class="fa-solid fa-award fs-3"></i>
                            <span class="small fw-semibold text-light">Uncompromising standards in every square foot delivered.</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="vision-card">
                        <span class="vision-badge"><i class="fa-solid fa-bullseye me-1"></i> OUR MISSION</span>
                        <h3>Delivering Architectural Masterworks with 100% Transparency</h3>
                        <p>
                            Our daily mission is to craft and represent benchmark independent builder floors, commercial landmarks, and plotted enclaves. We curate only projects with verified RERA documentation, earthquake-resistant RCC frameworks, bespoke European elevations, and direct developer pricing privileges for our clients.
                        </p>
                        <div class="mt-4 pt-2 d-flex align-items-center gap-3 text-warning">
                            <i class="fa-solid fa-handshake-angle fs-3"></i>
                            <span class="small fw-semibold text-light">End-to-end guidance from first site visit to keys handover.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6 Pillars of Excellence -->
    <section class="section-pad bg-light">
        <div class="container">
            <div class="section-heading-center text-center mb-5">
                <span class="micro-badge-tag">WHY PROPERTY STATION</span>
                <h2 class="section-title-italic">Six Pillars of <br><span>Excellence & Distinction</span></h2>
                <div class="gold-line-center"></div>
                <p class="section-subtext">What separates Property Station from ordinary real estate agents.</p>
            </div>

            <div class="row g-4">
                <!-- Pillar 1 -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fa-solid fa-compass-drafting"></i>
                        </div>
                        <h4>Bespoke European Elevations</h4>
                        <p>Each residence features modern classical facades, high-grade textured paint, double-height grand entrance lobbies, and weather-resistant balconies.</p>
                    </div>
                </div>

                <!-- Pillar 2 -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h4>100% Legal Title Due Diligence</h4>
                        <p>Every project undergoes forensic legal checks by our dedicated counsel to ensure DTCP/HUDA sanction approvals and clean, zero-dispute title deeds.</p>
                    </div>
                </div>

                <!-- Pillar 3 -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fa-solid fa-gem"></i>
                        </div>
                        <h4>Ultra-Luxury Material Palette</h4>
                        <p>From book-matched Italian marble to soundproof double-glazed UPVC windows and German hardware fittings, we never cut corners on quality.</p>
                    </div>
                </div>

                <!-- Pillar 4 -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <h4>Strategic NCR Hotspots</h4>
                        <p>Direct presence in Faridabad's prime Sectors 14, 15, 80, 84, 86, 119, Gurugram's Golf Course Extension, and South Delhi's boutique enclaves.</p>
                    </div>
                </div>

                <!-- Pillar 5 -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fa-solid fa-hand-holding-hand"></i>
                        </div>
                        <h4>Complete End-to-End Care</h4>
                        <p>From personalized layout selection, home loan approvals with major nationalized banks, to smooth registry and key handover ceremony.</p>
                    </div>
                </div>

                <!-- Pillar 6 -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                        <h4>Direct Developer Handshake</h4>
                        <p>Transparent pricing directly synchronized with top developers (Adore, BPTP, etc.), guaranteeing best market rates with zero brokerage on selected portfolios.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Spotlight Feature -->
    <section class="section-pad bg-white">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <div class="leader-portrait-wrap">
                        <img src="../uploads/projects/founder-luxury.webp" alt="Property Station Leadership" loading="lazy" decoding="async" onerror="this.src='../uploads/projects/agent_profile.jpg'">
                        <div class="leader-info-badge">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="mb-0 fw-bold text-white">Leadership & Advisory Desk</h5>
                                    <span class="small text-warning">Sector 15 Green Corridor, Faridabad</span>
                                </div>
                                <i class="fa-solid fa-crown text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <span class="micro-badge-tag">EXECUTIVE DESK</span>
                    <h2 class="section-title-italic mb-3">A Message From The <br><span>Leadership Desk</span></h2>
                    <div class="gold-line-center ms-0 mb-4"></div>

                    <div class="p-4 rounded-4 mb-4" style="background: #f8fafc; border-left: 4px solid var(--gold);">
                        <p class="fs-5 text-dark fst-italic mb-0" style="line-height: 1.8;">
                            "In real estate, a building is not just brick and mortar — it is a sanctuary for memories, a symbol of your family's hard work, and a cornerstone of financial heritage. At Property Station, we honor that sacred trust with complete transparency, verified documentation, and personalized attention."
                        </p>
                    </div>

                    <p class="text-secondary" style="line-height: 1.85; font-size: 0.96rem;">
                        Over the past 25 years, Delhi NCR's skyline has transformed dramatically. While many brokers chase quick sales, Property Station was built on the rare foundation of permanent client relationships. We personally inspect each builder floor, verify structural tests, and stand with our buyers at the registrar office.
                    </p>

                    <p class="text-secondary" style="line-height: 1.85; font-size: 0.96rem;">
                        Whether you are purchasing your first luxury apartment, upgrading to an independent 4 BHK builder floor with private terrace, or seeking high-rental commercial shops, you can rest assured that <em>Property Station</em> is your definitive final destination.
                    </p>

                    <div class="d-flex align-items-center gap-4 pt-2">
                        <a href="contact.php" class="btn-luxury">Schedule an Executive Meeting</a>
                        <a href="https://wa.me/<?= e(SITE_WHATSAPP) ?>" target="_blank" class="text-success fw-bold text-decoration-none">
                            <i class="fa-brands fa-whatsapp fs-5 me-1"></i> WhatsApp Us Directly
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our 4-Step Seamless Journey -->
    <section class="section-pad" style="background: #f1f5f9;">
        <div class="container">
            <div class="section-heading-center text-center mb-5">
                <span class="micro-badge-tag">HOW WE WORK</span>
                <h2 class="section-title-italic">Your 4-Step Journey to <br><span>Ownership Perfection</span></h2>
                <div class="gold-line-center"></div>
                <p class="section-subtext">A streamlined, stress-free experience from your first enquiry to keys handover.</p>
            </div>

            <div class="row g-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="process-step-item">
                        <div class="process-step-num">01</div>
                        <h5>Private Consultation</h5>
                        <p>We listen carefully to your family's space needs, preferred sectors, budget range, and timeline expectations.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="process-step-item">
                        <div class="process-step-num">02</div>
                        <h5>Curated Site Tours</h5>
                        <p>Exclusive chauffeured visits to fully-finished builder floors, ongoing developments, and model apartments.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="process-step-item">
                        <div class="process-step-num">03</div>
                        <h5>Legal & Loan Audit</h5>
                        <p>Transparent review of title deeds, sanctions, RERA compliance, and streamlined home loan approvals.</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="process-step-item">
                        <div class="process-step-num">04</div>
                        <h5>Registry & Keys</h5>
                        <p>Seamless execution at the registrar desk, prompt handover of keys, warranties, and after-sales care.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Client Testimonials & Trust Reviews -->
    <section class="section-pad bg-white">
        <div class="container">
            <div class="section-heading-center text-center mb-5">
                <span class="micro-badge-tag">CLIENT TESTIMONIALS</span>
                <h2 class="section-title-italic">What Our Homeowners <br><span>Say About Us</span></h2>
                <div class="gold-line-center"></div>
                <p class="section-subtext">Real experiences from proud residents across Delhi NCR.</p>
            </div>

            <div class="row g-4">
                <!-- Review 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="client-review-card">
                        <div>
                            <div class="review-stars">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <p class="review-text">
                                "Property Station helped us acquire our dream 3 BHK builder floor in Sector 14, Faridabad. The finishing of the Italian marble and European elevation is outstanding. The documentation process was 100% smooth!"
                            </p>
                        </div>
                        <div class="review-author">
                            <div class="author-avatar">RG</div>
                            <div class="author-info">
                                <h6>Rajesh Gupta</h6>
                                <span>Homeowner, Sector 14 Faridabad</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="client-review-card">
                        <div>
                            <div class="review-stars">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <p class="review-text">
                                "Finding genuine, legally clear builder floors in Delhi NCR is tough. The team at Property Station Sector 15 gave us direct developer deals without any broker hassle. Truly 'Ghar Ka Aakhri Station'!"
                            </p>
                        </div>
                        <div class="review-author">
                            <div class="author-avatar">SK</div>
                            <div class="author-info">
                                <h6>Col. Sanjay Kapoor</h6>
                                <span>Investor, Adore Legend 2</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Review 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="client-review-card">
                        <div>
                            <div class="review-stars">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <p class="review-text">
                                "Exceptional transparency and structural quality. We got our registry done right on time with all bank sanctions cleared. Their senior advisor was with us at every stage. Highly recommended!"
                            </p>
                        </div>
                        <div class="review-author">
                            <div class="author-avatar">AS</div>
                            <div class="author-info">
                                <h6>Ananya Sharma</h6>
                                <span>Resident, Sector 84 Faridabad</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shared Luxury Footer Component -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
