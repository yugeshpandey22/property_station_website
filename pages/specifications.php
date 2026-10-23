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
        $msg = 'Thank you! Your specification query has been submitted. Our technical advisor will connect with you.';
    }
}

// Fetch active projects for navbar dropdown & modals
$allProjects = $pdo->query("SELECT id, title, slug, location, price, category FROM projects WHERE is_active = 1 ORDER BY featured DESC, id DESC")->fetchAll();
$pageTitle = 'Quality Specifications & Architecture | Property Station';
$pageDescription = 'Explore luxury construction specifications of Property Station builder floors in Faridabad & Delhi NCR. Earthquake-resistant RCC, Italian marble, and branded fittings.';
$activePage = 'specifications';
require_once __DIR__ . '/../includes/header.php';
?>

    <!-- Page Hero Banner -->
    <section class="about-page-hero">
        <div class="container text-center position-relative">
            <div class="breadcrumb-gold">
                <a href="../index.php"><i class="fa-solid fa-house"></i> Home</a>
                <i class="fa-solid fa-chevron-right fs-xs text-secondary"></i>
                <span>Technical Specifications</span>
            </div>
            <h1 class="display-4 fw-bold font-heading text-white mb-3">
                Construction Excellence & <span class="gold-text">Specifications</span>
            </h1>
            <div class="gold-line-center"></div>
            <p class="text-light mx-auto fs-5 mb-0" style="max-width: 780px; font-weight: 300; line-height: 1.8;">
                Engineered for generations. Uncompromising structural strength, certified seismic resistance, and European luxury fittings in every Property Station home.
            </p>
        </div>
    </section>

    <?php if ($msg): ?>
        <div class="container mt-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> <?= e($msg) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Construction Philosophy Section -->
    <section class="section-pad bg-white">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-lg-2">
                    <div class="spec-img-wrapper" style="border-radius: 20px; overflow: hidden; border: 2px solid rgba(255, 103, 2, 0.4); box-shadow: 0 20px 50px rgba(15, 23, 42, 0.15);">
                        <img src="../uploads/projects/founder-luxury.webp" alt="Continuous Discussion & Improvement" class="img-fluid w-100" style="height: 500px; object-fit: cover;" onerror="this.src='../uploads/projects/adore-legend-2.jpg'">
                        <div class="spec-overlay-tag">
                            <small>DAILY ON-SITE AUDITS</small>
                            <strong>Refining Architectural Excellence</strong>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 order-lg-1">
                    <span class="micro-badge-tag">OUR CONSTRUCTION PHILOSOPHY</span>
                    <h2 class="section-title-italic">Constant Discussion & <br><span>Continuous Improvement</span></h2>
                    <div class="gold-line-center ms-0 mb-4"></div>
                    <p class="about-p-text mb-4">Structural superiority is never accidental at Property Station—it is the direct outcome of daily on-site coordination between senior structural engineers, licensed architects, and third-party quality testing laboratories.</p>

                    <ul class="founder-pillars-list">
                        <li>
                            <span class="pillar-bullet-icon"><i class="fa-solid fa-users-gear"></i></span>
                            <div class="pillar-text-content">
                                <strong>Daily Engineering Alignments</strong>
                                <p>Rigorous site reviews ensuring 100% precision between 3D architectural blueprints and physical RCC execution.</p>
                            </div>
                        </li>
                        <li>
                            <span class="pillar-bullet-icon"><i class="fa-solid fa-layer-group"></i></span>
                            <div class="pillar-text-content">
                                <strong>Premium Material Testing</strong>
                                <p>Every batch of cement, TMT rebars, electrical conduits, and anti-skid vitrified tiles undergoes independent lab checks.</p>
                            </div>
                        </li>
                        <li>
                            <span class="pillar-bullet-icon"><i class="fa-solid fa-clipboard-check"></i></span>
                            <div class="pillar-text-content">
                                <strong>Multi-Stage Quality Audits</strong>
                                <p>Mandatory pre-casting slab inspections, 72-hour waterproofing pond testing, and hydrostatic plumbing tests.</p>
                            </div>
                        </li>
                    </ul>

                    <button type="button" class="btn-luxury mt-3" data-bs-toggle="modal" data-bs-target="#enquireModal">
                        Request Detailed Specification Sheet <i class="fa-solid fa-file-arrow-down ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Comprehensive Specification Breakdown Cards -->
    <section class="section-pad bg-light">
        <div class="container">
            <div class="section-heading-center text-center mb-5">
                <span class="micro-badge-tag">ENGINEERED STANDARDS</span>
                <h2 class="section-title-italic">Comprehensive <br><span>Specification Matrix</span></h2>
                <div class="gold-line-center"></div>
                <p class="section-subtext">Every material and fixture selected for longevity, aesthetic appeal, and daily comfort.</p>
            </div>

            <div class="row g-4">
                <!-- Spec 1: Structure -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fa-solid fa-cubes-stacked"></i>
                        </div>
                        <h4>Structure & Masonry</h4>
                        <ul class="list-unstyled text-secondary small" style="line-height: 2;">
                            <li><i class="fa-solid fa-check text-warning me-2"></i> RCC framed structure (Seismic Zone IV compliant)</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Tata / Jindal Fe 550D TMT corrosion-resistant rebars</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Ultratech / Ambuja Grade-43 & 53 cement</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Anti-termite subterranean soil treatment</li>
                        </ul>
                    </div>
                </div>

                <!-- Spec 2: Flooring & Paint -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fa-solid fa-gem"></i>
                        </div>
                        <h4>Flooring & Wall Finishes</h4>
                        <ul class="list-unstyled text-secondary small" style="line-height: 2;">
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Book-matched imported Italian Marble in living & dining</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Premium wooden laminate flooring in Master Bedroom</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Anti-skid vitrified designer tiles on balconies</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Asian Paints Royale luxury emulsion with POP punning</li>
                        </ul>
                    </div>
                </div>

                <!-- Spec 3: Doors & Windows -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fa-solid fa-door-open"></i>
                        </div>
                        <h4>Doors, Windows & Balconies</h4>
                        <ul class="list-unstyled text-secondary small" style="line-height: 2;">
                            <li><i class="fa-solid fa-check text-warning me-2"></i> 8-feet high seasoned teak wood designer main door</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Yale / Godrej digital biometric smart door locks</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Soundproof double-glazed UPVC / heavy aluminium windows</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> 12mm toughened glass balcony railings with SS brackets</li>
                        </ul>
                    </div>
                </div>

                <!-- Spec 4: Modular Kitchen -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fa-solid fa-kitchen-set"></i>
                        </div>
                        <h4>Modular Kitchen & Utility</h4>
                        <ul class="list-unstyled text-secondary small" style="line-height: 2;">
                            <li><i class="fa-solid fa-check text-warning me-2"></i> German soft-close tandem drawers (Hettich / Hafele)</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Scratch & heat-resistant imported Quartz countertops</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Pre-installed auto-clean chimney & stainless steel sink</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Dedicated utility balcony with washing machine points</li>
                        </ul>
                    </div>
                </div>

                <!-- Spec 5: Bathrooms -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fa-solid fa-shower"></i>
                        </div>
                        <h4>Sanitary & Bath Fittings</h4>
                        <ul class="list-unstyled text-secondary small" style="line-height: 2;">
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Wall-hung EWCs with concealed cisterns (Kohler / Grohe)</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Toughened glass shower partition for wet & dry zones</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Multi-flow rain showerheads and single lever diverters</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Astral / Supreme CPVC pipes with pressure testing</li>
                        </ul>
                    </div>
                </div>

                <!-- Spec 6: Electrical & Automation -->
                <div class="col-md-6 col-lg-4">
                    <div class="pillar-card">
                        <div class="pillar-icon-box">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <h4>Electrical & Automation</h4>
                        <ul class="list-unstyled text-secondary small" style="line-height: 2;">
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Concealed fire-resistant copper wiring (Polycab / Havells)</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Modular smart touch switches (Schneider / Legrand)</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> Pre-laid VRV / Split AC copper piping in all rooms</li>
                            <li><i class="fa-solid fa-check text-warning me-2"></i> High-speed optical fiber FTTH internet & DTH cabling</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quality Assurance Checklist Strip -->
    <section class="section-pad" style="background: #080d1a; color: #fff;">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <span class="micro-badge-tag">GUARANTEED INTEGRITY</span>
                    <h2 class="section-title-italic text-white mb-3">Multi-Stage Quality <br><span>Assurance Protocol</span></h2>
                    <div class="gold-line-center ms-0 mb-4"></div>
                    <p class="text-secondary" style="line-height: 1.8;">
                        Before any buyer floor is presented to our clients, it must pass a 45-point technical inspection certified by our lead structural auditor. We leave no stone unturned in ensuring your family's safety and peace of mind.
                    </p>
                    <a href="contact.php" class="btn-luxury mt-2">Book a Technical Site Tour</a>
                </div>

                <div class="col-lg-7">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(255, 103, 2, 0.25);">
                                <i class="fa-solid fa-vial-circle-check text-warning fs-4 mb-2"></i>
                                <h6 class="text-white fw-bold mb-1">Concrete Cube Testing</h6>
                                <p class="text-secondary small mb-0">Compressive load tests at 7, 14, and 28 days for M25/M30 grade concrete.</p>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(255, 103, 2, 0.25);">
                                <i class="fa-solid fa-droplet-slash text-warning fs-4 mb-2"></i>
                                <h6 class="text-white fw-bold mb-1">Waterproofing Pond Test</h6>
                                <p class="text-secondary small mb-0">72-hour continuous water retention test on all terraces and wet areas.</p>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(255, 103, 2, 0.25);">
                                <i class="fa-solid fa-gauge-high text-warning fs-4 mb-2"></i>
                                <h6 class="text-white fw-bold mb-1">Hydrostatic Pressure Test</h6>
                                <p class="text-secondary small mb-0">10 kg/cm² pressure test across internal and external concealed plumbing.</p>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(255, 103, 2, 0.25);">
                                <i class="fa-solid fa-shield-virus text-warning fs-4 mb-2"></i>
                                <h6 class="text-white fw-bold mb-1">Thermal & Sound Insulation</h6>
                                <p class="text-secondary small mb-0">Double-glazed insulated UPVC glass units dampening 35+ dB outdoor ambient noise.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shared Luxury Footer Component -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
