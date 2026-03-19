<?php
// index.php – BrewMaster Home | ASB/2023/144
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Fetch 3 featured recipes for homepage cards
$featured = $conn->query("SELECT * FROM recipes ORDER BY created_at DESC LIMIT 3")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrewMaster – Digital Coffee Recipe Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<!-- ── Hero Section ────────────────────────────────────── -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Left: Headline -->
            <div class="col-lg-6">
                <h1 class="fade-up">Discover the<br><span>Perfect Brew</span></h1>
                <p class="mt-3 mb-4 fade-up-2">
                    Your digital home for coffee recipes. Explore Hot, Cold, Sweet &amp; Strong brews
                    crafted by coffee lovers around the world.
                </p>
                <div class="d-flex gap-3 flex-wrap fade-up-3">
                    <a href="recipes.php" class="bm-btn-primary">Explore Recipes</a>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="auth/register.php" class="bm-btn-outline">Join Free</a>
                    <?php else: ?>
                    <a href="dashboard.php" class="bm-btn-outline">My Dashboard</a>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Right: Image Carousel -->
            <div class="col-lg-6 fade-up-2">
                <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="slide-emoji">☕</div>
                            <h4>Hot Brews</h4>
                            <p>Espresso, Cappuccino, Latte &amp; more warming classics</p>
                        </div>
                        <div class="carousel-item">
                            <div class="slide-emoji">🧊</div>
                            <h4>Cold Brews</h4>
                            <p>Iced lattes, cold brew, and refreshing chilled coffees</p>
                        </div>
                        <div class="carousel-item">
                            <div class="slide-emoji">🍯</div>
                            <h4>Sweet Brews</h4>
                            <p>Caramel, mocha, honey &amp; indulgent sweet creations</p>
                        </div>
                        <div class="carousel-item">
                            <div class="slide-emoji">⚡</div>
                            <h4>Strong Brews</h4>
                            <p>Double shots, lungo &amp; bold blends for the brave</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── Featured Recipes ─────────────────────────────────── -->
<section class="section" id="featured">
    <div class="container">
        <h2 class="section-title animate-on-scroll">Featured Recipes</h2>
        <hr class="section-divider">
        <p class="section-subtitle">Hand-picked brews to get you started</p>

        <?php if (empty($featured)): ?>
            <div class="empty-state"><span class="empty-icon">☕</span>No recipes yet. <a href="auth/register.php">Sign up</a> to add the first!</div>
        <?php else: ?>
        <div class="row g-4" id="recipeGrid">
            <?php foreach ($featured as $r): ?>
            <div class="col-md-4 recipe-item" data-category="<?= $r['category'] ?>">
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
                        <small class="text-muted">Click to view recipe ↗</small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="recipes.php" class="bm-btn-dark">View All Recipes</a>
        </div>
    </div>
</section>

<!-- ── About Strip ──────────────────────────────────────── -->
<section class="about-strip" id="about">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h2 class="animate-on-scroll">About BrewMaster</h2>
                <hr class="section-divider">
                <p>BrewMaster is your digital companion for all things coffee. Whether you're a
                   home barista, a café owner, or simply a coffee lover, our platform lets you
                   explore hundreds of recipes and share your own signature brews.</p>
                <p class="mt-2">Filter by category, view step-by-step preparation guides, and
                   join a growing community of coffee enthusiasts.</p>
                <a href="auth/register.php" class="bm-btn-dark mt-3 d-inline-block">Get Started</a>
            </div>
            <div class="col-lg-5 text-center" style="font-size:6rem; line-height:1;">
                ☕🍵⚡🧊
            </div>
        </div>
    </div>
</section>

<!-- ── Recipe Detail Modal ──────────────────────────────── -->
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
        <p>☕ BrewMaster – Digital Coffee Recipe Book &nbsp;|&nbsp; COM 2303 Web Design &nbsp;|&nbsp; ASB/2023/144</p>
        <p class="mt-1"><a href="contact.php">Contact</a> &nbsp;&middot;&nbsp; <a href="recipes.php">Recipes</a></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
