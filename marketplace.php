<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Fetch Categories for Sidebar
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();

// Filtering and Searching Logic
$whereClause = ["i.status = 'approved'"];
$params = [];

// Search
if (!empty($_GET['search'])) {
    $whereClause[] = "(i.title LIKE ? OR i.description LIKE ?)";
    $search = "%" . $_GET['search'] . "%";
    $params[] = $search;
    $params[] = $search;
}

// Category filter
if (!empty($_GET['category'])) {
    $whereClause[] = "i.category_id = ?";
    $params[] = $_GET['category'];
}

// Min Price
if (!empty($_GET['min_price'])) {
    $whereClause[] = "i.price >= ?";
    $params[] = $_GET['min_price'];
}

// Max Price
if (!empty($_GET['max_price'])) {
    $whereClause[] = "i.price <= ?";
    $params[] = $_GET['max_price'];
}

// Sorting
$orderBy = "ORDER BY i.created_at DESC";
if (!empty($_GET['sort'])) {
    if ($_GET['sort'] === 'price_asc') {
        $orderBy = "ORDER BY i.price ASC";
    } elseif ($_GET['sort'] === 'price_desc') {
        $orderBy = "ORDER BY i.price DESC";
    }
}

// Fetch Items
$whereSQL = implode(' AND ', $whereClause);
$query = "SELECT i.*, c.name as category_name, u.full_name as seller_name 
          FROM items i 
          JOIN categories c ON i.category_id = c.id 
          JOIN users u ON i.user_id = u.id 
          WHERE $whereSQL 
          $orderBy";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$items = $stmt->fetchAll();
?>

<div class="container py-4 mt-3">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Marketplace</h2>
            <p class="text-muted">Find exactly what you need.</p>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card glass-card border-0 p-4 sticky-top" style="top: 100px; z-index: 1;">
                <h5 class="fw-bold mb-3"><i class="bi bi-funnel me-2"></i>Filters</h5>
                <form action="marketplace.php" method="GET">
                    
                    <?php if (!empty($_GET['search'])): ?>
                        <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
                    <?php endif; ?>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Category</label>
                        <select name="category" class="form-select bg-light">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Price Range (ETB)</label>
                        <div class="d-flex gap-2">
                            <input type="number" name="min_price" class="form-control bg-light" placeholder="Min" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
                            <input type="number" name="max_price" class="form-control bg-light" placeholder="Max" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted text-uppercase">Sort By</label>
                        <select name="sort" class="form-select bg-light">
                            <option value="latest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'latest') ? 'selected' : '' ?>>Latest</option>
                            <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : '' ?>>Price: Low to High</option>
                            <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : '' ?>>Price: High to Low</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill">Apply Filters</button>
                        <a href="marketplace.php" class="btn btn-light rounded-pill text-muted">Clear Filters</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Items Grid -->
        <div class="col-lg-9">
            <?php if (!empty($_GET['search'])): ?>
                <div class="alert alert-info bg-primary bg-opacity-10 border-0 rounded-3 mb-4">
                    Showing results for: <strong><?= htmlspecialchars($_GET['search']) ?></strong>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <?php if (count($items) > 0): ?>
                    <?php foreach ($items as $item): ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="card item-card h-100 shadow-sm hover-lift">
                            <div class="position-relative">
                                <img src="uploads/<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>" onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                                <span class="condition-badge text-primary"><?= htmlspecialchars($item['condition_type']) ?></span>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="text-muted small mb-1"><?= htmlspecialchars($item['category_name']) ?></div>
                                <h5 class="card-title fw-bold mb-2 text-truncate"><?= htmlspecialchars($item['title']) ?></h5>
                                <h4 class="text-accent fw-bold mb-3">ETB <?= number_format($item['price'], 2) ?></h4>
                                
                                <div class="mt-auto d-flex align-items-center justify-content-between">
                                    <div class="small text-muted text-truncate w-50">
                                        <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($item['seller_name']) ?>
                                    </div>
                                    <a href="item.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-primary rounded-pill px-3">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="display-1 text-muted mb-3"><i class="bi bi-search"></i></div>
                        <h4 class="fw-bold">No items found</h4>
                        <p class="text-muted">Try adjusting your filters or search term.</p>
                        <a href="marketplace.php" class="btn btn-primary rounded-pill mt-3">Reset Search</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
