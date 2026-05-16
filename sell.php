<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

$user_id = getCurrentUserId();
$error = '';
$success = '';

// Fetch categories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category_id = $_POST['category'] ?? '';
    $condition = $_POST['condition'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = trim($_POST['description'] ?? '');
    
    // File upload
    $image = $_FILES['image'] ?? null;
    $imageName = 'default-placeholder.jpg';

    if (!$title || !$category_id || !$condition || !$price || !$description || !$image || $image['error'] !== UPLOAD_ERR_OK) {
        $error = "Please fill in all fields and upload a valid image.";
    } else {
        // Handle image upload
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            $error = "Only JPG, PNG, and WEBP images are allowed.";
        } else {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $imageName = uniqid('item_') . '.' . $fileExtension;
            $uploadPath = $uploadDir . $imageName;
            
            if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
                // Insert item (default status is pending in DB schema)
                $stmt = $pdo->prepare("INSERT INTO items (user_id, category_id, title, description, price, condition_type, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
                if ($stmt->execute([$user_id, $category_id, $title, $description, $price, $condition, $imageName])) {
                    $success = "Item uploaded successfully! It is pending admin approval.";
                } else {
                    $error = "Failed to save item in database.";
                }
            } else {
                $error = "Failed to upload image.";
            }
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container py-5 mt-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card glass-card border-0 p-5 shadow-lg">
                <div class="text-center mb-5">
                    <h2 class="fw-bold">Sell an Item</h2>
                    <p class="text-muted">Post your item for other ASTU students to see.</p>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success rounded-3 d-flex align-items-center">
                        <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Success!</strong><br><?= $success ?>
                            <div class="mt-2"><a href="profile.php" class="btn btn-sm btn-success rounded-pill">View My Listings</a></div>
                        </div>
                    </div>
                <?php else: ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger rounded-3"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Item Title</label>
                                <input type="text" class="form-control form-control-lg bg-light" name="title" placeholder="e.g., HP Core i5 Laptop" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category</label>
                                <select class="form-select form-select-lg bg-light" name="category" required>
                                    <option value="" disabled selected>Select Category...</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Condition</label>
                                <select class="form-select form-select-lg bg-light" name="condition" required>
                                    <option value="" disabled selected>Select Condition...</option>
                                    <option value="New">New</option>
                                    <option value="Like New">Like New</option>
                                    <option value="Used">Used</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price (ETB)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0">ETB</span>
                                    <input type="number" class="form-control bg-light border-start-0" name="price" step="0.01" min="0" placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Upload Image</label>
                                <input class="form-control form-control-lg bg-light" type="file" id="image" name="image" accept="image/*" required>
                                <div class="mt-3 text-center">
                                    <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded-3 shadow-sm d-none" style="max-height: 150px;">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea class="form-control form-control-lg bg-light" name="description" rows="5" placeholder="Describe the item, features, any defects..." required></textarea>
                            </div>
                            
                            <div class="col-12 mt-5">
                                <div class="alert alert-info bg-primary bg-opacity-10 border-0 rounded-3 small d-flex">
                                    <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                                    <div>All listings require admin approval before appearing in the marketplace. Please ensure your images and descriptions are clear and accurate.</div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">Submit for Approval</button>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
