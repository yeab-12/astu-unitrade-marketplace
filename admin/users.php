<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireAdmin();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $u_id = $_POST['user_id'];
        $action = $_POST['action'];
        
        if ($action === 'verify') {
            $stmt = $pdo->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
            if ($stmt->execute([$u_id])) $success = "User verified successfully.";
        } elseif ($action === 'unverify') {
            $stmt = $pdo->prepare("UPDATE users SET is_verified = 0 WHERE id = ?");
            if ($stmt->execute([$u_id])) $success = "User unverified.";
        }
    }
}

// Fetch users
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0 admin-sidebar d-none d-md-block">
            <div class="p-4">
                <h5 class="fw-bold text-muted text-uppercase mb-4">Admin Menu</h5>
                <ul class="nav flex-column gap-2">
                    <li class="nav-item">
                        <a class="nav-link text-dark hover-opacity-100 opacity-75" href="dashboard.php">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark hover-opacity-100 opacity-75" href="items.php">
                            <i class="bi bi-box-seam me-2"></i> Manage Items
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary fw-bold bg-primary bg-opacity-10 rounded-3" href="users.php">
                            <i class="bi bi-people me-2"></i> Manage Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark hover-opacity-100 opacity-75" href="messages.php">
                            <i class="bi bi-envelope me-2"></i> Contact Messages
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 py-5 px-4">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="fw-bold mb-0">Manage Users</h2>
                    <p class="text-muted">Verify students to allow them to sell items.</p>
                </div>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success rounded-3"><i class="bi bi-check-circle me-2"></i><?= $success ?></div>
            <?php endif; ?>

            <div class="card glass-card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Student</th>
                                    <th class="py-3">Contact</th>
                                    <th class="py-3">Role</th>
                                    <th class="py-3">Status</th>
                                    <th class="pe-4 py-3 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <?php foreach ($users as $u): ?>
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3 text-primary fw-bold" style="width: 40px; height: 40px;">
                                                <?= strtoupper(substr($u['full_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0"><?= htmlspecialchars($u['full_name']) ?></h6>
                                                <div class="text-muted small"><?= htmlspecialchars($u['ugr_id']) ?> &middot; <?= htmlspecialchars($u['department']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="small">
                                            <div><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($u['email']) ?></div>
                                            <div><i class="bi bi-telegram me-1 text-info"></i><?= htmlspecialchars($u['telegram_username']) ?></div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill px-3"><?= ucfirst($u['role']) ?></span>
                                    </td>
                                    <td class="py-3">
                                        <?php if ($u['is_verified']): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i>Verified</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">Unverified</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        <?php if ($u['id'] !== $_SESSION['user_id']): // Don't let admin modify themselves ?>
                                            <form method="POST" action="" class="d-inline-flex">
                                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                                <?php if (!$u['is_verified']): ?>
                                                    <button type="submit" name="action" value="verify" class="btn btn-sm btn-success rounded-pill px-3">Verify</button>
                                                <?php else: ?>
                                                    <button type="submit" name="action" value="unverify" class="btn btn-sm btn-outline-warning rounded-pill px-3">Unverify</button>
                                                <?php endif; ?>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
