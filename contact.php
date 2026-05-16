<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $message) {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $message])) {
            $success = "Thank you for getting in touch! We'll respond shortly.";
        } else {
            $error = "Something went wrong. Please try again later.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<div class="container py-5 mt-4">
    <div class="row align-items-center mb-5">
        <div class="col-12 text-center mb-5">
            <h1 class="fw-bold">Get in touch</h1>
            <p class="text-muted">Questions, feedback or trouble with a listing? We're here to help.</p>
        </div>
    </div>

    <div class="row g-5">
        <div class="col-md-5">
            <div class="card glass-card border-0 p-5 h-100 shadow-sm">
                <h4 class="fw-bold mb-4">Contact Information</h4>
                
                <div class="d-flex align-items-start mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-envelope-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Email</h6>
                        <p class="text-muted mb-0">yeabsiragetachew613@gmail.com</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-start mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-telegram text-primary fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Telegram Support</h6>
                        <p class="text-muted mb-0"><a href="https://t.me/unitrade_support" class="text-decoration-none">t.me/unitrade_support</a></p>
                    </div>
                </div>
                
                <div class="d-flex align-items-start mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="bi bi-geo-alt-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Address</h6>
                        <p class="text-muted mb-0">Adama Science and Technology University<br>P.O. Box 1888, Adama, Ethiopia</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card glass-card border-0 p-5 shadow-sm">
                <h4 class="fw-bold mb-4">Send us a Message</h4>
                
                <?php if ($success): ?>
                    <div class="alert alert-success rounded-3"><i class="bi bi-check-circle-fill me-2"></i><?= $success ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger rounded-3"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Full Name</label>
                        <input type="text" class="form-control form-control-lg bg-light" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <input type="email" class="form-control form-control-lg bg-light" id="email" name="email" required>
                    </div>
                    <div class="mb-4">
                        <label for="message" class="form-label fw-semibold">Message</label>
                        <textarea class="form-control form-control-lg bg-light" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
