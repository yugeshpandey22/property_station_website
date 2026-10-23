<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$p = [
    'title' => '',
    'slug' => '',
    'category' => 'Residential',
    'location' => '',
    'price' => 'Price on Request',
    'status' => 'New Launch',
    'description' => '',
    'amenities' => '',
    'rera_number' => '',
    'image' => null,
    'featured' => 0,
    'is_active' => 1
];

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = ?');
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if ($found) {
        $p = $found;
    }
}

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    verify_csrf();
    $title = trim($_POST['title'] ?? '');
    $slug = strtolower(trim($_POST['slug'] ?? ''));
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
    $slug = trim($slug, '-');
    if (!$slug && $title) {
        $slug = trim(preg_replace('/[^a-z0-9-]+/', '-', strtolower($title)), '-');
    }

    $image = $p['image'];
    if (!empty($_FILES['image']['name'])) {
        $f = $_FILES['image'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($f['tmp_name']);
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

        if ($f['error'] !== UPLOAD_ERR_OK || !isset($allowed[$mime]) || $f['size'] > 6 * 1024 * 1024) {
            $error = 'Please upload a valid JPG, PNG, or WebP image under 6 MB.';
        } else {
            $ext = $allowed[$mime];
            $newFilename = bin2hex(random_bytes(10)) . '.' . $ext;
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0755, true);
            }
            if (move_uploaded_file($f['tmp_name'], UPLOAD_DIR . $newFilename)) {
                $image = $newFilename;
            } else {
                $error = 'Failed to save the uploaded image to the server disk.';
            }
        }
    }

    $location = trim($_POST['location'] ?? '');
    $category = $_POST['category'] ?? 'Residential';
    $price = trim($_POST['price'] ?? 'Price on Request');
    $status = $_POST['status'] ?? 'New Launch';
    $description = trim($_POST['description'] ?? '');
    $amenities = trim($_POST['amenities'] ?? '');
    $rera = trim($_POST['rera_number'] ?? '');
    $featured = isset($_POST['featured']) ? 1 : 0;
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if (!$error) {
        if ($title === '' || $location === '') {
            $error = 'Project title and location are required fields.';
        } else {
            try {
                if ($id) {
                    $sql = 'UPDATE projects SET title=?, slug=?, category=?, location=?, price=?, status=?, description=?, amenities=?, rera_number=?, image=?, featured=?, is_active=? WHERE id=?';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$title, $slug, $category, $location, $price, $status, $description, $amenities, $rera, $image, $featured, $isActive, $id]);
                } else {
                    $sql = 'INSERT INTO projects (title, slug, category, location, price, status, description, amenities, rera_number, image, featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$title, $slug, $category, $location, $price, $status, $description, $amenities, $rera, $image, $featured, $isActive]);
                }
                header('Location: projects.php?success=1');
                exit;
            } catch (PDOException $ex) {
                $error = 'Could not save project. Check if the URL slug "' . e($slug) . '" is already taken.';
            }
        }
    }

    $p = array_merge($p, $_POST);
    $p['image'] = $image;
}

