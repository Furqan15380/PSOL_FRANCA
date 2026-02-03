<?php
session_start();
require_once 'includes/db_connect.php';

$success = '';
$error = '';
$step = 'email'; // email, verify, reset

// Handle email submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_email'])) {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = 'Please enter your email address.';
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Generate reset token
            $reset_token = bin2hex(random_bytes(32));
            $reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Store token in database
            $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
            $stmt->execute([$reset_token, $reset_expires, $email]);

            // Store email in session for next step
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_token'] = $reset_token;

            $success = 'A password reset link has been generated. For demo purposes, you can proceed to reset your password.';
            $step = 'reset';
        } else {
            $error = 'No account found with that email address.';
        }
    }
}

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_SESSION['reset_email'] ?? '';

    if (empty($new_password) || empty($confirm_password)) {
        $error = 'Please fill in all fields.';
        $step = 'reset';
    } elseif (strlen($new_password) < 6) {
        $error = 'Password must be at least 6 characters long.';
        $step = 'reset';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Passwords do not match.';
        $step = 'reset';
    } else {
        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE email = ?");
        $stmt->execute([$hashed_password, $email]);

        // Clear session
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_token']);

        $success = 'Password reset successful! You can now login with your new password.';
        $step = 'complete';
    }
}

// Check if coming from session
if (isset($_SESSION['reset_email']) && !isset($_POST['submit_email'])) {
    $step = 'reset';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | PSOL FRANCA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body class="bg-light">

    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="card border-0 shadow-lg rounded-5 p-4 p-md-5" style="max-width: 500px; width: 100%;">

            <?php if ($step === 'email'): ?>
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-4"
                        style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="fas fa-key"></i>
                    </div>
                    <h2 class="fw-bold text-dark">Forgot Password?</h2>
                    <p class="text-secondary">No worries! Enter your email address and we'll help you reset your password.
                    </p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="submit_email" value="1">
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold text-primary">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control form-control-lg bg-light border-0"
                            placeholder="admin@psolfranca.com" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow-sm hover-lift">
                        <i class="fas fa-paper-plane me-2"></i> Send Reset Link
                    </button>
                </form>

            <?php elseif ($step === 'reset'): ?>
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-4"
                        style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h2 class="fw-bold text-dark">Reset Your Password</h2>
                    <p class="text-secondary">Enter your new password below. Make sure it's at least 6 characters long.</p>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <div><?php echo htmlspecialchars($success); ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="reset_password" value="1">
                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-bold text-primary">New Password</label>
                        <input type="password" id="new_password" name="new_password"
                            class="form-control form-control-lg bg-light border-0"
                            placeholder="Enter new password (min. 6 characters)" required>
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="form-label fw-bold text-primary">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password"
                            class="form-control form-control-lg bg-light border-0" placeholder="Confirm new password"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow-sm hover-lift">
                        <i class="fas fa-check me-2"></i> Reset Password
                    </button>
                </form>

            <?php elseif ($step === 'complete'): ?>
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-4"
                        style="width: 100px; height: 100px; font-size: 3rem;">
                        <i class="fas fa-check"></i>
                    </div>
                    <h2 class="fw-bold text-dark">Password Reset Complete!</h2>
                    <p class="text-secondary">Your password has been successfully reset. You can now login with your new
                        password.</p>
                </div>

                <a href="login.php" class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow-sm hover-lift mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Go to Login
                </a>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="login.php" class="text-decoration-none text-secondary hover-text-primary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Login
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>