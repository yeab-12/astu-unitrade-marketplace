<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireAdmin();

$success = '';
$error = '';
$item = null;

// Get item ID
$item_id = $_GET['id'] ?? null;
if (!$item_id) {
    header("Location: items.php");
    exit();
}

// Fetch item data
$stmt = $pdo->prepare("SELECT i.*, c.name as category_name, u.full_name as seller_name 
                       FROM items i 
                       JOIN categories c ON i.category_id = c.id 
                       JOIN users u ON i.user_id = u.id 
                       WHERE i.id = ?");
$stmt->execute([$item_id]);
$item = $stmt->fetch();

if (!$item) {
    header("Location: items.php");
    exit();
}

// Fetch categories for dropdown
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category_id = $_POST['category'] ?? '';
    $condition = $_POST['condition'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? $item['status'];

    if (!$title || !$category_id || !$condition || !$price || !$description) {
        $error = "Please fill in all fields.";
    } else {
        $imageName = $item['image']; // Keep existing image by default

        // Handle new image upload if provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                $error = "Only JPG, PNG, and WEBP images are allowed.";
            } else {
                $uploadDir = __DIR__ . '/../uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $imageName = uniqid('item_') . '.' . $fileExtension;
                $uploadPath = $uploadDir . $imageName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $error = "Failed to upload image.";
                    $imageName = $item['image']; // Revert to old image
                }
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("UPDATE items SET title = ?, category_id = ?, condition_type = ?, price = ?, description = ?, image = ?, status = ? WHERE id = ?");
            if ($stmt->execute([$title, $category_id, $condition, $price, $description, $imageName, $status, $item_id])) {
                $success = "Item updated successfully.";
                // Refresh item data
                $stmt = $pdo->prepare("SELECT i.*, c.name as category_name, u.full_name as seller_name 
                                       FROM items i 
                                       JOIN categories c ON i.category_id = c.id 
                                       JOIN users u ON i.user_id = u.id 
                                       WHERE i.id = ?");
                $stmt->execute([$item_id]);
                $item = $stmt->fetch();
            } else {
                $error = "Failed to update item.";
            }
        }
    }
}

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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Edit Item</h2>
                    <p class="text-muted mb-0">Update item details, price, image, and status.</p>
                </div>
                <a href="items.php" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-1"></i> Back to Items
                </a>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success rounded-3 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                    <div><strong>Success!</strong> <?= $success ?></div>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger rounded-3 d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                    <div><strong>Error!</strong> <?= $error ?></div>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <!-- Current Image Preview -->
                <div class="col-lg-4">
                    <div class="card glass-card border-0 shadow-sm overflow-hidden">
                        <img src="../uploads/<?= htmlspecialchars($item['image']) ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($item['title']) ?>"
                             onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'"
                             style="height: 250px; object-fit: cover;">
                        <div class="card-body text-center">
                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($item['title']) ?></h5>
                            <p class="text-muted small mb-2">by <?= htmlspecialchars($item['seller_name']) ?></p>
                            <h4 class="text-accent fw-bold mb-2">ETB <?= number_format($item['price'], 2) ?></h4>
                            <?php if ($item['status'] === 'approved'): ?>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Approved</span>
                            <?php elseif ($item['status'] === 'pending'): ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">Pending</span>
                            <?php else: ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">Rejected</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <div class="col-lg-8">
                    <div class="card glass-card border-0 shadow-sm p-4">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Item Title</label>
                                    <input type="text" class="form-control form-control-lg bg-light" 
                                           name="title" value="<?= htmlspecialchars($item['title']) ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Category</label>
                                    <select class="form-select form-select-lg bg-light" name="category" required>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $item['category_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Condition</label>
                                    <select class="form-select form-select-lg bg-light" name="condition" required>
                                        <option value="New" <?= $item['condition_type'] === 'New' ? 'selected' : '' ?>>New</option>
                                        <option value="Like New" <?= $item['condition_type'] === 'Like New' ? 'selected' : '' ?>>Like New</option>
                                        <option value="Used" <?= $item['condition_type'] === 'Used' ? 'selected' : '' ?>>Used</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Price (ETB)</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0 fw-bold">ETB</span>
                                        <input type="number" class="form-control bg-light border-start-0" 
                                               name="price" step="0.01" min="0" 
                                               value="<?= htmlspecialchars($item['price']) ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select class="form-select form-select-lg bg-light" name="status" required>
                                        <option value="pending" <?= $item['status'] === 'pending' ? 'selected' : '' ?>>⏳ Pending</option>
                                        <option value="approved" <?= $item['status'] === 'approved' ? 'selected' : '' ?>>✅ Approved</option>
                                        <option value="rejected" <?= $item['status'] === 'rejected' ? 'selected' : '' ?>>❌ Rejected</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Replace Image <span class="text-muted fw-normal">(optional — leave empty to keep current)</span></label>
                                    <input class="form-control form-control-lg bg-light" type="file" name="image" accept="image/*">
                                    <div class="mt-2">
                                        <img id="newImagePreview" src="#" alt="New Preview" class="img-fluid rounded-3 shadow-sm d-none" style="max-height: 120px;">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea class="form-control form-control-lg bg-light" name="description" rows="4" required><?= htmlspecialchars($item['description']) ?></textarea>
                                </div>

                                <div class="col-12 mt-4">
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm flex-grow-1">
                                            <i class="bi bi-check-lg me-2"></i>Save Changes
                                        </button>
                                        <a href="items.php" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Image upload preview
const imageInput = document.querySelector('input[name="image"]');
const preview = document.getElementById('newImagePreview');
if (imageInput && preview) {
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>
