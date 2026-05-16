<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['is_verified'] = $user['is_verified'];
            $_SESSION['full_name'] = $user['full_name'];
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}

require_once 'includes/header.php';
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card glass-card border-0 p-5 shadow-lg text-center">
                <div class="mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-person-fill text-primary fs-2"></i>
                    </div>
                    <h3 class="fw-bold">Welcome Back</h3>
                    <p class="text-muted">Login to your UniTrade account</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger rounded-3 small"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" action="" class="text-start">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Gmail Address</label>
                        <input type="email" class="form-control form-control-lg bg-light" id="email" name="email" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control form-control-lg bg-light" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill mb-3">Login</button>
                    
                    <div class="text-center mt-3">
                        <p class="text-muted small">Don't have an account? <a href="signup.php" class="text-decoration-none fw-bold text-accent">Sign up here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
