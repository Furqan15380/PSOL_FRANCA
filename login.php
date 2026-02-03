<?php
session_start();
require_once 'includes/db_connect.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Check database for user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PSOL FRANCA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body class="bg-light">

    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="card border-0 shadow-lg rounded-5 p-4 p-md-5" style="max-width: 450px; width: 100%;">
            <div class="text-center mb-4">
                <img src="Images/Logo Psol.png" alt="PSOL Logo" height="80" class="mb-3">
                <h2 class="fw-bold text-dark">Welcome Back</h2>
                <p class="text-secondary">Please enter your credentials to login</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <div><?php echo $error; ?></div>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold text-primary">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control form-control-lg bg-light border-0"
                        placeholder="info@psolfranca.com" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label fw-bold text-primary">Password</label>
                    <input type="password" id="password" name="password"
                        class="form-control form-control-lg bg-light border-0" placeholder="••••••••" required>
                    <div class="text-end mt-2">
                        <a href="forgot_password.php" class="text-decoration-none small fw-medium">
                            <i class="fas fa-key me-1"></i> Forgot Password?
                        </a>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow-sm hover-lift">
                    Login to Account
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="index.php" class="text-decoration-none text-secondary hover-text-primary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>