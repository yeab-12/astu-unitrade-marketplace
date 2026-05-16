<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireAdmin();

// Get stats
$stats = [];
$stats['users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$stats['items'] = $pdo->query("SELECT COUNT(*) FROM items")->fetchColumn();
$stats['pending_items'] = $pdo->query("SELECT COUNT(*) FROM items WHERE status = 'pending'")->fetchColumn();
$stats['approved_items'] = $pdo->query("SELECT COUNT(*) FROM items WHERE status = 'approved'")->fetchColumn();

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
                        <a class="nav-link text-primary fw-bold bg-primary bg-opacity-10 rounded-3" href="dashboard.php">
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
                        <a class="nav-link text-dark hover-opacity-100 opacity-75" href="messages.php">
                            <i class="bi bi-envelope me-2"></i> Contact Messages
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 py-5 px-4">
            <h2 class="fw-bold mb-4">Dashboard Overview</h2>
            
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="card glass-card border-0 p-4 border-start border-primary border-4 shadow-sm">
                        <div class="text-muted small text-uppercase fw-bold mb-1">Total Users</div>
                        <h2 class="fw-bold text-primary mb-0"><?= number_format($stats['users']) ?></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card glass-card border-0 p-4 border-start border-info border-4 shadow-sm">
                        <div class="text-muted small text-uppercase fw-bold mb-1">Total Items</div>
                        <h2 class="fw-bold text-info mb-0"><?= number_format($stats['items']) ?></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card glass-card border-0 p-4 border-start border-success border-4 shadow-sm">
                        <div class="text-muted small text-uppercase fw-bold mb-1">Approved Items</div>
                        <h2 class="fw-bold text-success mb-0"><?= number_format($stats['approved_items']) ?></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card glass-card border-0 p-4 border-start border-warning border-4 shadow-sm">
                        <div class="text-muted small text-uppercase fw-bold mb-1">Pending Items</div>
                        <h2 class="fw-bold text-warning mb-0"><?= number_format($stats['pending_items']) ?></h2>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card glass-card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 pt-4 pb-0">
                            <h5 class="fw-bold">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <a href="items.php?status=pending" class="btn btn-warning text-dark text-start p-3 rounded-3 shadow-sm d-flex justify-content-between align-items-center">
                                    <span class="fw-bold"><i class="bi bi-clock-history me-2"></i> Review Pending Items</span>
                                    <span class="badge bg-dark rounded-pill"><?= $stats['pending_items'] ?></span>
                                </a>
                                <a href="users.php" class="btn btn-light border text-start p-3 rounded-3 shadow-sm d-flex justify-content-between align-items-center">
                                    <span class="fw-bold"><i class="bi bi-people me-2"></i> View All Users</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                                <a href="messages.php" class="btn btn-light border text-start p-3 rounded-3 shadow-sm d-flex justify-content-between align-items-center">
                                    <span class="fw-bold"><i class="bi bi-envelope me-2"></i> View Messages</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
