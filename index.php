<?php
require __DIR__ . '/config.php';

// Fetch all active projects
$projects = $pdo->query("SELECT * FROM projects WHERE is_active=1 ORDER BY featured DESC, id ASC")->fetchAll();
$totalProjects = count($projects);
$categories = $pdo->query("SELECT category, COUNT(*) as total FROM projects WHERE is_active=1 GROUP BY category")->fetchAll();

$enquirySent = false;
$enquiryError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['quick_enquiry']) || isset($_POST['modal_enquiry']))) {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $budget = trim($_POST['budget'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $projectId = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;

    if ($name === '' || !preg_match('/^[0-9+() .-]{7,20}$/', $phone)) {
        $enquiryError = 'Please enter your name and a valid phone number.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO enquiries (project_id, name, phone, email, budget, message) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $projectId,
            $name,
            $phone,
            filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null,
            $budget ?: null,
            $message ?: null
        ]);
        $enquirySent = true;
    }
}
$pageTitle = 'Property Station | Ghar Ka Aakhri Station — Luxury Real Estate in Faridabad & Delhi NCR';
$pageDescription = 'Property Station — Ghar Ka Aakhri Station. Premier real estate consultancy and luxury builder floors in Faridabad, Gurugram and Delhi NCR.';
$activePage = 'home';
require_once __DIR__ . '/includes/header.php';
?>

    <!-- Hero Slider Section -->
    <section class="hero-slider-section">
        <div class="swiper hero-slider">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <div class="hero-slide-bg" style="background-image: url('uploads/projects/banner-1.webp');"></div>
                    <div class="hero-overlay-gradient"></div>
                    <div class="container hero-content-box">
                        <div class="hero-tag">
                            <span class="hero-tag-line"></span>
                            <span>GHAR KA AAKHRI STATION</span>
                        </div>
                        <h1>Discover Elite <em>Builder Floors</em> & Luxury Living.</h1>
                        <p>Architectural elegance, verified RERA projects, and transparent consultancy across prime Sectors of Faridabad and Delhi NCR.</p>
                        <div class="hero-badges-row">
                            <span><i class="fa-solid fa-crown"></i> Sector 15 Green Corridor</span>
                            <span><i class="fa-solid fa-shield-halved"></i> 100% RERA Compliant</span>
                            <span><i class="fa-solid fa-handshake"></i> Direct Developer Pricing</span>
                        </div>
                        <div>
                            <button type="button" class="btn-luxury me-3" data-bs-toggle="modal" data-bs-target="#enquireModal">Book Site Visit</button>
                            <a href="pages/projects.php" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold">Explore Projects</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="swiper-slide">
                    <div class="hero-slide-bg" style="background-image: url('uploads/projects/banner-4.webp');"></div>
                    <div class="hero-overlay-gradient"></div>
                    <div class="container hero-content-box">
                        <div class="hero-tag">
                            <span class="hero-tag-line"></span>
                            <span>LUXURY FAMILY RESIDENCES</span>
                        </div>
                        <h1>Generational Comfort in <em>Bespoke Penthouses</em>.</h1>
                        <p>Experience expansive living with floor-to-ceiling vistas, private terrace cabanas, and imported Italian marble finishes.</p>
                        <div class="hero-badges-row">
                            <span><i class="fa-solid fa-house-chimney-window"></i> High-Ceiling Layouts</span>
                            <span><i class="fa-solid fa-shield-heart"></i> Multi-tier Security</span>
                            <span><i class="fa-solid fa-sparkles"></i> Dedicated Stilt Parking</span>
                        </div>
                        <div>
                            <a href="pages/projects.php?category=Residential" class="btn-luxury me-3">Explore Residences</a>
                            <button type="button" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#enquireModal">Enquire Now</button>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="swiper-slide">
                    <div class="hero-slide-bg" style="background-image: url('uploads/projects/banner-2.webp');"></div>
                    <div class="hero-overlay-gradient"></div>
                    <div class="container hero-content-box">
                        <div class="hero-tag">
                            <span class="hero-tag-line"></span>
                            <span>PRIME NCR CONNECTIVITY</span>
                        </div>
                        <h1>Rapid Metro Hubs & <em>Commercial Boulevards</em>.</h1>
                        <p>High-yield Grade-A office spaces and prime retail avenues positioned along bustling Delhi NCR expressways and metro corridors.</p>
                        <div class="hero-badges-row">
                            <span><i class="fa-solid fa-train-subway"></i> Express Metro Corridor</span>
                            <span><i class="fa-solid fa-chart-line"></i> High Rental Yields</span>
                            <span><i class="fa-solid fa-building"></i> Commercial Retail</span>
                        </div>
                        <div>
                            <a href="pages/projects.php" class="btn-luxury me-3">View Collection</a>
                            <a href="tel:<?= e(SITE_PHONE) ?>" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold"><i class="fa-solid fa-phone me-1"></i> Call <?= e(SITE_PHONE) ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Swiper Controls -->
            <div class="swiper-pagination"></div>
            <div class="hero-slider-prev"><i class="fa-solid fa-chevron-left"></i></div>
            <div class="hero-slider-next"><i class="fa-solid fa-chevron-right"></i></div>
        </div>
    </section>


    <!-- Trust / Highlights Metrics Strip -->
    <section class="trust-metrics-section">
        <div class="container">
            <div class="row g-3">
                <div class="col-6 col-lg-3">
                    <div class="metric-item">
                        <div class="metric-icon-box"><i class="fa-solid fa-medal"></i></div>
                        <div class="metric-text">
                            <h4>25+ Years Legacy</h4>
                            <p>Proven trust & reputation</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="metric-item">
                        <div class="metric-icon-box"><i class="fa-solid fa-building-circle-check"></i></div>
                        <div class="metric-text">
                            <h4>Curated Builder Floors</h4>
                            <p>Handcrafted luxury designs</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="metric-item">
                        <div class="metric-icon-box"><i class="fa-solid fa-file-shield"></i></div>
                        <div class="metric-text">
                            <h4>RERA Registered</h4>
                            <p>Complete paperwork clarity</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="metric-item">
                        <div class="metric-icon-box"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                        <div class="metric-text">
                            <h4>0% Brokerage Burden</h4>
                            <p>Direct developer benefits</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section-pad about-section-wrap" id="about">
        <div class="container">
            <div class="section-heading-center">
                <span class="micro-badge-tag">OUR HERITAGE</span>
                <h2 class="section-title-italic">About <span>Property Station</span></h2>
                <div class="gold-line-center"></div>
                <p class="section-subtext">A Legacy of Trust, Transparency & Architectural Brilliance in Faridabad & Delhi NCR.</p>
            </div>

            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="about-visual-column">
                        <div class="about-img-box">
                            <img src="uploads/projects/about-luxury.webp" alt="Property Station Luxury Builder Floors" loading="lazy" decoding="async" onerror="this.src='uploads/projects/adore-legend-2.jpg'">
                        </div>
                        <div class="about-floating-card">
                            <span class="about-card-number">25+</span>
                            <span class="about-card-text">Years of Excellence</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="about-content-column">
                        <p class="about-lead-quote">"Ghar Ka Aakhri Station — Where your journey for the perfect property culminates with peace of mind."</p>
                        <p class="about-p-text"><strong>Property Station</strong> is a premier real estate construction, sales, and consultancy enterprise dedicated to delivering high-quality residential spaces, independent builder floors, and premium commercial assets across Faridabad and Delhi NCR.</p>
                        <p class="about-p-text">We pioneer the standard of modern living by uniting world-class structural materials, bespoke European elevation designs, transparent documentations, and on-time handovers. Whether you are looking for your forever family home or a high-yielding real estate investment, our dedicated advisors make the entire process effortless.</p>

                        <ul class="about-features-list">
                            <li><i class="fa-solid fa-circle-check"></i> High Ceilings & Cross Ventilation</li>
                            <li><i class="fa-solid fa-circle-check"></i> Earthquake Resistant RCC Frame</li>
                            <li><i class="fa-solid fa-circle-check"></i> Italian Marble & Modular Kitchens</li>
                            <li><i class="fa-solid fa-circle-check"></i> Seamless Bank Loan Approvals</li>
                        </ul>

                        <div class="d-flex align-items-center gap-3">
                            <a href="pages/projects.php" class="btn-luxury">Explore Our Projects <i class="fa-solid fa-arrow-right"></i></a>
                            <button type="button" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#enquireModal">Talk to Our Team</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Presence of Property Station (Interactive NCR Map & Locations) -->
    <section class="presence-map-section">
        <div class="map-bg-glow map-glow-1"></div>
        <div class="map-bg-glow map-glow-2"></div>

        <div class="container position-relative">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="micro-badge-tag">ELITE NCR ADDRESSES</span>
                    <h2 class="section-title-italic">Presence of <br><span>Property Station</span></h2>
                    <div class="gold-line-center ms-0"></div>
                    <p class="text-light mb-4">We curate and deliver landmark architectural masterworks across India's most highly sought residential and commercial sectors. Select a hub below to explore active inventory.</p>

                    <div class="location-cards-grid">
                        <div class="loc-select-card active" data-location="faridabad">
                            <span class="loc-indicator-dot"></span>
                            <div class="loc-info">
                                <h4>Faridabad Hub</h4>
                                <span class="loc-count-tag">15+ Builder Floors</span>
                            </div>
                        </div>
                        <div class="loc-select-card" data-location="gurugram">
                            <span class="loc-indicator-dot"></span>
                            <div class="loc-info">
                                <h4>Gurugram</h4>
                                <span class="loc-count-tag">8+ Ultra-Luxury Projects</span>
                            </div>
                        </div>
                        <div class="loc-select-card" data-location="delhi">
                            <span class="loc-indicator-dot"></span>
                            <div class="loc-info">
                                <h4>South Delhi</h4>
                                <span class="loc-count-tag">5+ Bespoke Residences</span>
                            </div>
                        </div>
                        <div class="loc-select-card" data-location="noida">
                            <span class="loc-indicator-dot"></span>
                            <div class="loc-info">
                                <h4>Noida NCR</h4>
                                <span class="loc-count-tag">Upcoming Pre-launches</span>
                            </div>
                        </div>
                    </div>

                    <div class="map-stats-strip">
                        <div class="map-stat-box">
                            <h3>25+</h3>
                            <p>Premium Projects</p>
                        </div>
                        <div class="map-stat-box">
                            <h3>10M+</h3>
                            <p>Sq.Ft. Delivered</p>
                        </div>
                        <div class="map-stat-box">
                            <h3>1,200+</h3>
                            <p>Delighted Families</p>
                        </div>
                    </div>

                    <a href="pages/projects.php" class="btn-luxury">View All Locations <i class="fa-solid fa-arrow-right"></i></a>
                </div>

                <div class="col-lg-6">
                    <div class="map-visual-box">
                        <img src="uploads/projects/delhi-ncr-map.jpg" alt="Property Station Delhi NCR Presence Map" loading="lazy" decoding="async" onerror="this.src='https://images.unsplash.com/photo-1524813686514-a57563d77d66?auto=format&fit=crop&w=1000&q=80'">
                        
                        <!-- Dynamic Tooltip Box updated via JS -->
                        <div class="map-dynamic-tooltip" id="mapTooltip">
                            <span class="tooltip-badge-pill" id="tooltipBadge">CORE LUXURY HUB</span>
                            <h4 class="tooltip-title-text" id="tooltipTitle">Faridabad</h4>
                            <p class="tooltip-desc-text" id="tooltipDesc">15+ Handcrafted premium builder floors in elite Sectors 14, 15, 80, 84, 86, and 119. Featuring European elevations, Italian marble, and smart modular kitchens.</p>
                            <div class="tooltip-meta-grid">
                                <div class="tooltip-meta-col">
                                    <span class="lbl">Availability</span>
                                    <span class="val text-success" id="tooltipStatus">Ready to Move & Ongoing</span>
                                </div>
                                <div class="tooltip-meta-col">
                                    <span class="lbl">Price Range</span>
                                    <span class="val" id="tooltipPrice">₹45 Lakhs - ₹3.5 Cr</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Curated Projects Showcase (Swiper Slider) -->
    <section class="section-pad projects-section-wrap" id="projects">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-3">
                <div>
                    <span class="micro-badge-tag">CURATED COLLECTION</span>
                    <h2 class="section-title-italic mb-1">Our Premium <span>Builder Floors & Projects</span></h2>
                    <div class="gold-line-center ms-0"></div>
                    <p class="section-subtext ms-0">Discover our meticulously planned residential and commercial masterworks that redefine modern luxury in Delhi NCR.</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="proj-slider-prev btn btn-outline-dark rounded-circle" style="width:46px;height:46px;display:grid;place-items:center;cursor:pointer;"><i class="fa-solid fa-arrow-left"></i></div>
                    <div class="proj-slider-next btn btn-outline-dark rounded-circle" style="width:46px;height:46px;display:grid;place-items:center;cursor:pointer;"><i class="fa-solid fa-arrow-right"></i></div>
                </div>
            </div>

            <!-- Projects Swiper Slider -->
            <div class="swiper projects-swiper mt-4">
                <div class="swiper-wrapper">
                    <?php foreach ($projects as $p): ?>
                        <div class="swiper-slide h-auto">
                            <div class="project-card-luxury">
                                <div class="project-card-thumb">
                                    <span class="card-crown-badge"><i class="fa-solid fa-crown"></i> PREMIUM RESIDENCE</span>
                                    <span class="card-status-pill"><?= e($p['status']) ?></span>
                                    <img src="<?= e(project_image($p['image'])) ?>" alt="<?= e($p['title']) ?>" loading="lazy" decoding="async" onerror="this.src='https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80'">
                                </div>
                                <div class="project-card-body">
                                    <span class="card-cat-tag"><?= e($p['category']) ?></span>
                                    <h3 class="project-card-title">
                                        <a href="pages/project.php?slug=<?= urlencode($p['slug']) ?>"><?= e($p['title']) ?></a>
                                    </h3>
                                    <p class="card-location-text">
                                        <i class="fa-solid fa-location-dot"></i> <?= e($p['location']) ?>
                                    </p>
                                    <p class="card-desc-snippet"><?= e($p['description']) ?></p>
                                    <div class="project-card-footer">
                                        <div class="card-price-value">
                                            <small>Starting from</small>
                                            <?= e($p['price'] ?: 'Price on Request') ?>
                                        </div>
                                        <a href="pages/project.php?slug=<?= urlencode($p['slug']) ?>" class="btn-skew-explore">
                                            Explore <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination mt-4 position-relative"></div>
            </div>

            <div class="text-center mt-5">
                <a href="pages/projects.php" class="btn-luxury px-5 py-3">View All <?= $totalProjects ?> Projects <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <!-- Founder's Vision / Leadership Section -->
    <section class="founder-section-wrap">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <div class="portrait-gold-box">
                        <div class="portrait-gold-frame-bg"></div>
                        <img src="uploads/projects/agent_profile.jpg" alt="Property Station Legal & Sales Leadership" class="portrait-main-img" loading="lazy" decoding="async" onerror="this.src='https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=900&q=80'">
                        <div class="portrait-badge-card">
                            <span class="portrait-founder-name">Senior Property Consultant</span>
                            <span class="portrait-founder-role">Legal & Sales Desk — Sector 15 Green Corridor</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <span class="micro-badge-tag">LEADERSHIP & TRUST</span>
                    <h2 class="section-title-italic">Guiding Your Journey at <br><span>Property Station</span></h2>
                    <div class="gold-line-center ms-0"></div>
                    <p class="about-p-text">With decades of building mastery and absolute dedication to client trust, <strong>Property Station</strong> has shaped the landscape of premium housing, luxury builder floors, and planned plotted townships in Faridabad & Delhi NCR.</p>

                    <ul class="founder-pillars-list">
                        <li>
                            <span class="pillar-bullet-icon"><i class="fa-solid fa-gem"></i></span>
                            <div class="pillar-text-content">
                                <strong>Architectural Distinction</strong>
                                <p>Delivering European-inspired exterior aesthetics, spacious high-ceiling layouts, and premium cross-ventilation profiles that elevate your daily standard of living.</p>
                            </div>
                        </li>
                        <li>
                            <span class="pillar-bullet-icon"><i class="fa-solid fa-shield-heart"></i></span>
                            <div class="pillar-text-content">
                                <strong>Zero-Compromise Materials</strong>
                                <p>Constructing multi-generational, age-proof homes utilizing certified high-grade concrete, Fe-550 TMT reinforced steels, and pure Italian marble slabs.</p>
                            </div>
                        </li>
                        <li>
                            <span class="pillar-bullet-icon"><i class="fa-solid fa-handshake"></i></span>
                            <div class="pillar-text-content">
                                <strong>Radical Transparency & Legal Guidance</strong>
                                <p>Strict commitment to delivery timelines, crystal clear paperwork, verified titles, and dedicated post-delivery assistance for complete peace of mind.</p>
                            </div>
                        </li>
                    </ul>

                    <div class="founder-quote-banner">
                        <p>"At Property Station, we do not simply build upscale residential floors; we craft luxury sanctuaries where families build lifetimes of cherished memories. Ghar Ka Aakhri Station is our promise of ultimate satisfaction."</p>
                        <div class="founder-quote-author">— Legal & Sales Desk | Call: <?= e(SITE_PHONE) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Construction Philosophy & Specifications Section -->
    <section class="spec-philosophy-section" id="specifications">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-lg-2">
                    <div class="spec-img-wrapper">
                        <img src="uploads/projects/founder-luxury.webp" alt="Continuous Discussion & Improvement" loading="lazy" decoding="async" onerror="this.src='https://images.unsplash.com/photo-1541888946425-d0fbb186156f?auto=format&fit=crop&w=900&q=80'">
                        <div class="spec-overlay-tag">
                            <small>DAILY ON-SITE AUDITS</small>
                            <strong>Refining Architectural Excellence</strong>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 order-lg-1">
                    <span class="micro-badge-tag">OUR CONSTRUCTION PHILOSOPHY</span>
                    <h2 class="section-title-italic">Constant Discussion & <br><span>Continuous Improvement</span></h2>
                    <div class="gold-line-center ms-0"></div>
                    <p class="about-p-text">Structural superiority is never accidental at Property Station—it is the direct outcome of daily on-site coordination between structural engineers, licensed architects, and quality inspection teams.</p>

                    <ul class="founder-pillars-list">
                        <li>
                            <span class="pillar-bullet-icon"><i class="fa-solid fa-users-gear"></i></span>
                            <div class="pillar-text-content">
                                <strong>Daily Engineering Alignments</strong>
                                <p>Regular site reviews to ensure absolute precision between 3D architectural schematics and physical RCC execution.</p>
                            </div>
                        </li>
                        <li>
                            <span class="pillar-bullet-icon"><i class="fa-solid fa-layer-group"></i></span>
                            <div class="pillar-text-content">
                                <strong>Premium Material Testing</strong>
                                <p>Every consignment of cement, TMT rebars, electrical conduits, and anti-skid vitrified tiles undergoes independent lab checks.</p>
                            </div>
                        </li>
                        <li>
                            <span class="pillar-bullet-icon"><i class="fa-solid fa-clipboard-check"></i></span>
                            <div class="pillar-text-content">
                                <strong>Multi-Stage Quality Audits</strong>
                                <p>Rigorous pre-casting checks, waterproofing membrane testing, and plumbing pressure tests before finishing touches.</p>
                            </div>
                        </li>
                    </ul>

                    <button type="button" class="btn-luxury" data-bs-toggle="modal" data-bs-target="#enquireModal">
                        Download Quality Specifications <i class="fa-solid fa-file-arrow-down"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Luxury Gallery Section -->
    <section class="gallery-section-wrap" id="gallery">
        <div class="container">
            <div class="section-heading-center">
                <span class="micro-badge-tag">VISUAL EXCELLENCE</span>
                <h2 class="section-title-italic text-white">Experience Our <span>Architectural Craft</span></h2>
                <div class="gold-line-center"></div>
                <p class="section-subtext text-light">Glimpse through the interior elegance, modern elevation facades, and luxury finishes of our builder floors.</p>
            </div>

            <div class="gallery-grid mt-4">
                <div class="gallery-item-card">
                    <img src="uploads/projects/adore-legend-2.jpg" alt="Luxury Living Room" loading="lazy" decoding="async">
                    <div class="gallery-item-overlay">
                        <h5>Spacious Living Lounge</h5>
                        <span>Double-height High Ceilings</span>
                    </div>
                </div>
                <div class="gallery-item-card">
                    <img src="uploads/projects/adore-broadway.webp" alt="Commercial Retail Boulevard" loading="lazy" decoding="async">
                    <div class="gallery-item-overlay">
                        <h5>High-Street Boulevard</h5>
                        <span>Grade-A Commercial Space</span>
                    </div>
                </div>
                <div class="gallery-item-card">
                    <img src="uploads/projects/bp-homes-barleria.jpg" alt="Resort Style Builder Floor" loading="lazy" decoding="async">
                    <div class="gallery-item-overlay">
                        <h5>Resort Style Elevation</h5>
                        <span>Bespoke Exterior Façade</span>
                    </div>
                </div>
                <div class="gallery-item-card">
                    <img src="uploads/projects/bptp-skynest.jpg" alt="Sky Garden Towers" loading="lazy" decoding="async">
                    <div class="gallery-item-overlay">
                        <h5>Sky Garden Residences</h5>
                        <span>Panoramic NCR Skyline</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact & Enquiry Section -->
    <section class="contact-section-wrap" id="contact">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5">
                    <span class="micro-badge-tag">GET IN TOUCH</span>
                    <h2 class="section-title-italic">Let's Discuss Your <span>Dream Address</span></h2>
                    <div class="gold-line-center ms-0"></div>
                    <p class="about-p-text">Our dedicated property advisors are ready to guide you through floor plans, on-site walkthroughs, and developer pricing.</p>

                    <div class="contact-item-row">
                        <div class="contact-item-icon"><i class="fa-solid fa-phone"></i></div>
                        <div class="contact-item-info">
                            <small>Call Our Expert</small>
                            <strong><a href="tel:<?= e(SITE_PHONE) ?>"><?= e(SITE_PHONE) ?></a></strong>
                        </div>
                    </div>

                    <div class="contact-item-row">
                        <div class="contact-item-icon"><i class="fa-solid fa-envelope"></i></div>
                        <div class="contact-item-info">
                            <small>Email Inquiries</small>
                            <strong><a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></strong>
                        </div>
                    </div>

                    <div class="contact-item-row">
                        <div class="contact-item-icon"><i class="fa-solid fa-map-location-dot"></i></div>
                        <div class="contact-item-info">
                            <small>Legal & Sales Office</small>
                            <strong>Sector 15 Green Corridor, Faridabad, Haryana</strong>
                        </div>
                    </div>

                    <div class="contact-item-row">
                        <div class="contact-item-icon"><i class="fa-brands fa-whatsapp"></i></div>
                        <div class="contact-item-info">
                            <small>Instant WhatsApp Support</small>
                            <strong><a href="https://wa.me/<?= e(SITE_WHATSAPP) ?>" target="_blank"><?= e(SITE_PHONE) ?> (Legal & Sales Desk)</a></strong>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="contact-card-box">
                        <h3 class="fw-bold mb-2">Request Detailed Information</h3>
                        <p class="text-muted mb-4">Leave your contact details and our senior property specialist will get in touch with brochures and pricing.</p>

                        <?php if ($enquirySent): ?>
                            <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
                                <i class="fa-solid fa-circle-check fs-5"></i>
                                <div><strong>Thank you!</strong> Your enquiry has been received. Our team will contact you shortly.</div>
                            </div>
                        <?php elseif ($enquiryError): ?>
                            <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                                <i class="fa-solid fa-triangle-exclamation fs-5"></i>
                                <div><?= e($enquiryError) ?></div>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="#contact">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="quick_enquiry" value="1">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Your Full Name *</label>
                                    <input type="text" name="name" class="form-control py-3" placeholder="Enter full name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Mobile Number *</label>
                                    <input type="tel" name="phone" class="form-control py-3" placeholder="10-digit mobile number" required pattern="[0-9]{10}" maxlength="10">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email Address (Optional)</label>
                                    <input type="email" name="email" class="form-control py-3" placeholder="you@domain.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Budget Range</label>
                                    <select name="budget" class="form-select py-3">
                                        <option value="">Select your budget</option>
                                        <option value="Under ₹50 Lakh">Under ₹50 Lakh</option>
                                        <option value="₹50 Lakh – ₹1 Cr">₹50 Lakh – ₹1 Cr</option>
                                        <option value="₹1 Cr – ₹2 Cr">₹1 Cr – ₹2 Cr</option>
                                        <option value="₹2 Cr+">₹2 Cr & Above</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Interested Project</label>
                                    <select name="project_id" class="form-select py-3">
                                        <option value="">General Inquiry / Any Project</option>
                                        <?php foreach ($projects as $p): ?>
                                            <option value="<?= (int)$p['id'] ?>"><?= e($p['title']) ?> (<?= e($p['location']) ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Message / Specific Requirements</label>
                                    <textarea name="message" class="form-control py-3" rows="3" placeholder="Tell us what you are looking for (e.g. 3 BHK, Floor preference, etc.)"></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn-luxury w-100 justify-content-center py-3 fs-6">
                                        <i class="fa-solid fa-paper-plane me-2"></i> Submit Inquiry Now
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
$extraScripts = <<<'EOD'
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Hero Slider Swiper
    const heroSwiper = new Swiper('.hero-slider', {
        loop: true,
        speed: 1000,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false
        },
        pagination: {
            el: '.hero-slider .swiper-pagination',
            clickable: true
        },
        navigation: {
            nextEl: '.hero-slider-next',
            prevEl: '.hero-slider-prev'
        }
    });

    // Projects Swiper Slider
    const projectsSwiper = new Swiper('.projects-swiper', {
        slidesPerView: 1,
        spaceBetween: 24,
        loop: true,
        autoplay: {
            delay: 4500,
            disableOnInteraction: false
        },
        navigation: {
            nextEl: '.proj-slider-next',
            prevEl: '.proj-slider-prev'
        },
        pagination: {
            el: '.projects-swiper .swiper-pagination',
            clickable: true
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 24
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 28
            },
            1280: {
                slidesPerView: 4,
                spaceBetween: 28
            }
        }
    });

    // Interactive Presence Map Data
    const locationInfo = {
        faridabad: {
            title: "Faridabad Core Hub",
            badge: "CORE LUXURY HUB",
            desc: "15+ Handcrafted builder floors in elite Sectors 14, 15, 80, 84, 86, and 119. Our flagship center of design excellence featuring European elevations and Italian marble.",
            status: "Ready to Move & Ongoing",
            price: "₹35 Lakhs - ₹3.5 Cr"
        },
        gurugram: {
            title: "Gurugram Luxury Hub",
            badge: "PREMIUM ELITE SECTOR",
            desc: "8+ Ultra-luxury residences along Golf Course Extension Road. Automated smart homes, serene high-end societies, and rapid metro connectivity.",
            status: "Booking Open",
            price: "₹1.5 Cr - ₹6.5 Cr"
        },
        delhi: {
            title: "South Delhi Collection",
            badge: "ULTRA-LUXURY COLLECTION",
            desc: "5+ Super premium architectural residences in Greater Kailash, Vasant Vihar, and Panchsheel with private double-height lobbies.",
            status: "Exclusive Collaboration",
            price: "₹4.5 Cr - ₹12.0 Cr"
        },
        noida: {
            title: "Noida NCR Residences",
            badge: "MODERN RESIDENCES",
            desc: "Upcoming luxury projects strategically positioned in Sectors 44, 105, and 150 with world-class structural health and clubhouses.",
            status: "Pre-Launch Bookings",
            price: "₹95 Lakhs - ₹3.8 Cr"
        }
    };

    const locCards = document.querySelectorAll(".loc-select-card");
    const tTitle = document.getElementById("tooltipTitle");
    const tBadge = document.getElementById("tooltipBadge");
    const tDesc = document.getElementById("tooltipDesc");
    const tStatus = document.getElementById("tooltipStatus");
    const tPrice = document.getElementById("tooltipPrice");

    locCards.forEach(card => {
        card.addEventListener("click", function() {
            locCards.forEach(c => c.classList.remove("active"));
            this.classList.add("active");
            const locKey = this.getAttribute("data-location");
            const data = locationInfo[locKey];
            if (data) {
                tTitle.textContent = data.title;
                tBadge.textContent = data.badge;
                tDesc.textContent = data.desc;
                tStatus.textContent = data.status;
                tPrice.textContent = data.price;
            }
        });
    });
});
</script>
EOD;
require_once __DIR__ . '/includes/footer.php';