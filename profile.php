<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

$user_id = getCurrentUserId();
$success = '';
$error = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $telegram = trim($_POST['telegram_username']);
    $phone = trim($_POST['phone']);

    if (strpos($telegram, '@') !== 0) {
        $error = "Telegram username must start with @.";
    } elseif (!preg_match('/^\+2519\d{8}$/', $phone)) {
        $error = "Phone must be in +2519XXXXXXXX format.";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET telegram_username = ?, phone = ? WHERE id = ?");
        if ($stmt->execute([$telegram, $phone, $user_id])) {
            $success = "Profile updated successfully.";
        } else {
            $error = "Failed to update profile.";
        }
    }
}

// Handle item deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $item_id = $_POST['item_id'];
    $stmt = $pdo->prepare("DELETE FROM items WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$item_id, $user_id])) {
        $success = "Item deleted successfully.";
    } else {
        $error = "Failed to delete item.";
    }
}

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    // Session is stale or user was deleted — force re-login
    session_destroy();
    header("Location: login.php");
    exit();
}

// Fetch user items
$stmt = $pdo->prepare("SELECT i.*, c.name as category_name 
                       FROM items i 
                       JOIN categories c ON i.category_id = c.id 
                       WHERE i.user_id = ? ORDER BY i.created_at DESC");
$stmt->execute([$user_id]);
$my_items = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<div class="container py-5 mt-4">
    <div class="row g-5">
        <!-- Profile Sidebar -->
        <div class="col-lg-4">
            <div class="card glass-card border-0 p-4 shadow-sm text-center">
                <div class="position-relative mx-auto mb-4" style="width: 120px; height: 120px;">
                    <div class="bg-primary bg-opacity-10 rounded-circle w-100 h-100 d-flex align-items-center justify-content-center border border-3 border-white shadow-sm">
                        <i class="bi bi-person-fill text-primary" style="font-size: 4rem;"></i>
                    </div>
                </div>
                
                <h4 class="fw-bold mb-1"><?= htmlspecialchars($user['full_name']) ?></h4>
                <p class="text-muted mb-3"><?= htmlspecialchars($user['department']) ?> Student</p>
                
                <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-4">
                    <i class="bi bi-check-circle-fill me-1"></i> Verified Student
                </div>

                <ul class="list-group list-group-flush text-start mb-4">
                    <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                        <span class="text-muted">UGR ID</span>
                        <span class="fw-semibold"><?= htmlspecialchars($user['ugr_id']) ?></span>
                    </li>
                    <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                        <span class="text-muted">Joined</span>
                        <span class="fw-semibold"><?= date('M Y', strtotime($user['created_at'])) ?></span>
                    </li>
                </ul>

                <?php if ($success): ?>
                    <div class="alert alert-success rounded-3 small py-2"><i class="bi bi-check-circle me-1"></i><?= $success ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger rounded-3 small py-2"><i class="bi bi-exclamation-triangle me-1"></i><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <h6 class="fw-bold text-start mb-3">Edit Contact Info</h6>
                    <div class="mb-3 text-start">
                        <label class="form-label small text-muted">Telegram Username</label>
                        <input type="text" name="telegram_username" class="form-control bg-light" value="<?= htmlspecialchars($user['telegram_username']) ?>" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label small text-muted">Phone Number</label>
                        <input type="text" name="phone" class="form-control bg-light" value="<?= htmlspecialchars($user['phone']) ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-outline-primary rounded-pill w-100">Update Profile</button>
                </form>
            </div>
        </div>

        <!-- My Listings -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">My Listings</h3>
                <a href="sell.php" class="btn btn-primary rounded-pill px-4"><i class="bi bi-plus-lg me-1"></i>Post New Item</a>
            </div>

            <div class="row g-4">
                <?php if (count($my_items) > 0): ?>
                    <?php foreach ($my_items as $item): ?>
                    <div class="col-md-6">
                        <div class="card item-card h-100 shadow-sm">
                            <div class="position-relative">
                                <img src="uploads/<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>" onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'" style="height: 180px;">
                                
                                <?php if ($item['status'] === 'approved'): ?>
                                    <span class="badge bg-success position-absolute top-0 start-0 m-2 px-3 py-2 rounded-pill shadow-sm">Approved</span>
                                <?php elseif ($item['status'] === 'pending'): ?>
                                    <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 px-3 py-2 rounded-pill shadow-sm">Pending</span>
                                <?php else: ?>
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-2 px-3 py-2 rounded-pill shadow-sm">Rejected</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-truncate mb-1"><?= htmlspecialchars($item['title']) ?></h5>
                                <h5 class="text-accent fw-bold mb-3">ETB <?= number_format($item['price'], 2) ?></h5>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                    <span class="text-muted small"><i class="bi bi-clock me-1"></i><?= date('M d, Y', strtotime($item['created_at'])) ?></span>
                                    
                                    <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                        <button type="submit" name="delete_item" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5 glass-card">
                        <div class="display-3 text-muted mb-3"><i class="bi bi-box-seam"></i></div>
                        <h4 class="fw-bold">No items listed yet</h4>
                        <p class="text-muted">Start selling your items to other students.</p>
                        <a href="sell.php" class="btn btn-primary rounded-pill mt-2">Post your first item</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
