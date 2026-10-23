<?php
/**
 * Shared Luxury Footer Component for Property Station
 * Always in sync across all pages (index.php, pages/*.php)
 */
if (!isset($pdo)) {
    require_once __DIR__ . '/../config.php';
}

$isSubPage = (basename(dirname($_SERVER['SCRIPT_FILENAME'] ?? '')) === 'pages');
$fWebRoot = $isSubPage ? '../' : '';
$fPagesDir = $isSubPage ? '' : 'pages/';

// Fetch live projects for footer dynamically
try {
    $footerProjectsStmt = $pdo->query("SELECT id, title, slug, location, price, category FROM projects WHERE is_active = 1 ORDER BY featured DESC, id DESC LIMIT 5");
    $footerProjects = $footerProjectsStmt->fetchAll();

    $footerCountStmt = $pdo->query("SELECT COUNT(*) FROM projects WHERE is_active = 1");
    $footerTotalProjects = (int)$footerCountStmt->fetchColumn();
} catch (Exception $e) {
    $footerProjects = [];
    $footerTotalProjects = 0;
}
?>

<!-- Luxury Footer -->
<footer class="luxury-footer" id="siteFooter">
    <!-- Pre-Footer VIP Consultation Strip -->
    <div class="footer-consult-strip">
        <div class="container">
            <div class="footer-consult-card">
                <div class="footer-consult-text">
                    <div class="d-inline-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            <i class="fa-solid fa-gem me-1"></i> BESPOKE REAL ESTATE ADVISORY
                        </span>
                        <span class="text-secondary small d-none d-md-inline">• Sector 15 Green Corridor, Faridabad</span>
                    </div>
                    <h3 class="mb-1 text-white fw-bold">Looking for Your Dream Property in Delhi NCR?</h3>
                    <p class="text-secondary mb-0">Speak directly with our senior consultant for verified luxury builder floors, plots, and commercial retail investments.</p>
                </div>
                <div class="footer-consult-actions d-flex align-items-center flex-wrap gap-3">
                    <a href="tel:<?= e(SITE_PHONE) ?>" class="btn-luxury" style="box-shadow: 0 4px 20px rgba(255, 103, 2, 0.35);">
                        <i class="fa-solid fa-phone me-2"></i> Call: <?= e(SITE_PHONE) ?>
                    </a>
                    <a href="https://wa.me/<?= e(SITE_WHATSAPP) ?>?text=<?= urlencode('Hello Property Station team, I would like to enquire about luxury builder floors and properties in Delhi NCR.') ?>" target="_blank" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="border-color: rgba(255,255,255,0.25);">
                        <i class="fa-brands fa-whatsapp text-success fs-5"></i>
                        <span>WhatsApp Desk</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Footer Content -->
    <div class="container footer-main-grid">
        <div class="row g-4 g-lg-5">
            <!-- Column 1: Brand & Credentials -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand-box">
                    <a href="<?= $fWebRoot ?>index.php" class="d-inline-block mb-3">
                        <img src="<?= $fWebRoot ?>assets/logo.jpeg" alt="Property Station Logo" class="footer-logo-img" style="height: 52px; max-height: 52px; width: auto; max-width: 180px; object-fit: contain; background: #ffffff; padding: 6px 12px; border-radius: 8px; border: 1px solid rgba(255, 103, 2, 0.4); display: inline-block;">
                    </a>
                    <div class="footer-tagline-badge mb-3">
                        <span class="text-warning fw-bold">GHAR KA AAKHRI STATION</span>
                    </div>
                    <p class="footer-desc text-secondary mb-4">
                        Property Station is Delhi NCR's premier real estate consultancy, specializing in bespoke luxury builder floors, gated villa enclaves, and Grade-A commercial spaces with 100% legal compliance.
                    </p>
                    
                    <div class="footer-trust-pills d-flex flex-wrap gap-2 mb-4">
                        <span class="trust-pill"><i class="fa-solid fa-shield-check text-warning me-1"></i> RERA Verified</span>
                        <span class="trust-pill"><i class="fa-solid fa-award text-warning me-1"></i> 25+ Years Trust</span>
                        <span class="trust-pill"><i class="fa-solid fa-handshake text-warning me-1"></i> Zero Brokerage Support</span>
                    </div>

                    <div class="footer-social-links d-flex gap-2">
                        <a href="https://facebook.com" target="_blank" class="footer-social-btn" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://instagram.com" target="_blank" class="footer-social-btn" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="https://youtube.com" target="_blank" class="footer-social-btn" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                        <a href="https://wa.me/<?= e(SITE_WHATSAPP) ?>" target="_blank" class="footer-social-btn" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h4 class="footer-col-title">Navigation</h4>
                <div class="footer-title-gold-line"></div>
                <ul class="footer-nav-list list-unstyled">
                    <li><a href="<?= $fWebRoot ?>index.php"><i class="fa-solid fa-angle-right me-2 text-warning"></i> Home</a></li>
                    <li><a href="<?= $fPagesDir ?>about.php"><i class="fa-solid fa-angle-right me-2 text-warning"></i> About Us</a></li>
                    <li><a href="<?= $fPagesDir ?>projects.php"><i class="fa-solid fa-angle-right me-2 text-warning"></i> All Projects</a></li>
                    <li><a href="<?= $fPagesDir ?>specifications.php"><i class="fa-solid fa-angle-right me-2 text-warning"></i> Specifications</a></li>
                    <li><a href="<?= $fPagesDir ?>gallery.php"><i class="fa-solid fa-angle-right me-2 text-warning"></i> Visual Gallery</a></li>
                    <li><a href="<?= $fPagesDir ?>contact.php"><i class="fa-solid fa-angle-right me-2 text-warning"></i> Contact Us</a></li>
                    <li class="mt-2"><a href="<?= $fWebRoot ?>admin/login.php" class="text-warning-emphasis"><i class="fa-solid fa-lock me-2"></i> Admin Portal</a></li>
                </ul>
            </div>

            <!-- Column 3: Top Projects -->
            <div class="col-lg-3 col-md-6">
                <h4 class="footer-col-title">Top Projects</h4>
                <div class="footer-title-gold-line"></div>
                <ul class="footer-nav-list list-unstyled">
                    <?php if (!empty($footerProjects)): ?>
                        <?php foreach ($footerProjects as $fp): ?>
                            <li>
                                <a href="<?= $fPagesDir ?>project.php?slug=<?= urlencode($fp['slug']) ?>">
                                    <i class="fa-solid fa-angle-right me-2 text-warning"></i> <?= e($fp['title']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li class="mt-2 pt-1">
                            <a href="<?= $fPagesDir ?>projects.php" class="text-warning fw-semibold">
                                View all <?= $footerTotalProjects ?> properties <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="text-secondary small">No projects currently listed.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Column 4: Contact & Office -->
            <div class="col-lg-3 col-md-6">
                <h4 class="footer-col-title">Headquarters</h4>
                <div class="footer-title-gold-line"></div>
                <div class="footer-contact-info">
                    <div class="footer-contact-row d-flex gap-3 mb-3">
                        <div class="footer-contact-icon">
                            <i class="fa-solid fa-location-dot text-warning fs-5"></i>
                        </div>
                        <div class="footer-contact-details">
                            <strong class="text-light d-block">Corporate Office</strong>
                            <p class="text-secondary small mb-0"><?= e(SITE_LOCATION) ?></p>
                        </div>
                    </div>

                    <div class="footer-contact-row d-flex gap-3 mb-3">
                        <div class="footer-contact-icon">
                            <i class="fa-solid fa-phone text-warning fs-5"></i>
                        </div>
                        <div class="footer-contact-details">
                            <strong class="text-light d-block">Direct Advisory</strong>
                            <a href="tel:<?= e(SITE_PHONE) ?>" class="text-secondary small text-decoration-none hover-gold d-block"><?= e(SITE_PHONE) ?></a>
                        </div>
                    </div>

                    <div class="footer-contact-row d-flex gap-3 mb-3">
                        <div class="footer-contact-icon">
                            <i class="fa-solid fa-envelope text-warning fs-5"></i>
                        </div>
                        <div class="footer-contact-details">
                            <strong class="text-light d-block">Official Email</strong>
                            <a href="mailto:<?= e(SITE_EMAIL) ?>" class="text-secondary small text-decoration-none hover-gold d-block"><?= e(SITE_EMAIL) ?></a>
                        </div>
                    </div>

                    <div class="footer-contact-row d-flex gap-3 mb-3">
                        <div class="footer-contact-icon">
                            <i class="fa-brands fa-whatsapp text-success fs-5"></i>
                        </div>
                        <div class="footer-contact-details">
                            <strong class="text-light d-block">Instant WhatsApp Desk</strong>
                            <a href="https://wa.me/<?= e(SITE_WHATSAPP) ?>" target="_blank" class="text-secondary small text-decoration-none hover-gold d-block">+91 <?= e(SITE_WHATSAPP) ?></a>
                        </div>
                    </div>

                    <a href="<?= $fPagesDir ?>contact.php" class="btn btn-outline-warning btn-sm rounded-pill px-3 py-2 w-100 mt-2 d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-map-location-dot"></i> Get Directions & Schedule Visit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom Bar -->
    <div class="footer-bottom-bar">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 w-100">
                <div class="footer-copy-text">
                    © <?= date('Y') ?> <strong>Property Station</strong>. All Rights Reserved. <span class="text-warning">Ghar Ka Aakhri Station</span>.
                </div>
                <div class="footer-design-credit small text-secondary">
                    Design by <a href="https://mineib.com/" target="_blank" rel="noopener noreferrer" class="text-warning fw-semibold text-decoration-none hover-gold">Mineib Creative Technology</a>
                </div>
                <div class="footer-terms-links small">
                    <a href="<?= $fPagesDir ?>projects.php" class="text-secondary text-decoration-none me-3 hover-gold">Projects Directory</a>
                    <a href="<?= $fPagesDir ?>contact.php" class="text-secondary text-decoration-none hover-gold">Visit Us</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php if (!isset($disableGlobalModal)): ?>
<!-- Global Enquiry Modal -->
<div class="modal fade luxury-modal" id="enquireModal" tabindex="-1" aria-labelledby="enquireModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <img src="<?= $fWebRoot ?>assets/logo.jpeg" alt="" style="width: 32px; height: 32px; object-fit: contain; border-radius: 6px;">
                    <div>
                        <h5 class="modal-title font-heading fw-bold mb-0 text-white" id="enquireModalLabel">Request Private Consultation</h5>
                        <p class="small text-secondary mb-0">Direct advisory for luxury residences in Delhi NCR</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3 pb-4 px-4">
                <form action="<?= $fPagesDir ?>contact.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="modal_enquiry" value="1">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-light">Full Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-light">Mobile Number *</label>
                            <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-light">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-light">Select Project / Property Interest</label>
                        <select name="project_id" class="form-select">
                            <option value="">General Inquiry / Custom Builder Floor</option>
                            <?php if (!empty($footerProjects)): ?>
                                <?php foreach ($footerProjects as $fp): ?>
                                    <option value="<?= (int)$fp['id'] ?>"><?= e($fp['title']) ?> (<?= e($fp['location']) ?>)</option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-light">Investment Budget</label>
                        <select name="budget" class="form-select">
                            <option value="">Select Expected Budget Range</option>
                            <option value="₹1.2 Cr - ₹2.0 Cr">₹1.2 Cr - ₹2.0 Cr (Luxury Floors)</option>
                            <option value="₹2.0 Cr - ₹4.5 Cr">₹2.0 Cr - ₹4.5 Cr (Bespoke Independent)</option>
                            <option value="₹4.5 Cr - ₹10+ Cr">₹4.5 Cr - ₹10+ Cr (Ultra Luxury / Villas)</option>
                            <option value="Commercial Investment">Commercial / High Street Retail</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-light">Specific Requirements / Message</label>
                        <textarea name="message" class="form-control" rows="3" placeholder="Tell us about your preferred sector, size, floor preference, or request a site visit..."></textarea>
                    </div>

                    <button type="submit" name="contact_submit" class="btn-luxury w-100 py-3 text-center justify-content-center fw-bold">
                        <i class="fa-solid fa-paper-plane me-2"></i> Submit Confidential Request
                    </button>
                    <p class="small text-secondary text-center mt-2 mb-0" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-lock text-warning me-1"></i> Your information is kept 100% confidential. No spam guaranteed.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Floating WhatsApp Action Button -->
<a href="https://wa.me/<?= e(SITE_WHATSAPP) ?>?text=<?= urlencode('Hello Property Station, I would like to inquire about properties.') ?>" 
   target="_blank" 
   class="floating-whatsapp-btn" 
   title="Chat with Us on WhatsApp" 
   aria-label="Chat on WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
</a>

<!-- Back To Top Button -->
<button type="button" class="back-to-top-btn" id="backToTopBtn" title="Back to top" aria-label="Back to top">
    <i class="fa-solid fa-chevron-up"></i>
</button>

<!-- Bootstrap 5.3.3 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Swiper JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
// Header Scroll Styling & Back to Top logic
document.addEventListener("DOMContentLoaded", function() {
    const mainHeader = document.getElementById("mainHeader");
    const bttBtn = document.getElementById("backToTopBtn");

    window.addEventListener("scroll", function() {
        const scrolled = window.scrollY > 40;
        if (mainHeader) {
            if (scrolled) {
                mainHeader.classList.add("scrolled");
            } else {
                mainHeader.classList.remove("scrolled");
            }
        }
        if (bttBtn) {
            if (window.scrollY > 300) {
                bttBtn.classList.add("visible");
            } else {
                bttBtn.classList.remove("visible");
            }
        }
    });

    if (bttBtn) {
        bttBtn.addEventListener("click", function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});
</script>
<?php if (!empty($extraScripts)) echo $extraScripts; ?>
</body>
</html>
