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

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $review_id = $_POST['review_id'];
    $new_status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE reviews SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $review_id]);
    $success = 'Review status updated successfully!';
}

// Handle deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $review_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->execute([$review_id]);
    $success = 'Review deleted successfully!';
}

// Handle bulk approval
if (isset($_POST['bulk_action']) && $_POST['bulk_action'] == 'approve_all') {
    $pdo->query("UPDATE reviews SET status = 'approved' WHERE status = 'pending'");
    $success = 'All pending reviews approved!';
}

// Fetch all reviews
$stmt = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC");
$reviews = $stmt->fetchAll();

// Stats
$pending_count = $pdo->query("SELECT COUNT(*) FROM reviews WHERE status = 'pending'")->fetchColumn();
$approved_count = $pdo->query("SELECT COUNT(*) FROM reviews WHERE status = 'approved'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Management | PSOL ADMIN</title>
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

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .stat-card h3 {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
            font-weight: 700;
        }

        .stat-card p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 0;
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

        .content-card {
            background: white;
            border-radius: 25px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .badge-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-approved {
            background: #f0fdf4;
            color: #16a34a;
        }

        .badge-rejected {
            background: #fef2f2;
            color: #dc2626;
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
            <a href="admin_reviews.php" class="nav-item active"><i class="fas fa-star"></i> Reviews</a>
            <a href="admin_dashboard.php#services" class="nav-item"><i class="fas fa-briefcase"></i> Services</a>
            <a href="admin_profile.php" class="nav-item"><i class="fas fa-user-circle"></i> My Profile</a>
            <a href="index.php" class="nav-item"><i class="fas fa-globe"></i> View Site</a>
            <a href="logout.php" class="nav-item mt-auto text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <main class="main-content">
        <div class="container-fluid">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h1 class="h2 fw-bold text-primary mb-1">Manage Reviews</h1>
                    <p class="text-muted mb-0">Moderately control and publish customer reviews</p>
                </div>
                <?php if ($pending_count > 0): ?>
                    <form method="POST">
                        <input type="hidden" name="bulk_action" value="approve_all">
                        <button type="submit" class="btn btn-success rounded-3 px-4 shadow-sm w-100">
                            <i class="fas fa-check-double me-2"></i> Approve All Pending
                        </button>
                    </form>
                <?php endif; ?>
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

            <!-- Stats Grid -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4">
                    <div class="stat-card text-center text-md-start">
                        <h3><?php echo count($reviews); ?></h3>
                        <p>Total Reviews</p>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="stat-card text-center text-md-start">
                        <h3 class="text-warning"><?php echo $pending_count; ?></h3>
                        <p>Pending</p>
                    </div>
                </div>
                <div class="col-12 col-md-4 mt-3 mt-md-0">
                    <div class="stat-card text-center text-md-start">
                        <h3 class="text-success"><?php echo $approved_count; ?></h3>
                        <p>Published</p>
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="content-card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="d-none d-sm-table-cell">Date</th>
                                <th>Author</th>
                                <th class="d-none d-md-table-cell">Rating</th>
                                <th>Review</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reviews as $review): ?>
                                <tr>
                                    <td class="d-none d-sm-table-cell text-muted small">
                                        <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo htmlspecialchars($review['author_name']); ?>
                                        </div>
                                        <div class="text-muted small">
                                            <?php echo htmlspecialchars($review['author_role']); ?>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell text-nowrap">
                                        <?php for ($i = 0; $i < $review['rating']; $i++)
                                            echo '⭐'; ?>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 250px;">
                                            <?php echo htmlspecialchars($review['content']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $review['status']; ?> rounded-pill px-3">
                                            <?php echo ucfirst($review['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group shadow-sm rounded-3">
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                                <input type="hidden" name="update_status" value="1">
                                                <?php if ($review['status'] !== 'approved'): ?>
                                                    <button type="submit" name="status" value="approved"
                                                        class="btn btn-light btn-sm text-success" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($review['status'] !== 'rejected'): ?>
                                                    <button type="submit" name="status" value="rejected"
                                                        class="btn btn-light btn-sm text-warning" title="Reject">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </form>
                                            <a href="?delete=<?php echo $review['id']; ?>"
                                                class="btn btn-light btn-sm text-danger"
                                                onclick="return confirm('Delete review?')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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