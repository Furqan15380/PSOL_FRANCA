<?php
session_start();
require_once 'includes/db_connect.php';

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$success = '';
$error = '';
$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// If user not found, redirect to login
if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Validate inputs
    if (empty($name) || empty($email)) {
        $error = 'Name and email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Check if email already exists for another user
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            $error = 'This email is already in use.';
        } else {
            // Update profile
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->execute([$name, $email, $user_id]);
            $_SESSION['user_name'] = $name;
            $success = 'Profile updated successfully!';

            // Refresh user data
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        }
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = 'All password fields are required.';
    } elseif (!isset($user['password']) || !password_verify($current_password, $user['password'])) {
        $error = 'Current password is incorrect.';
    } elseif (strlen($new_password) < 6) {
        $error = 'New password must be at least 6 characters long.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'New passwords do not match.';
    } else {
        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed_password, $user_id]);
        $success = 'Password changed successfully!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile | PSOL FRANCA</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #0f172a;
            --accent-color: #3b82f6;
            --text-muted: #64748b;
        }

        body {
            background-color: #f1f5f9;
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar Desktop Layout */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary-color);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            color: white;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar-logo {
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-logo img {
            height: 40px;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex-grow: 1;
        }

        .nav-item {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            color: #94a3b8;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
        }

        .nav-item:hover,
        .nav-item.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        /* Responsive Adjustments */
        @media (max-width: 991.98px) {
            .sidebar {
                left: -100%;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
                padding-top: 5rem;
            }
        }

        .profile-card {
            background: white;
            border-radius: 25px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        /* Mobile Navbar */
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: white;
            padding: 0 1.5rem;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            z-index: 999;
        }

        @media (max-width: 991.98px) {
            .mobile-header {
                display: flex;
            }
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }

        .sidebar-overlay.show {
            display: block;
        }
    </style>
</head>

<body>
    <!-- Mobile Header -->
    <header class="mobile-header">
        <button class="btn btn-dark" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="d-flex align-items-center gap-2">
            <img src="Images/logo Psol.png" alt="Logo" height="30">
            <span class="fw-bold">PSOL ADMIN</span>
        </div>
    </header>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-logo d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <img src="Images/logo Psol.png" alt="PSOL Logo">
                <span class="fw-bold fs-5">PSOL ADMIN</span>
            </div>
            <button class="btn d-lg-none text-white border-0" onclick="toggleSidebar()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="nav-menu">
            <a href="admin_dashboard.php" class="nav-item"><i class="fas fa-th-large"></i> Overview</a>
            <a href="admin_inquiries.php" class="nav-item"><i class="fas fa-envelope"></i> Inquiries</a>
            <a href="admin_reviews.php" class="nav-item"><i class="fas fa-star"></i> Reviews</a>
            <a href="admin_dashboard.php#services" class="nav-item"><i class="fas fa-briefcase"></i> Services</a>
            <a href="admin_profile.php" class="nav-item active"><i class="fas fa-user-circle"></i> My Profile</a>
            <a href="index.php" class="nav-item"><i class="fas fa-globe"></i> View Site</a>
            <a href="logout.php" class="nav-item mt-auto text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <main class="main-content">
        <div class="container-fluid">
            <div class="mb-4">
                <h1 class="h2 fw-bold text-primary mb-1">My Profile</h1>
                <p class="text-muted">Manage your account settings and preferences</p>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-check-circle fs-4"></i>
                        <span><?php echo htmlspecialchars($success); ?></span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-exclamation-circle fs-4"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <!-- Profile Information -->
                <div class="col-12 col-xl-6">
                    <div class="profile-card h-100">
                        <h2 class="h5 fw-bold text-primary mb-4 d-flex align-items-center gap-2">
                            <i class="fas fa-user"></i> Profile Information
                        </h2>
                        <form method="POST">
                            <input type="hidden" name="update_profile" value="1">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Full Name</label>
                                <input type="text" id="name" name="name" class="form-control rounded-3 py-2"
                                    value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control rounded-3 py-2"
                                    value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 shadow-sm">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="col-12 col-xl-6">
                    <div class="profile-card h-100">
                        <h2 class="h5 fw-bold text-primary mb-4 d-flex align-items-center gap-2">
                            <i class="fas fa-lock"></i> Change Password
                        </h2>
                        <form method="POST">
                            <input type="hidden" name="change_password" value="1">
                            <div class="mb-3">
                                <label for="current_password" class="form-label fw-semibold">Current Password</label>
                                <input type="password" id="current_password" name="current_password"
                                    class="form-control rounded-3 py-2" placeholder="Enter current password" required>
                            </div>
                            <hr class="my-4 opacity-10">
                            <div class="mb-3">
                                <label for="new_password" class="form-label fw-semibold">New Password</label>
                                <input type="password" id="new_password" name="new_password"
                                    class="form-control rounded-3 py-2" placeholder="Min. 6 characters" required>
                            </div>
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label fw-semibold">Confirm New
                                    Password</label>
                                <input type="password" id="confirm_password" name="confirm_password"
                                    class="form-control rounded-3 py-2" placeholder="Confirm new password" required>
                            </div>
                            <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 shadow-sm">
                                <i class="fas fa-key me-2"></i> Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
    </script>
</body>

</html>