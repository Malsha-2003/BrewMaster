<?php
// includes/navbar.php
if (session_status() === PHP_SESSION_NONE) session_start();
$base = getBase();
$current = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bm-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= $base ?>index.php">
            <span class="brand-icon">☕</span> BrewMaster
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link <?= $current==='index.php'?'active':'' ?>" href="<?= $base ?>index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current==='recipes.php'?'active':'' ?>" href="<?= $base ?>recipes.php">Recipes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current==='contact.php'?'active':'' ?>" href="<?= $base ?>contact.php">Contact</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $current==='dashboard.php'?'active':'' ?>" href="<?= $base ?>dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="btn bm-btn-outline btn-sm ms-lg-2" href="<?= $base ?>auth/logout.php">Logout</a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base ?>auth/login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="btn bm-btn-primary btn-sm ms-lg-2" href="<?= $base ?>auth/register.php">Register</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
