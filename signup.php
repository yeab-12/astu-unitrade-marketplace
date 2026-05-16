<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

$departments = ['SE', 'CSE', 'ECE', 'EPCE', 'CE', 'Mechanical', 'Material', 'Applied', 'Architecture', 'Water'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $telegram = trim($_POST['telegram_username'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $ugr_id = trim($_POST['ugr_id'] ?? '');

    // Server side validation
    if (strlen($password) < 8 || strlen($password) > 10) {
        $error = "Password must be between 8 and 10 characters.";
    } elseif (strpos($telegram, '@') !== 0) {
        $error = "Telegram username must start with @.";
    } elseif (!preg_match('/^\+2519\d{8}$/', $phone)) {
        $error = "Phone must be in +2519XXXXXXXX format.";
    } elseif (!preg_match('/^UGR\/\d{5}\/\d{2}$/', $ugr_id)) {
        $error = "UGR ID must be in UGR/XXXXX/XX format.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email is already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // In a real app, is_verified would be 0 until admin verifies. 
            // Setting to 1 for ease of testing based on the requirements.
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, telegram_username, phone, department, ugr_id, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
            if ($stmt->execute([$full_name, $email, $hashed_password, $telegram, $phone, $department, $ugr_id])) {
                $_SESSION['signup_success'] = true;
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card glass-card border-0 p-5 shadow-lg">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Create Account</h2>
                    <p class="text-muted">Join the ASTU student marketplace</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger rounded-3 small"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" action="" id="signupForm">
                    <div class="row g-3">
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" class="form-control bg-light" name="full_name" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-semibold">Gmail Address</label>
                            <input type="email" class="form-control bg-light" name="email" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-semibold">UGR ID</label>
                            <input type="text" class="form-control bg-light" id="ugr_id" name="ugr_id" placeholder="UGR/35214/15" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-semibold">Department</label>
                            <select class="form-select bg-light" name="department" required>
                                <option value="" disabled selected>Select Department...</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= $dept ?>"><?= $dept ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="text" class="form-control bg-light" id="phone" name="phone" placeholder="+2519XXXXXXXX" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-semibold">Telegram Username</label>
                            <input type="text" class="form-control bg-light" id="telegram_username" name="telegram_username" placeholder="@username" required>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-semibold">Password <span class="text-muted small fw-normal">(8-10 chars)</span></label>
                            <input type="password" class="form-control bg-light" id="password" name="password" minlength="8" maxlength="10" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill mb-3 mt-3">Sign Up</button>
                    
                    <div class="text-center mt-3">
                        <p class="text-muted small">Already have an account? <a href="login.php" class="text-decoration-none fw-bold text-accent">Login here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
