<?php
// contact.php – Contact & Submit Recipe | BrewMaster | ASB/2023/144
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$errors  = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($_POST['name']    ?? '');
    $email   = sanitize($_POST['email']   ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $errors[] = "All fields are required.";
    }
    if (!isValidEmail($email)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?,?,?)");
        $stmt->bind_param("sss", $name, $email, $message);
        if ($stmt->execute()) {
            $success = "Thank you! Your message has been sent successfully.";
        } else {
            $errors[] = "Failed to send message. Please try again.";
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
    <title>Contact – BrewMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<section class="page-hero">
    <div class="container">
        <h1>📬 Contact Us</h1>
        <p>Have a question, suggestion, or recipe idea? Reach out!</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="bm-form-card">
                    <h3>Send a Message</h3>

                    <?php if ($success): ?>
                        <div class="alert-bm-success"><?= $success ?></div>
                    <?php endif; ?>
                    <?php if (!empty($errors)): ?>
                        <div class="alert-bm-error"><?php foreach ($errors as $e) echo "<p style='margin:0'>$e</p>"; ?></div>
                    <?php endif; ?>

                    <form action="contact.php" method="POST" id="contactForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   placeholder="Your name" required
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   placeholder="you@example.com" required
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message"
                                      rows="6" placeholder="Write your message here..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="bm-btn-dark">Send Message</button>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="bm-form-card h-auto">
                    <h3>Get in Touch</h3>
                    <p class="text-muted mb-3">We'd love to hear from you.</p>
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <strong>📧 Email</strong><br>
                            <span class="text-muted">hello@brewmaster.com</span>
                        </div>
                        <div>
                            <strong>📍 Address</strong><br>
                            <span class="text-muted">Department of Computing,<br>Rajarata University of Sri Lanka</span>
                        </div>
                        <div>
                            <strong>📞 Phone</strong><br>
                            <span class="text-muted">+94 25 000 0000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bm-footer">
    <div class="container text-center">
        <p>☕ BrewMaster – COM 2303 Web Design &nbsp;|&nbsp; ASB/2023/144</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
