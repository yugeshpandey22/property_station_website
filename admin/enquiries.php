<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';
require_admin();

$msg = '';

// Handle Status Update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['update_status'])) {
    verify_csrf();
    $id = (int)($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? 'New';
    if ($id && in_array($status, ['New', 'Contacted', 'Closed'], true)) {
        $stmt = $pdo->prepare('UPDATE enquiries SET status = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
        $msg = 'Lead status updated to ' . $status . '.';
    }
}

// Handle Delete Lead
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['delete_enquiry'])) {
    verify_csrf();
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        $stmt = $pdo->prepare('DELETE FROM enquiries WHERE id = ?');
        $stmt->execute([$id]);
        $msg = 'Lead record deleted successfully.';
    }
}

// Filter by Status & Search
$statusFilter = trim($_GET['status'] ?? '');
$searchQuery = trim($_GET['q'] ?? '');

$sql = 'SELECT e.*, p.title AS project_title, p.slug AS project_slug FROM enquiries e LEFT JOIN projects p ON p.id = e.project_id WHERE 1=1';
$params = [];

if ($statusFilter && in_array($statusFilter, ['New', 'Contacted', 'Closed'], true)) {
    $sql .= ' AND e.status = ?';
    $params[] = $statusFilter;
}

if ($searchQuery) {
    $sql .= ' AND (e.name LIKE ? OR e.phone LIKE ? OR e.email LIKE ? OR e.message LIKE ?)';
    $like = '%' . $searchQuery . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

$sql .= ' ORDER BY e.created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

// Counts for filter badges
$totalCount = (int)$pdo->query('SELECT COUNT(*) FROM enquiries')->fetchColumn();
$newCount = (int)$pdo->query("SELECT COUNT(*) FROM enquiries WHERE status = 'New'")->fetchColumn();
$contactedCount = (int)$pdo->query("SELECT COUNT(*) FROM enquiries WHERE status = 'Contacted'")->fetchColumn();
$closedCount = (int)$pdo->query("SELECT COUNT(*) FROM enquiries WHERE status = 'Closed'")->fetchColumn();

$pageTitle = 'Customer Enquiries & Leads';
$activeNav = 'enquiries';
require_once __DIR__ . '/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="font-size: 1.75rem; color: #0f172a;">Customer Leads & Enquiries</h2>
        <p class="text-secondary mb-0">Follow up with prospective home buyers, schedule site visits, and close transactions.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <form method="GET" class="d-flex gap-2">
            <?php if ($statusFilter): ?>
                <input type="hidden" name="status" value="<?= e($statusFilter) ?>">
            <?php endif; ?>
            <div class="input-group">
                <input type="text" name="q" class="form-control form-control-sm rounded-start-pill px-3" placeholder="Search name, phone..." value="<?= e($searchQuery) ?>" style="min-width: 200px;">
                <button class="btn btn-sm btn-dark rounded-end-pill px-3" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
            <?php if ($searchQuery || $statusFilter): ?>
                <a href="enquiries.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="Clear Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php if ($msg): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="fa-solid fa-circle-check fs-5"></i>
        <div><?= e($msg) ?></div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Status Filter Pills -->
<div class="d-flex flex-wrap align-items-center gap-2 mb-4">
    <a href="enquiries.php<?= $searchQuery ? '?q=' . urlencode($searchQuery) : '' ?>" class="btn btn-sm <?= empty($statusFilter) ? 'btn-dark' : 'btn-outline-secondary' ?> rounded-pill px-3 py-2 fw-semibold">
        All Leads (<?= $totalCount ?>)
    </a>
    <a href="enquiries.php?status=New<?= $searchQuery ? '&q=' . urlencode($searchQuery) : '' ?>" class="btn btn-sm <?= $statusFilter === 'New' ? 'btn-danger' : 'btn-outline-danger' ?> rounded-pill px-3 py-2 fw-semibold">
        🔴 New (<?= $newCount ?>)
    </a>
    <a href="enquiries.php?status=Contacted<?= $searchQuery ? '&q=' . urlencode($searchQuery) : '' ?>" class="btn btn-sm <?= $statusFilter === 'Contacted' ? 'btn-primary' : 'btn-outline-primary' ?> rounded-pill px-3 py-2 fw-semibold">
        🔵 Contacted (<?= $contactedCount ?>)
    </a>
    <a href="enquiries.php?status=Closed<?= $searchQuery ? '&q=' . urlencode($searchQuery) : '' ?>" class="btn btn-sm <?= $statusFilter === 'Closed' ? 'btn-success' : 'btn-outline-success' ?> rounded-pill px-3 py-2 fw-semibold">
        🟢 Closed (<?= $closedCount ?>)
    </a>
</div>

<!-- Enquiries Table -->
<div class="admin-table-wrap">
    <div class="table-responsive">
        <table class="table admin-table align-middle">
            <thead>
                <tr>
                    <th style="width: 140px;">Received</th>
                    <th>Client Details</th>
                    <th>Quick Contact</th>
                    <th>Interest / Budget</th>
                    <th style="min-width: 220px;">Client Message</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                    <?php
                    // Clean phone for WhatsApp link
                    $cleanPhone = preg_replace('/[^0-9]/', '', $r['phone']);
                    if (strlen($cleanPhone) === 10) {
                        $waPhone = '91' . $cleanPhone;
                    } else {
                        $waPhone = $cleanPhone;
                    }
                    ?>
                    <tr>
                        <td>
                            <strong class="text-dark d-block small"><?= date('d M, Y', strtotime($r['created_at'])) ?></strong>
                            <small class="text-secondary"><?= date('h:i A', strtotime($r['created_at'])) ?></small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="sidebar-avatar" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                    <?= strtoupper(substr($r['name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <strong class="text-dark fs-6 d-block"><?= e($r['name']) ?></strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a href="tel:<?= e($r['phone']) ?>" class="btn btn-sm btn-outline-dark rounded-circle" style="width: 32px; height: 32px; display: grid; place-items: center;" title="Call: <?= e($r['phone']) ?>">
                                    <i class="fa-solid fa-phone"></i>
                                </a>
                                <?php if ($waPhone): ?>
                                    <a href="https://wa.me/<?= e($waPhone) ?>?text=<?= urlencode('Hello ' . $r['name'] . ', thank you for reaching out to Property Station.') ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-circle" style="width: 32px; height: 32px; display: grid; place-items: center;" title="Chat on WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if ($r['email']): ?>
                                    <a href="mailto:<?= e($r['email']) ?>" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; display: grid; place-items: center;" title="Email: <?= e($r['email']) ?>">
                                        <i class="fa-solid fa-envelope"></i>
                                    </a>
                                <?php endif; ?>
                                <span class="small fw-bold text-dark ms-1"><?= e($r['phone']) ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1 fw-semibold d-inline-block text-truncate" style="max-width: 170px;">
                                <?= e($r['project_title'] ?? 'General Inquiry') ?>
                            </span>
                            <?php if ($r['budget']): ?>
                                <small class="d-block text-success fw-bold mt-1">
                                    <i class="fa-solid fa-wallet me-1"></i> <?= e($r['budget']) ?>
                                </small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($r['message'])): ?>
                                <p class="mb-0 text-secondary small" style="line-height: 1.5;">
                                    <?= nl2br(e($r['message'])) ?>
                                </p>
                            <?php else: ?>
                                <span class="text-muted small fst-italic">No additional note provided.</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <input type="hidden" name="update_status" value="1">
                                <select name="status" class="form-select form-select-sm status-pill <?= $r['status'] === 'New' ? 'status-pill-new' : ($r['status'] === 'Contacted' ? 'status-pill-contacted' : 'status-pill-closed') ?>" onchange="this.form.submit()" style="cursor: pointer;">
                                    <option value="New" <?= $r['status'] === 'New' ? 'selected' : '' ?>>🔴 New</option>
                                    <option value="Contacted" <?= $r['status'] === 'Contacted' ? 'selected' : '' ?>>🔵 Contacted</option>
                                    <option value="Closed" <?= $r['status'] === 'Closed' ? 'selected' : '' ?>>🟢 Closed</option>
                                </select>
                            </form>
                        </td>
                        <td class="text-end">
                            <form method="POST" class="d-inline" onsubmit="return confirm('Delete enquiry record for <?= e(addslashes($r['name'])) ?>?')">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <input type="hidden" name="delete_enquiry" value="1">
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; display: inline-grid; place-items: center;" title="Delete Record">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-secondary">
                            <i class="fa-regular fa-inbox fs-1 d-block mb-3 text-muted"></i>
                            No enquiry records found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>