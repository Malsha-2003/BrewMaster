<?php
// auth/login.php – User Login | BrewMaster | ASB/2023/144
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

redirectIfLoggedIn();

$error = '';
$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitize($_POST['email']    ?? '');
    $password = $_POST['password']          ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter your email and password.";
    } elseif (!isValidEmail($email)) {
        $error = "Please enter a valid email address.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $username, $hashed);
        $stmt->fetch();
        $stmt->close();

        if ($id && password_verify($password, $hashed)) {
            $_SESSION['user_id']  = $id;
            $_SESSION['username'] = $username;
            header("Location: ../dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – BrewMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">☕ BrewMaster</div>
        <h2>Welcome Back</h2>

        <?php if ($flash): ?>
            <div class="alert-bm-<?= $flash['type'] === 'success' ? 'success' : 'error' ?>"><?= htmlspecialchars($flash['msg']) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert-bm-error"><?= $error ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email"
                       placeholder="you@example.com" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Your password" required>
            </div>
            <button type="submit" class="bm-btn-dark w-100">Login</button>
        </form>
        <p class="auth-footer">Don't have an account? <a href="register.php">Register here</a></p>
        <p class="auth-footer"><a href="../index.php">← Back to Home</a></p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>
