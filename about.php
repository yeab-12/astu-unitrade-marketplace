<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<div class="container py-5 mt-4">
    <div class="row align-items-center mb-5">
        <div class="col-md-6 pe-md-5">
            <h1 class="display-4 fw-bold mb-4">About UniTrade</h1>
            <p class="lead text-muted mb-4">The premier marketplace built exclusively for Adama Science and Technology University students.</p>
            <p>UniTrade was born out of a simple need: a safe, reliable, and easy way for ASTU students to exchange goods on campus. Whether you are moving out of the dorms, upgrading your laptop, or just want to sell some textbooks, UniTrade connects you directly with your fellow students.</p>
            <p>Unlike public marketplaces, UniTrade requires a verified UGR ID to sell, ensuring that the community remains trustworthy and exclusive to ASTU.</p>
        </div>
        <div class="col-md-6">
            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="ASTU Campus" class="img-fluid rounded-4 shadow-lg hover-lift">
        </div>
    </div>

    <div class="row g-4 mt-5">
        <div class="col-md-4">
            <div class="card glass-card border-0 p-4 h-100 text-center hover-lift">
                <div class="display-4 text-accent mb-3"><i class="bi bi-eye"></i></div>
                <h4 class="fw-bold">Our Vision</h4>
                <p class="text-muted">To be the central hub for student commerce at ASTU, making resource sharing efficient and sustainable.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card glass-card border-0 p-4 h-100 text-center hover-lift">
                <div class="display-4 text-accent mb-3"><i class="bi bi-bullseye"></i></div>
                <h4 class="fw-bold">Our Mission</h4>
                <p class="text-muted">Provide a secure, elegant, and user-friendly platform where students can buy and sell items seamlessly via Telegram.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card glass-card border-0 p-4 h-100 text-center hover-lift">
                <div class="display-4 text-accent mb-3"><i class="bi bi-shield-lock"></i></div>
                <h4 class="fw-bold">Trust System</h4>
                <p class="text-muted">Every item is reviewed by our admin team before going live, and all sellers are verified ASTU students.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>