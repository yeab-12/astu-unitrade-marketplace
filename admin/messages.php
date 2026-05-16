<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireAdmin();

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_msg'])) {
        $msg_id = $_POST['msg_id'];
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        if ($stmt->execute([$msg_id])) $success = "Message deleted.";
    }
}

// Fetch messages
$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll();

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
                        <a class="nav-link text-dark hover-opacity-100 opacity-75" href="users.php">
                            <i class="bi bi-people me-2"></i> Manage Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary fw-bold bg-primary bg-opacity-10 rounded-3" href="messages.php">
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
                    <h2 class="fw-bold mb-0">Contact Messages</h2>
                    <p class="text-muted">Messages from the contact form.</p>
                </div>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success rounded-3"><i class="bi bi-check-circle me-2"></i><?= $success ?></div>
            <?php endif; ?>

            <div class="row g-4">
                <?php foreach ($messages as $msg): ?>
                <div class="col-md-6">
                    <div class="card glass-card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($msg['name']) ?></h6>
                                    <div class="text-muted small"><a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a></div>
                                </div>
                                <div class="text-muted small"><?= date('M d, Y H:i', strtotime($msg['created_at'])) ?></div>
                            </div>
                            
                            <div class="bg-light p-3 rounded-3 mb-3">
                                <p class="mb-0" style="white-space: pre-wrap;"><?= htmlspecialchars($msg['message']) ?></p>
                            </div>
                            
                            <form method="POST" action="" class="text-end" onsubmit="return confirm('Delete this message?');">
                                <input type="hidden" name="msg_id" value="<?= $msg['id'] ?>">
                                <button type="submit" name="delete_msg" class="btn btn-sm btn-outline-danger rounded-pill px-4">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (count($messages) === 0): ?>
                <div class="col-12 text-center py-5">
                    <div class="display-3 text-muted mb-3"><i class="bi bi-envelope-open"></i></div>
                    <h4 class="fw-bold text-muted">No messages</h4>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
