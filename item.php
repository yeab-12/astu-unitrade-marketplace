<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: marketplace.php");
    exit();
}

$stmt = $pdo->prepare("SELECT i.*, c.name as category_name, u.full_name as seller_name, u.telegram_username, u.department, u.created_at as joined_date 
                       FROM items i 
                       JOIN categories c ON i.category_id = c.id 
                       JOIN users u ON i.user_id = u.id 
                       WHERE i.id = ? AND i.status = 'approved'");
$stmt->execute([$_GET['id']]);
$item = $stmt->fetch();

if (!$item) {
    echo "<div class='container py-5 text-center'><h3>Item not found or pending approval.</h3></div>";
    require_once 'includes/footer.php';
    exit();
}

// Make sure telegram link is correctly formatted
$telegramLink = $item['telegram_username'];
if (strpos($telegramLink, '@') === 0) {
    $telegramLink = 'https://t.me/' . substr($telegramLink, 1);
} else {
    $telegramLink = 'https://t.me/' . $telegramLink;
}
?>

<div class="container py-5 mt-3">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="marketplace.php" class="text-decoration-none">Marketplace</a></li>
            <li class="breadcrumb-item"><a href="marketplace.php?category=<?= $item['category_id'] ?>" class="text-decoration-none"><?= htmlspecialchars($item['category_name']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($item['title']) ?></li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Item Image -->
        <div class="col-md-7">
            <div class="card border-0 rounded-4 overflow-hidden shadow-sm">
                <img src="uploads/<?= htmlspecialchars($item['image']) ?>" class="img-fluid w-100" style="object-fit: cover; max-height: 500px;" alt="<?= htmlspecialchars($item['title']) ?>" onerror="this.src='https://via.placeholder.com/800x600?text=No+Image'">
            </div>
        </div>

        <!-- Item Details -->
        <div class="col-md-5">
            <div class="card glass-card border-0 p-4 p-md-5 h-100 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold">
                        <?= htmlspecialchars($item['category_name']) ?>
                    </span>
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                        Condition: <?= htmlspecialchars($item['condition_type']) ?>
                    </span>
                </div>
                
                <h2 class="fw-bold mb-3"><?= htmlspecialchars($item['title']) ?></h2>
                <h1 class="text-accent fw-bold mb-4">ETB <?= number_format($item['price'], 2) ?></h1>
                
                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase small text-muted">Description</h6>
                    <p class="mb-0 lh-lg"><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                </div>

                <hr class="text-muted my-4">

                <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="bi bi-person-fill text-primary fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0"><?= htmlspecialchars($item['seller_name']) ?></h6>
                        <small class="text-muted"><?= htmlspecialchars($item['department']) ?> Student &middot; Joined <?= date('M Y', strtotime($item['joined_date'])) ?></small>
                    </div>
                </div>

                <?php if (isLoggedIn()): ?>
                    <a href="<?= $telegramLink ?>" target="_blank" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm d-flex align-items-center justify-content-center">
                        <i class="bi bi-telegram fs-5 me-2"></i> Contact on Telegram
                    </a>
                <?php else: ?>
                    <div class="alert alert-warning text-center rounded-3">
                        <i class="bi bi-info-circle-fill me-2"></i>Please <a href="login.php" class="fw-bold text-dark">Login</a> to contact the seller.
                    </div>
                <?php endif; ?>
                
                <p class="text-center text-muted small mt-3">
                    <i class="bi bi-shield-check text-success me-1"></i> Meet on campus in safe public areas.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
