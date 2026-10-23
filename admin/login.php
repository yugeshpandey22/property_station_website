<?php
declare(strict_types=1);
require_once __DIR__ . '/../config.php';

if (is_admin()) {
    header('Location: index.php');
    exit;
}

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    $stmt = $pdo->prepare('SELECT * FROM admins WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header('Location: index.php');
        exit;
    }
    $error = 'Invalid email address or password. Please try again.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In | Property Station</title>
    <link rel="icon" href="../assets/logo.jpeg" type="image/jpeg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Font Awesome 6 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin-style.css?v=<?= filemtime(__DIR__ . '/admin-style.css') ?>">
</head>
<body>

    <div class="admin-login-wrapper">
        <div class="admin-login-box">
            <div class="login-brand-header">
                <a href="../index.php">
                    <img src="../assets/logo.jpeg" alt="Property Station Logo">
                </a>
                <h2>Property Station</h2>
                <p>Ultra-Luxury Management Console</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 py-2 px-3 rounded-3" role="alert" style="font-size: 0.9rem;">
                    <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
                    <div><?= e($error) ?></div>
                </div>
            <?php endif; ?>

            <form method="POST" autocomplete="on">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

                <div class="login-input-group">
                    <label for="adminEmail"><i class="fa-solid fa-envelope me-1 text-warning"></i> Admin Email</label>
                    <input type="email" name="email" id="adminEmail" class="login-input-field" placeholder="admin@propertystation.in" required autocomplete="username" value="<?= e($_POST['email'] ?? '') ?>">
                </div>

                <div class="login-input-group">
                    <label for="adminPassword"><i class="fa-solid fa-lock me-1 text-warning"></i> Secure Password</label>
                    <div class="position-relative">
                        <input type="password" name="password" id="adminPassword" class="login-input-field pe-5" placeholder="••••••••••••" required autocomplete="current-password">
                        <button type="button" id="togglePasswordBtn" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-secondary text-decoration-none pe-3" style="font-size: 1.1rem;" aria-label="Toggle password visibility">
                            <i class="fa-regular fa-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn-adm-gold w-100 justify-content-center py-3 fs-6">
                        <i class="fa-solid fa-shield-halved me-1"></i> Sign In to Console
                    </button>
                </div>

                <div class="text-center mt-4 pt-2 border-top border-secondary border-opacity-25">
                    <a href="../index.php" class="text-secondary text-decoration-none small d-inline-flex align-items-center gap-1 hover-gold">
                        <i class="fa-solid fa-arrow-left"></i> Return to Public Website
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Password visibility toggle
    const toggleBtn = document.getElementById("togglePasswordBtn");
    const passwordInput = document.getElementById("adminPassword");
    const toggleIcon = document.getElementById("togglePasswordIcon");
    if (toggleBtn && passwordInput && toggleIcon) {
        toggleBtn.addEventListener("click", () => {
            const isPassword = passwordInput.getAttribute("type") === "password";
            passwordInput.setAttribute("type", isPassword ? "text" : "password");
            toggleIcon.className = isPassword ? "fa-regular fa-eye-slash text-warning" : "fa-regular fa-eye text-secondary";
        });
    }
    </script>
</body>
</html>