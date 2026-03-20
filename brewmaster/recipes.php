<?php
// recipes.php – All Recipes | BrewMaster | ASB/2023/144
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$recipes = $conn->query("SELECT r.*, u.username FROM recipes r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Recipes – BrewMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<section class="page-hero">
    <div class="container">
        <h1>☕ All Coffee Recipes</h1>
        <p>Browse, filter, and discover your next perfect brew</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <!-- Filter Buttons (Dynamic Content Updates) -->
        <div class="filter-btns">
            <button class="filter-btn active" data-filter="All">All</button>
            <button class="filter-btn" data-filter="Hot">☕ Hot</button>
            <button class="filter-btn" data-filter="Cold">🧊 Cold</button>
            <button class="filter-btn" data-filter="Sweet">🍯 Sweet</button>
            <button class="filter-btn" data-filter="Strong">⚡ Strong</button>
        </div>

        <?php if (empty($recipes)): ?>
            <div class="empty-state">
                <span class="empty-icon">☕</span>
                No recipes found. <a href="auth/register.php">Sign up</a> and add the first!
            </div>
        <?php else: ?>
        <div class="row g-4" id="recipeGrid">
            <?php foreach ($recipes as $r): ?>
            <div class="col-md-4 col-sm-6 recipe-item animate-on-scroll" data-category="<?= $r['category'] ?>">
                <div class="recipe-card"
                     data-bs-toggle="modal" data-bs-target="#recipeModal"
                     data-title="<?= htmlspecialchars($r['title']) ?>"
                     data-cat="<?= htmlspecialchars($r['category']) ?>"
                     data-time="<?= htmlspecialchars($r['brew_time'] ?? '') ?>"
                     data-ingr="<?= htmlspecialchars($r['ingredients']) ?>"
                     data-steps="<?= htmlspecialchars($r['instructions']) ?>"
                     data-emoji="<?= categoryEmoji($r['category']) ?>">
                    <div class="recipe-card-thumb"><?= categoryEmoji($r['category']) ?></div>
                    <div class="recipe-card-body">
                        <span class="bm-badge <?= categoryBadge($r['category']) ?>"><?= $r['category'] ?></span>
                        <h5><?= htmlspecialchars($r['title']) ?></h5>
                        <?php if ($r['brew_time']): ?><p class="brew-time">⏱ <?= htmlspecialchars($r['brew_time']) ?></p><?php endif; ?>
                        <p class="snippet"><?= htmlspecialchars(substr($r['ingredients'], 0, 70)) ?>...</p>
                        <small class="text-muted">By <?= htmlspecialchars($r['username']) ?> &bull; <?= date('M j, Y', strtotime($r['created_at'])) ?></small>
                        <br><small style="color:var(--caramel);">Click to view ↗</small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div id="emptyState" class="empty-state" style="display:none;">
            <span class="empty-icon">🔍</span>No recipes in this category yet.
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- Recipe Modal -->
<div class="modal fade" id="recipeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span id="modalEmoji" style="font-size:1.4rem;margin-right:.5rem;"></span>
                    <span id="modalTitle" class="fw-bold fs-5"></span>
                    &nbsp;<span id="modalCategory" class="bm-badge bg-secondary" style="font-size:.7rem;"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modalTime" class="text-muted mb-3"></p>
                <h6 class="fw-bold mb-2">Ingredients</h6>
                <ul id="modalIngredients" class="mb-3"></ul>
                <h6 class="fw-bold mb-2">Preparation Steps</h6>
                <ol id="modalSteps"></ol>
            </div>
        </div>
    </div>
</div>

<footer class="bm-footer">
    <div class="container text-center">
        <p>☕ BrewMaster – COM 2303 Web Design &nbsp;|&nbsp; ASB/2023/144</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
