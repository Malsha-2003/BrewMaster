<?php
// auth/register.php – User Registration | BrewMaster | ASB/2023/144
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

redirectIfLoggedIn();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $email    = sanitize($_POST['email']    ?? '');
    $password = $_POST['password']          ?? '';
    $confirm  = $_POST['confirm_password']  ?? '';

    // Server-side validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $errors[] = "All fields are required.";
    }
    if (!isValidEmail($email)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        // Check duplicate username/email
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Username or email is already registered.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?,?,?)");
            $ins->bind_param("sss", $username, $email, $hashed);

            if ($ins->execute()) {
                setFlash('success', 'Account created successfully! Please log in.');
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
            $ins->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – BrewMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">☕ BrewMaster</div>
        <h2>Create Account</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert-bm-error">
                <?php foreach ($errors as $e): ?><p style="margin:0"><?= $e ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" id="registerForm">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                       placeholder="coffeelover" required
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email"
                       placeholder="you@example.com" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Min. 6 characters" required>
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                       placeholder="Repeat your password" required>
            </div>
            <button type="submit" class="bm-btn-dark w-100">Register</button>
        </form>
        <p class="auth-footer">Already have an account? <a href="login.php">Login here</a></p>
        <p class="auth-footer"><a href="../index.php">← Back to Home</a></p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>
