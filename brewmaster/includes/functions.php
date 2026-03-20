<?php
// includes/functions.php – Helper functions
// BrewMaster | ASB/2023/144

/** Sanitize input to prevent XSS */
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

/** Validate email format */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/** Require login – redirect to login if not authenticated */
function requireLogin() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . getBase() . "auth/login.php");
        exit();
    }
}

/** Redirect to dashboard if already logged in */
function redirectIfLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (isset($_SESSION['user_id'])) {
        header("Location: " . getBase() . "dashboard.php");
        exit();
    }
}

/** Return base path depending on current file location */
function getBase() {
    return (strpos($_SERVER['PHP_SELF'], '/auth/') !== false) ? '../' : './';
}

/** Store a one-time flash message in session */
function setFlash($type, $msg) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

/** Retrieve and clear flash message */
function getFlash() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

/** Category badge colour map */
function categoryBadge($cat) {
    $map = [
        'Hot'    => 'badge-hot',
        'Cold'   => 'badge-cold',
        'Sweet'  => 'badge-sweet',
        'Strong' => 'badge-strong',
    ];
    return $map[$cat] ?? 'bg-secondary';
}

/** Category emoji */
function categoryEmoji($cat) {
    $map = ['Hot' => '☕', 'Cold' => '🧊', 'Sweet' => '🍯', 'Strong' => '⚡'];
    return $map[$cat] ?? '☕';
}
?>
