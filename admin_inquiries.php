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
    $inquiry_id = $_POST['inquiry_id'];
    $new_status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE inquiries SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $inquiry_id]);
    $success = 'Inquiry status updated successfully!';
}

// Handle inquiry deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $inquiry_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM inquiries WHERE id = ?");
    $stmt->execute([$inquiry_id]);
    $success = 'Inquiry deleted successfully!';
}

// Pagination
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Get total count
$total_count = $pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
$total_pages = ceil($total_count / $per_page);

// Fetch all inquiries with pagination
$stmt = $pdo->prepare("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$inquiries = $stmt->fetchAll();

// Get stats
$new_count = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'new'")->fetchColumn();
$pending_count = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'pending'")->fetchColumn();
$completed_count = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'completed'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Inquiries | PSOL FRANCA</title>
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

        .stat-box {
            background: white;
            padding: 1.5rem;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .stat-box h3 {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
            font-weight: 700;
        }

        .stat-box p {
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

        .badge-new {
            background: #eff6ff;
            color: #3b82f6;
        }

        .badge-pending {
            background: #fef3c7;
            color: #f59e0b;
        }

        .badge-completed {
            background: #f0fdf4;
            color: #22c55e;
        }

        .badge-cancelled {
            background: #fef2f2;
            color: #ef4444;
        }

        .detail-row {
            margin-bottom: 1.5rem;
        }

        .detail-row label {
            display: block;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .detail-row p {
            color: #475569;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 12px;
            margin-bottom: 0;
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
            <a href="admin_inquiries.php" class="nav-item active"><i class="fas fa-envelope"></i> Inquiries</a>
            <a href="admin_reviews.php" class="nav-item"><i class="fas fa-star"></i> Reviews</a>
            <a href="admin_dashboard.php#services" class="nav-item"><i class="fas fa-briefcase"></i> Services</a>
            <a href="admin_profile.php" class="nav-item"><i class="fas fa-user-circle"></i> My Profile</a>
            <a href="index.php" class="nav-item"><i class="fas fa-globe"></i> View Site</a>
            <a href="logout.php" class="nav-item mt-auto text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <main class="main-content">
        <div class="container-fluid">
            <div class="mb-4">
                <h1 class="h2 fw-bold text-primary mb-1">All Inquiries</h1>
                <p class="text-muted">Manage and track all customer inquiries</p>
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

            <!-- Stats Row -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="stat-box text-center text-md-start">
                        <h3><?php echo $total_count; ?></h3>
                        <p>Total Inquiries</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-box text-center text-md-start">
                        <h3 class="text-primary"><?php echo $new_count; ?></h3>
                        <p>New</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-box text-center text-md-start">
                        <h3 class="text-warning"><?php echo $pending_count; ?></h3>
                        <p>Pending</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-box text-center text-md-start">
                        <h3 class="text-success"><?php echo $completed_count; ?></h3>
                        <p>Completed</p>
                    </div>
                </div>
            </div>

            <!-- Inquiries List -->
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4 fw-bold mb-0">Inquiry List</h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="d-none d-lg-table-cell">ID</th>
                                <th>Name</th>
                                <th class="d-none d-md-table-cell">Email</th>
                                <th class="d-none d-xl-table-cell">Phone</th>
                                <th>Service</th>
                                <th class="d-none d-sm-table-cell">Date</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($inquiries)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fs-1 mb-3 d-block"></i>
                                        No inquiries found
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($inquiries as $inquiry): ?>
                                    <tr>
                                        <td class="d-none d-lg-table-cell text-muted small">#<?php echo $inquiry['id']; ?></td>
                                        <td class="fw-semibold"><?php echo htmlspecialchars($inquiry['full_name']); ?></td>
                                        <td class="d-none d-md-table-cell"><?php echo htmlspecialchars($inquiry['email']); ?>
                                        </td>
                                        <td class="d-none d-xl-table-cell text-nowrap">
                                            <?php echo htmlspecialchars($inquiry['phone']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($inquiry['service_interested']); ?></td>
                                        <td class="d-none d-sm-table-cell text-nowrap">
                                            <?php echo date('M d, Y', strtotime($inquiry['created_at'])); ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo $inquiry['status']; ?> rounded-pill px-3">
                                                <?php echo ucfirst($inquiry['status']); ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group shadow-sm rounded-3">
                                                <button class="btn btn-light btn-sm text-primary"
                                                    onclick='viewInquiry(<?php echo json_encode($inquiry); ?>)'>
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-light btn-sm text-warning"
                                                    onclick='editStatus(<?php echo json_encode($inquiry); ?>)'>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="?delete=<?php echo $inquiry['id']; ?>"
                                                    class="btn btn-light btn-sm text-danger"
                                                    onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link rounded-3 me-2" href="?page=<?php echo $page - 1; ?>"><i
                                            class="fas fa-chevron-left me-1"></i> Prev</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link rounded-3 mx-1" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link rounded-3 ms-2" href="?page=<?php echo $page + 1; ?>">Next <i
                                            class="fas fa-chevron-right ms-1"></i></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- View Inquiry Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Inquiry Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="viewContent">
                    <!-- Dynamic Content -->
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Status Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="POST">
                        <input type="hidden" name="update_status" value="1">
                        <input type="hidden" name="inquiry_id" id="edit_inquiry_id">
                        <div class="detail-row mb-3">
                            <label class="form-label fw-semibold">Inquiry From</label>
                            <p id="edit_name" class="p-3 bg-light rounded-3 mb-0 fw-semibold"></p>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" id="edit_status" class="form-select rounded-3 py-2" required>
                                <option value="new">New</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 shadow-sm">
                            <i class="fas fa-save me-2"></i> Update Status
                        </button>
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

        const viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));

        function viewInquiry(inquiry) {
            const content = `
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="detail-row">
                            <label>Full Name</label>
                            <p>${inquiry.full_name}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-row">
                            <label>Email Address</label>
                            <p>${inquiry.email}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-row">
                            <label>Phone Number</label>
                            <p>${inquiry.phone || 'N/A'}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-row">
                            <label>Service Interested</label>
                            <p>${inquiry.service_interested}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-row">
                            <label>Message</label>
                            <p>${inquiry.message || 'No message provided'}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-row">
                            <label>Submitted On</label>
                            <p>${new Date(inquiry.created_at).toLocaleString()}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-row">
                            <label>Current Status</label>
                            <p class="bg-transparent p-0"><span class="badge badge-${inquiry.status} rounded-pill px-3">${inquiry.status.toUpperCase()}</span></p>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('viewContent').innerHTML = content;
            viewModal.show();
        }

        function editStatus(inquiry) {
            document.getElementById('edit_inquiry_id').value = inquiry.id;
            document.getElementById('edit_name').textContent = inquiry.full_name;
            document.getElementById('edit_status').value = inquiry.status;
            editModal.show();
        }
    </script>
</body>

</html>