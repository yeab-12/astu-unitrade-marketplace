<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Fetch categories
$stmt = $pdo->query("SELECT * FROM categories LIMIT 6");
$categories = $stmt->fetchAll();

// Fetch latest approved items
$stmt = $pdo->query("SELECT i.*, c.name as category_name, u.full_name as seller_name 
                     FROM items i 
                     JOIN categories c ON i.category_id = c.id 
                     JOIN users u ON i.user_id = u.id 
                     WHERE i.status = 'approved' 
                     ORDER BY i.created_at DESC LIMIT 8");
$featured_items = $stmt->fetchAll();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container hero-content text-center py-5">
        <span class="badge bg-accent px-3 py-2 rounded-pill mb-3 text-uppercase fw-bold letter-spacing-1">Built for ASTU students</span>
        <h1 class="display-3 fw-bold mb-4">The student marketplace for<br>Adama Science & Tech</h1>
        <p class="lead mb-5 mx-auto" style="max-width: 800px;">
            Buy, sell and exchange electronics, stationary materials, clothes, shoes and food and beverage with verified students — priced in Ethiopian Birr, delivered through Telegram.
        </p>
        
        <div class="d-flex justify-content-center gap-3 mb-5">
            <a href="marketplace.php" class="btn btn-primary btn-lg rounded-pill px-5 fw-semibold shadow-lg">Browse Marketplace</a>
            <?php if (isLoggedIn()): ?>
                <a href="sell.php" class="btn btn-light btn-lg rounded-pill px-5 fw-semibold shadow-lg text-primary">Sell an Item</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-light btn-lg rounded-pill px-5 fw-semibold shadow-lg text-primary">Login to Sell</a>
            <?php endif; ?>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="marketplace.php" method="GET" class="search-bar-wrapper d-flex align-items-center">
                    <i class="bi bi-search ms-3 text-muted fs-5"></i>
                    <input type="text" name="search" class="form-control form-control-lg bg-transparent border-0" placeholder="Search laptops, books, jackets...">
                    <button type="submit" class="btn btn-accent rounded-pill px-4 ms-2">Search</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Browse Categories</h2>
            <p class="text-muted">Find exactly what you need on campus</p>
        </div>
        <div class="row g-4">
            <?php foreach ($categories as $cat): ?>
            <div class="col-md-4 col-sm-6">
                <a href="marketplace.php?category=<?= $cat['id'] ?>" class="text-decoration-none">
                    <div class="card glass-card h-100 border-0 bg-light hover-lift text-center category-card overflow-hidden">
                        <div class="category-img-wrapper">
                            <?php if (!empty($cat['image'])): ?>
                                <img src="uploads/categories/<?= htmlspecialchars($cat['image']) ?>" 
                                     alt="<?= htmlspecialchars($cat['name']) ?>" 
                                     class="category-img">
                            <?php else: ?>
                                <div class="category-icon-fallback">
                                    <i class="bi <?= htmlspecialchars($cat['icon']) ?>"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body pt-3">
                            <h4 class="card-title text-dark fw-bold"><?= htmlspecialchars($cat['name']) ?></h4>
                            <p class="text-muted mb-0 small"><?= htmlspecialchars($cat['description']) ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Items Section -->
<section class="py-5">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold">Featured Items</h2>
                <p class="text-muted mb-0">Latest approved listings from ASTU students</p>
            </div>
            <a href="marketplace.php" class="btn btn-outline-primary rounded-pill px-4">View All</a>
        </div>
        
        <div class="row g-4">
            <?php foreach ($featured_items as $item): ?>
            <div class="col-md-3 col-sm-6">
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
                            <div class="small text-muted">
                                <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($item['seller_name']) ?>
                            </div>
                            <a href="item.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-primary rounded-pill px-3">View</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How it Works Section -->
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">How UniTrade Works</h2>
            <p class="text-muted">Simple, safe, and built for students</p>
        </div>
        
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4 hover-lift">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-cloud-arrow-up display-5 text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Step 1: Upload your item</h4>
                    <p class="text-muted">Snap a photo, add a price in ETB, write a quick description and submit.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 hover-lift">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-check display-5 text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Step 2: Admin approval</h4>
                    <p class="text-muted">Our team reviews each listing to keep UniTrade safe and trustworthy.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 hover-lift">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-telegram display-5 text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Step 3: Sell via Telegram</h4>
                    <p class="text-muted">Buyers contact you directly on Telegram. Meet on campus, exchange, done.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
