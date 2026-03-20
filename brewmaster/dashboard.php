<?php
// dashboard.php – User Dashboard | BrewMaster | ASB/2023/144
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

requireLogin();

$user_id  = $_SESSION['user_id'];
$username = $_SESSION['username'];
$msg      = '';

// ── Add Recipe ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $title        = sanitize($_POST['title']        ?? '');
    $category     = sanitize($_POST['category']     ?? 'Hot');
    $ingredients  = sanitize($_POST['ingredients']  ?? '');
    $instructions = sanitize($_POST['instructions'] ?? '');
    $brew_time    = sanitize($_POST['brew_time']     ?? '');

    if ($title && $ingredients && $instructions) {
        $stmt = $conn->prepare("INSERT INTO recipes (user_id, title, category, ingredients, instructions, brew_time) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("isssss", $user_id, $title, $category, $ingredients, $instructions, $brew_time);
        $msg = $stmt->execute() ? 'success' : 'error';
        $stmt->close();
    } else {
        $msg = 'empty';
    }
}

// ── Delete Recipe ────────────────────────────────────────
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $stmt   = $conn->prepare("DELETE FROM recipes WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $del_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?deleted=1");
    exit();
}

// ── Fetch user's recipes ─────────────────────────────────
$stmt = $conn->prepare("SELECT * FROM recipes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recipes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ── Stats ────────────────────────────────────────────────
$counts = ['Hot'=>0,'Cold'=>0,'Sweet'=>0,'Strong'=>0];
foreach ($recipes as $r) { $counts[$r['category']] = ($counts[$r['category']] ?? 0) + 1; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – BrewMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<section class="page-hero">
    <div class="container">
        <h1>👋 Welcome, <?= htmlspecialchars($username) ?>!</h1>
        <p>Manage your coffee recipes below</p>
    </div>
</section>

<section class="section">
<div class="container">

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-num"><?= count($recipes) ?></div>
                <div class="stat-label">Total Recipes</div>
            </div>
        </div>
        <?php foreach ($counts as $cat => $cnt): ?>
        <div class="col-6 col-md-3 d-none d-md-block">
            <div class="stat-card">
                <div class="stat-num"><?= $cnt ?></div>
                <div class="stat-label"><?= categoryEmoji($cat) ?> <?= $cat ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Alerts -->
    <?php if ($msg === 'success' || isset($_GET['deleted'])): ?>
        <div class="alert-bm-success"><?= $msg === 'success' ? 'Recipe added successfully! ☕' : 'Recipe deleted.' ?></div>
    <?php elseif ($msg === 'error'): ?>
        <div class="alert-bm-error">Failed to add recipe. Please try again.</div>
    <?php elseif ($msg === 'empty'): ?>
        <div class="alert-bm-error">Title, ingredients, and instructions are required.</div>
    <?php endif; ?>

    <!-- Add Recipe Form -->
    <div class="bm-form-card">
        <h3>Add New Recipe</h3>
        <form action="dashboard.php" method="POST" id="submitForm">
            <input type="hidden" name="action" value="add">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Recipe Title *</label>
                    <input type="text" class="form-control" name="title" placeholder="e.g. Iced Honey Latte" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category *</label>
                    <select class="form-select" name="category">
                        <option value="Hot">☕ Hot</option>
                        <option value="Cold">🧊 Cold</option>
                        <option value="Sweet">🍯 Sweet</option>
                        <option value="Strong">⚡ Strong</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Brew Time</label>
                    <input type="text" class="form-control" name="brew_time" placeholder="e.g. 5 mins">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ingredients * <small class="text-muted">(one per line)</small></label>
                    <textarea class="form-control" name="ingredients" rows="4" placeholder="Espresso 1 shot&#10;Milk 200ml&#10;Honey 1 tbsp" required></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Instructions * <small class="text-muted">(one step per line)</small></label>
                    <textarea class="form-control" name="instructions" rows="4" placeholder="1. Brew espresso&#10;2. Heat milk&#10;3. Combine and serve" required></textarea>
                </div>
            </div>
            <button type="submit" class="bm-btn-dark mt-3">Add Recipe ☕</button>
        </form>
    </div>

    <!-- My Recipes -->
    <h3 class="section-title">My Recipes <small style="font-size:1.1rem;color:var(--muted);">(<?= count($recipes) ?>)</small></h3>
    <hr class="section-divider">

    <?php if (empty($recipes)): ?>
        <div class="empty-state"><span class="empty-icon">☕</span>You haven't added any recipes yet. Use the form above!</div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($recipes as $r): ?>
        <div class="col-md-4 col-sm-6">
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
                    <p class="snippet"><?= htmlspecialchars(substr($r['ingredients'], 0, 60)) ?>...</p>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted"><?= date('M j, Y', strtotime($r['created_at'])) ?></small>
                        <a href="dashboard.php?delete=<?= $r['id'] ?>"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Delete this recipe?')"
                           style="font-size:.75rem;padding:.2rem .6rem;">Delete</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
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
