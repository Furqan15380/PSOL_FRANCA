<?php
session_start();
require_once 'includes/db_connect.php';

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch Stats
$inquiry_count = $pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
$service_count = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();

// Fetch Recent Inquiries
$recent_inquiries = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Fetch Services
$services = $pdo->query("SELECT * FROM services ORDER BY created_at DESC")->fetchAll();

// Handle Service Addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_service'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO services (title, category, description) VALUES (?, ?, ?)");
    $stmt->execute([$title, $category, $description]);
    header("Location: admin_dashboard.php?success=service_added");
    exit();
}

// Handle Service Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_service'])) {
    $id = $_POST['service_id'];
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("UPDATE services SET title = ?, category = ?, description = ? WHERE id = ?");
    $stmt->execute([$title, $category, $description, $id]);
    header("Location: admin_dashboard.php?success=service_updated");
    exit();
}

// Handle Service Deletion
if (isset($_GET['delete_service'])) {
    $id = $_GET['delete_service'];
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin_dashboard.php?success=service_deleted");
    exit();
}

// Handle Review Deletion
if (isset($_GET['delete_review'])) {
    $id = $_GET['delete_review'];
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin_dashboard.php?success=review_deleted");
    exit();
}

// Fetch Reviews
$recent_reviews = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC LIMIT 5")->fetchAll();
$review_count = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | PSOL FRANCA</title>
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
            display: flex;
            align-items: center;
            gap: 1.5rem;
            height: 100%;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .blue-icon {
            background: #eff6ff;
            color: #3b82f6;
        }

        .green-icon {
            background: #f0fdf4;
            color: #22c55e;
        }

        .purple-icon {
            background: #faf5ff;
            color: #a855f7;
        }

        .content-card {
            background: white;
            border-radius: 25px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        .badge-new {
            background: #eff6ff;
            color: #3b82f6;
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
            <a href="#" class="nav-item active"><i class="fas fa-th-large"></i> Overview</a>
            <a href="admin_inquiries.php" class="nav-item"><i class="fas fa-envelope"></i> Inquiries</a>
            <a href="admin_reviews.php" class="nav-item"><i class="fas fa-star"></i> Reviews</a>
            <a href="#services" class="nav-item"><i class="fas fa-briefcase"></i> Services</a>
            <a href="admin_profile.php" class="nav-item"><i class="fas fa-user-circle"></i> My Profile</a>
            <a href="index.php" class="nav-item"><i class="fas fa-globe"></i> View Site</a>
            <a href="logout.php" class="nav-item mt-auto text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <main class="main-content">
        <div class="container-fluid">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h1 class="h2 fw-bold text-primary mb-1">Welcome, Admin</h1>
                    <p class="text-muted mb-0">Here's what's happening today.</p>
                </div>
                <button class="btn btn-primary btn-lg rounded-3 px-4 shadow-sm d-none d-md-block" data-bs-toggle="modal"
                    data-bs-target="#addServiceModal">
                    <i class="fas fa-plus me-2"></i> Add New Service
                </button>
            </div>

            <!-- Stats Grid -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="stat-card">
                        <div class="stat-icon blue-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <h3 class="h4 fw-bold mb-0"><?php echo $inquiry_count; ?></h3>
                            <p class="text-muted small mb-0">Total Inquiries</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="stat-card">
                        <div class="stat-icon purple-icon"><i class="fas fa-briefcase"></i></div>
                        <div>
                            <h3 class="h4 fw-bold mb-0"><?php echo $service_count; ?></h3>
                            <p class="text-muted small mb-0">Active Services</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="stat-card">
                        <div class="stat-icon green-icon"><i class="fas fa-users"></i></div>
                        <div>
                            <h3 class="h4 fw-bold mb-0"><?php echo $review_count; ?></h3>
                            <p class="text-muted small mb-0">Total Reviews</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Add Service Button -->
            <div class="d-block d-md-none mb-4">
                <button class="btn btn-primary w-100 btn-lg rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                    <i class="fas fa-plus me-2"></i> Add New Service
                </button>
            </div>

            <!-- Recent Inquiries -->
            <div class="content-card mb-4" id="inquiries">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4 fw-bold mb-0">Recent Inquiries</h2>
                    <a href="admin_inquiries.php" class="btn btn-outline-primary btn-sm rounded-3">
                        View All
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Service</th>
                                <th class="d-none d-md-table-cell">Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_inquiries as $row): ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td class="d-none d-sm-table-cell">
                                        <?php echo htmlspecialchars($row['service_interested']); ?></td>
                                    <td class="d-none d-md-table-cell">
                                        <?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                    <td><span
                                            class="badge badge-new rounded-pill px-3"><?php echo ucfirst($row['status']); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="content-card mb-4" id="reviews">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4 fw-bold mb-0">Recent Reviews</h2>
                    <a href="admin_reviews.php" class="btn btn-outline-primary btn-sm rounded-3">
                        View All
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Author</th>
                                <th class="d-none d-sm-table-cell">Rating</th>
                                <th>Review</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_reviews as $row): ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($row['author_name']); ?></td>
                                    <td class="d-none d-sm-table-cell text-nowrap">
                                        <?php for ($i = 0; $i < $row['rating']; $i++)
                                            echo '⭐'; ?></td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;">
                                            <?php echo htmlspecialchars($row['content']); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-3"
                                            style="background: <?php echo $row['status'] == 'approved' ? '#f0fdf4' : ($row['status'] == 'pending' ? '#fef3c7' : '#fef2f2'); ?>; color: <?php echo $row['status'] == 'approved' ? '#16a34a' : ($row['status'] == 'pending' ? '#d97706' : '#dc2626'); ?>;">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="admin_dashboard.php?delete_review=<?php echo $row['id']; ?>"
                                            onclick="return confirm('Are you sure you want to delete this review?')"
                                            class="btn btn-light btn-sm text-danger rounded-3">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Services -->
            <div class="content-card" id="services">
                <h2 class="h4 fw-bold mb-4">Expertise Services</h2>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th class="d-none d-sm-table-cell">Category</th>
                                <th>Description</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $row): ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td class="d-none d-sm-table-cell"><?php echo htmlspecialchars($row['category']); ?>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 250px;">
                                            <?php echo htmlspecialchars($row['description']); ?></div>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group shadow-sm rounded-3">
                                            <button onclick='openEditModal(<?php echo json_encode($row); ?>)'
                                                class="btn btn-light btn-sm text-primary">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="admin_dashboard.php?delete_service=<?php echo $row['id']; ?>"
                                                onclick="return confirm('Are you sure you want to delete this service?')"
                                                class="btn btn-light btn-sm text-danger">
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

    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Add New Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST">
                        <input type="hidden" name="add_service" value="1">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Service Title</label>
                            <input type="text" name="title" class="form-control rounded-3"
                                placeholder="e.g. Web Development" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category" class="form-select rounded-3">
                                <option value="Study & Language">Study & Language</option>
                                <option value="Digital & IT">Digital & IT</option>
                                <option value="Interview Prep">Interview Prep</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control rounded-3" rows="4"
                                placeholder="Describe the service..." required></textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-light w-100 rounded-3 py-2"
                                    data-bs-dismiss="modal">Cancel</button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary w-100 rounded-3 py-2">Save Service</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <div class="modal fade" id="editServiceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST">
                        <input type="hidden" name="update_service" value="1">
                        <input type="hidden" name="service_id" id="edit_service_id">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Service Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category" id="edit_category" class="form-select rounded-3">
                                <option value="Study & Language">Study & Language</option>
                                <option value="Digital & IT">Digital & IT</option>
                                <option value="Interview Prep">Interview Prep</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="edit_description" class="form-control rounded-3" rows="4"
                                required></textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-light w-100 rounded-3 py-2"
                                    data-bs-dismiss="modal">Cancel</button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary w-100 rounded-3 py-2">Update
                                    Service</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        const editModal = new bootstrap.Modal(document.getElementById('editServiceModal'));

        function openEditModal(service) {
            document.getElementById('edit_service_id').value = service.id;
            document.getElementById('edit_title').value = service.title;
            document.getElementById('edit_category').value = service.category;
            document.getElementById('edit_description').value = service.description;
            editModal.show();
        }
    </script>
</body>

</html>