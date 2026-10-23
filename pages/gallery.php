<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';

// Fetch active projects for navbar dropdown & modals
$allProjects = $pdo->query("SELECT id, title, slug, location, price, category FROM projects WHERE is_active = 1 ORDER BY featured DESC, id DESC")->fetchAll();

$galleryItems = [
    [
        'title' => 'Adore Legend 2 - Luxury Living Lounge',
        'category' => 'Residential',
        'location' => 'Sector 119, Faridabad',
        'image' => '../uploads/projects/adore-legend-2.jpg',
        'tag' => 'Italian Marble & High Ceilings'
    ],
    [
        'title' => 'Adore Broadway - Commercial Retail Boulevard',
        'category' => 'Commercial',
        'location' => 'Sector 84, Faridabad',
        'image' => '../uploads/projects/adore-broadway.webp',
        'tag' => 'Grade-A Retail Hub'
    ],
    [
        'title' => 'BP Homes Barleria - European Classical Façade',
        'category' => 'Residential',
        'location' => 'Sector 84, Faridabad',
        'image' => '../uploads/projects/bp-homes-barleria.jpg',
        'tag' => 'Modern Elevation'
    ],
    [
        'title' => 'BPTP Skynest - High-Rise Penthouse Towers',
        'category' => 'Residential',
        'location' => 'Sector 80, Faridabad',
        'image' => '../uploads/projects/bptp-skynest.jpg',
        'tag' => 'Sky Garden View'
    ],
    [
        'title' => 'Adore Arpanam - Serene Plotted Living',
        'category' => 'Plots',
        'location' => 'Sector 84, Faridabad',
        'image' => '../uploads/projects/adore-arpanam.webp',
        'tag' => 'Gated Community'
    ],
    [
        'title' => 'Rasa Enclave - Independent Floor Architecture',
        'category' => 'Residential',
        'location' => 'Sector 86, Faridabad',
        'image' => '../uploads/projects/rasa-enclave.jpg',
        'tag' => 'Bespoke Finishing'
    ],
    [
        'title' => 'Sector 15 Signature Floors - Grand Elevation',
        'category' => 'Residential',
        'location' => 'Sector 15 Green Corridor',
        'image' => '../uploads/projects/banner-1.webp',
        'tag' => 'Flagship Landmark'
    ],
    [
        'title' => 'Ultra-Luxury Entrance Lobbies & Lighting',
        'category' => 'Residential',
        'location' => 'Faridabad Prime Sectors',
        'image' => '../uploads/projects/banner-4.webp',
        'tag' => 'Double Height Foyer'
    ],
    [
        'title' => 'Modern Classical Facade & Glass Balconies',
        'category' => 'Residential',
        'location' => 'Delhi NCR Corridors',
        'image' => '../uploads/projects/banner-2.webp',
        'tag' => 'European Architecture'
    ]
];
$pageTitle = 'Architectural Gallery & Living Spaces | Property Station';
$pageDescription = 'Browse the visual gallery of luxury builder floors, commercial landmarks, and European elevations delivered by Property Station across Faridabad & Delhi NCR.';
$activePage = 'gallery';
require_once __DIR__ . '/../includes/header.php';
?>

    <!-- Page Hero Banner -->
    <section class="about-page-hero">
        <div class="container text-center position-relative">
            <div class="breadcrumb-gold">
                <a href="../index.php"><i class="fa-solid fa-house"></i> Home</a>
                <i class="fa-solid fa-chevron-right fs-xs text-secondary"></i>
                <span>Visual Portfolio</span>
            </div>
            <h1 class="display-4 fw-bold font-heading text-white mb-3">
                Architectural <span class="gold-text">Gallery</span>
            </h1>
            <div class="gold-line-center"></div>
            <p class="text-light mx-auto fs-5 mb-0" style="max-width: 760px; font-weight: 300; line-height: 1.8;">
                A curated glimpse into the handcrafted elevations, double-height foyers, Italian marble lounges, and prime commercial boulevard developments.
            </p>
        </div>
    </section>

    <!-- Main Gallery Grid -->
    <section class="section-pad bg-light">
        <div class="container">
            <div class="row g-4">
                <?php foreach ($galleryItems as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="gallery-item-card position-relative rounded-4 overflow-hidden shadow-sm" style="height: 380px;">
                            <img src="<?= e($item['image']) ?>" alt="<?= e($item['title']) ?>" class="w-100 h-100" style="object-fit: cover; transition: transform 0.6s ease;" onerror="this.src='../uploads/projects/adore-legend-2.jpg'">
                            <div class="gallery-item-overlay p-4 d-flex flex-column justify-content-end" style="background: linear-gradient(to top, rgba(6, 11, 22, 0.95) 0%, rgba(6, 11, 22, 0.3) 60%, transparent 100%); position: absolute; inset: 0;">
                                <span class="badge bg-warning text-dark px-2 py-1 rounded-pill fw-bold mb-2 align-self-start" style="font-size: 0.72rem;">
                                    <?= e($item['tag']) ?>
                                </span>
                                <h5 class="text-white fw-bold mb-1 font-heading" style="font-size: 1.15rem;"><?= e($item['title']) ?></h5>
                                <span class="text-secondary small"><i class="fa-solid fa-location-dot text-warning me-1"></i> <?= e($item['location']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-5">
                <a href="projects.php" class="btn-luxury">Explore Complete Property Inventory <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </section>

    <!-- Shared Luxury Footer Component -->
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