$pageTitle = ($id ? 'Edit' : 'Create') . ' Project';
$activeNav = $id ? 'projects' : 'add_project';
require_once __DIR__ . '/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="font-size: 1.75rem; color: #0f172a;">
            <?= $id ? 'Edit: ' . e($p['title']) : 'Add New Property Masterwork' ?>
        </h2>
        <p class="text-secondary mb-0">Fill in architectural details, specifications, pricing, and visual assets.</p>
    </div>
    <a href="projects.php" class="btn-adm-outline">
        <i class="fa-solid fa-arrow-left"></i> Back to Projects
    </a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="fa-solid fa-circle-exclamation fs-5 flex-shrink-0"></i>
        <div><?= e($error) ?></div>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

    <div class="row g-4">
        <!-- Left Column: Core Specifications -->
        <div class="col-lg-8">
            <div class="admin-form-card mb-4">
                <h5 class="fw-bold text-dark border-bottom pb-3 mb-4">
                    <i class="fa-solid fa-layer-group text-warning me-2"></i> Project Details
                </h5>

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label-adm">Project Title / Name *</label>
                        <input type="text" name="title" class="form-control-adm" required maxlength="190" placeholder="e.g. Adore Legend 2 Luxury Builder Floors" value="<?= e($p['title']) ?>" id="projectTitleInput">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-adm">Property Category</label>
                        <select name="category" class="form-select-adm">
                            <?php foreach (['Residential', 'Commercial', 'Plots', 'Villa', 'Other'] as $cat): ?>
                                <option value="<?= $cat ?>" <?= $p['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-adm">URL Slug (Web Address)</label>
                        <input type="text" name="slug" class="form-control-adm" placeholder="e.g. adore-legend-2" value="<?= e($p['slug']) ?>" id="projectSlugInput">
                        <small class="text-muted" style="font-size: 0.76rem;">Leave empty to auto-generate from title.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-adm">Location / Sector *</label>
                        <input type="text" name="location" class="form-control-adm" required maxlength="190" placeholder="e.g. Sector 84, Greater Faridabad" value="<?= e($p['location']) ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-adm">Price Display Text</label>
                        <input type="text" name="price" class="form-control-adm" maxlength="120" placeholder="e.g. ₹ 65 Lakhs - ₹ 1.85 Cr" value="<?= e($p['price']) ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-adm">Construction Status</label>
                        <select name="status" class="form-select-adm">
                            <?php foreach (['New Launch', 'Under Construction', 'Ready to Move', 'Upcoming'] as $st): ?>
                                <option value="<?= $st ?>" <?= $p['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label-adm">RERA Registration Number</label>
                        <input type="text" name="rera_number" class="form-control-adm" placeholder="e.g. HRERA-PKL-FBD-123-2024 (Optional)" value="<?= e($p['rera_number']) ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label-adm">Comprehensive Description</label>
                        <textarea name="description" class="form-control-adm" rows="5" placeholder="Detail the architectural elevation, floor plans, room configurations, cross-ventilation, and surroundings..."><?= e($p['description']) ?></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label-adm">Key Amenities / Specifications (Bullet Points)</label>
                        <textarea name="amenities" class="form-control-adm" rows="4" placeholder="e.g. High Ceilings • Italian Marble • Modular Kitchen • 100% Power Backup • Dedicated Stilt Parking"><?= e($p['amenities']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Media & Visibility -->
        <div class="col-lg-4">
            <!-- Media Upload Box -->
            <div class="admin-form-card mb-4">
                <h5 class="fw-bold text-dark border-bottom pb-3 mb-3">
                    <i class="fa-solid fa-image text-warning me-2"></i> Showcase Media
                </h5>

                <div class="mb-3">
                    <label class="form-label-adm">Featured Cover Photo</label>
                    <div class="image-upload-dropzone" onclick="document.getElementById('projectImageInput').click();">
                        <div id="imagePreviewContainer" class="mb-2">
                            <?php if ($p['image']): ?>
                                <img src="<?= e(project_image($p['image'])) ?>" alt="Current Project Image" class="w-100 rounded-3" style="max-height: 200px; object-fit: cover;" id="previewImgTag">
                            <?php else: ?>
                                <div class="py-4">
                                    <i class="fa-solid fa-cloud-arrow-up text-warning fs-1 mb-2"></i>
                                    <p class="mb-1 text-dark fw-bold">Click to select photo</p>
                                    <small class="text-muted">JPG, PNG, WebP up to 6 MB</small>
                                </div>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="image" id="projectImageInput" class="d-none" accept="image/jpeg,image/png,image/webp">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 mt-2">
                            <i class="fa-solid fa-arrow-up-from-bracket me-1"></i> Choose File
                        </button>
                    </div>
                </div>
            </div>

            <!-- Publishing & Flags -->
            <div class="admin-form-card mb-4">
                <h5 class="fw-bold text-dark border-bottom pb-3 mb-3">
                    <i class="fa-solid fa-sliders text-warning me-2"></i> Publishing Options
                </h5>

                <div class="form-check form-switch mb-3 ps-5">
                    <input class="form-check-input ms-n5" type="checkbox" role="switch" name="is_active" id="isActiveSwitch" <?= !isset($p['is_active']) || $p['is_active'] ? 'checked' : '' ?> style="width: 2.5em; height: 1.3em;">
                    <label class="form-check-label fw-bold text-dark ms-2" for="isActiveSwitch">
                        Publish on Website
                    </label>
                    <small class="text-secondary d-block ms-2">Visible to public visitors.</small>
                </div>

                <div class="form-check form-switch mb-4 ps-5">
                    <input class="form-check-input ms-n5" type="checkbox" role="switch" name="featured" id="isFeaturedSwitch" <?= !empty($p['featured']) ? 'checked' : '' ?> style="width: 2.5em; height: 1.3em;">
                    <label class="form-check-label fw-bold text-dark ms-2" for="isFeaturedSwitch">
                        Featured Highlight
                    </label>
                    <small class="text-secondary d-block ms-2">Shown prominently on Homepage.</small>
                </div>

                <div class="d-grid gap-2 pt-2 border-top">
                    <button type="submit" class="btn-adm-gold w-100 justify-content-center py-3">
                        <i class="fa-solid fa-floppy-disk me-1"></i> <?= $id ? 'Save Project Changes' : 'Publish New Project' ?>
                    </button>
                    <a href="projects.php" class="btn btn-outline-secondary rounded-pill py-2 text-center text-decoration-none">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
// Live Image Preview Handler
const imgInput = document.getElementById('projectImageInput');
const previewContainer = document.getElementById('imagePreviewContainer');
if (imgInput && previewContainer) {
    imgInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.innerHTML = '<img src="' + e.target.result + '" class="w-100 rounded-3 shadow-sm" style="max-height: 220px; object-fit: cover;">';
            };
            reader.readAsDataURL(file);
        }
    });
}

// Auto-fill slug from title if empty
const titleInput = document.getElementById('projectTitleInput');
const slugInput = document.getElementById('projectSlugInput');
if (titleInput && slugInput) {
    titleInput.addEventListener('blur', function() {
        if (!slugInput.value.trim()) {
            slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        }
    });
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>