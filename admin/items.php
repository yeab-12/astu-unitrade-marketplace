<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireAdmin();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['item_id'])) {
        $item_id = $_POST['item_id'];
        $action = $_POST['action'];
        
        if ($action === 'approve') {
            $stmt = $pdo->prepare("UPDATE items SET status = 'approved' WHERE id = ?");
            if ($stmt->execute([$item_id])) $success = "Item approved successfully.";
        } elseif ($action === 'reject') {
            $stmt = $pdo->prepare("UPDATE items SET status = 'rejected' WHERE id = ?");
            if ($stmt->execute([$item_id])) $success = "Item rejected.";
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM items WHERE id = ?");
            if ($stmt->execute([$item_id])) $success = "Item deleted.";
        }
    }
}

// Fetch items
$status_filter = $_GET['status'] ?? '';
$where = '';
$params = [];
if ($status_filter && in_array($status_filter, ['pending', 'approved', 'rejected'])) {
    $where = "WHERE i.status = ?";
    $params[] = $status_filter;
}

$query = "SELECT i.*, u.full_name as seller_name, c.name as category_name 
          FROM items i 
          JOIN users u ON i.user_id = u.id 
          JOIN categories c ON i.category_id = c.id 
          $where 
          ORDER BY i.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$items = $stmt->fetchAll();

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
                        <a class="nav-link text-primary fw-bold bg-primary bg-opacity-10 rounded-3" href="items.php">
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
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="fw-bold mb-0">Manage Items</h2>
                    <p class="text-muted">Approve, reject, or delete student listings.</p>
                </div>
                
                <div class="btn-group shadow-sm">
                    <a href="items.php" class="btn btn-outline-primary <?= !$status_filter ? 'active' : '' ?>">All</a>
                    <a href="items.php?status=pending" class="btn btn-outline-warning text-dark <?= $status_filter === 'pending' ? 'active' : '' ?>">Pending</a>
                    <a href="items.php?status=approved" class="btn btn-outline-success <?= $status_filter === 'approved' ? 'active' : '' ?>">Approved</a>
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
                                    <th class="ps-4 py-3">Image</th>
                                    <th class="py-3">Details</th>
                                    <th class="py-3">Seller</th>
                                    <th class="py-3">Status</th>
                                    <th class="pe-4 py-3 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <?php foreach ($items as $item): ?>
                                <tr>
                                    <td class="ps-4 py-3" style="width: 100px;">
                                        <img src="../uploads/<?= htmlspecialchars($item['image']) ?>" alt="Item" class="rounded-3" style="width: 70px; height: 70px; object-fit: cover;">
                                    </td>
                                    <td class="py-3">
                                        <h6 class="fw-bold mb-1"><a href="../item.php?id=<?= $item['id'] ?>" target="_blank" class="text-dark text-decoration-none"><?= htmlspecialchars($item['title']) ?></a></h6>
                                        <div class="text-muted small"><?= htmlspecialchars($item['category_name']) ?> &middot; ETB <?= number_format($item['price'], 2) ?></div>
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-semibold small"><?= htmlspecialchars($item['seller_name']) ?></div>
                                    </td>
                                    <td class="py-3">
                                        <?php if ($item['status'] === 'approved'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Approved</span>
                                        <?php elseif ($item['status'] === 'pending'): ?>
                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="edit_item.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-primary rounded-pill" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            
                                            <form method="POST" action="" class="d-inline">
                                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                                
                                                <?php if ($item['status'] !== 'approved'): ?>
                                                    <button type="submit" name="action" value="approve" class="btn btn-sm btn-success rounded-pill" title="Approve"><i class="bi bi-check-lg"></i></button>
                                                <?php endif; ?>
                                                
                                                <?php if ($item['status'] !== 'rejected'): ?>
                                                    <button type="submit" name="action" value="reject" class="btn btn-sm btn-warning text-dark rounded-pill" title="Reject"><i class="bi bi-x-lg"></i></button>
                                                <?php endif; ?>
                                                
                                                <button type="submit" name="action" value="delete" class="btn btn-sm btn-danger rounded-pill" title="Delete" onclick="return confirm('Are you sure you want to delete this item?');"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                
                                <?php if (count($items) === 0): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No items found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
