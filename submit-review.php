<?php
session_start();
require_once 'includes/db_connect.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);
    $rating = (int) $_POST['rating'];
    $content = trim($_POST['content']);

    if (empty($name) || empty($content)) {
        $error = 'Name and review content are required.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO reviews (author_name, author_role, rating, content, status) VALUES (?, ?, ?, ?, 'approved')");
            $stmt->execute([$name, $role, $rating, $content]);
            $success = 'Thank you! Your review has been submitted and is now live.';
        } catch (PDOException $e) {
            $error = 'An error occurred. Please try again later.';
        }
    }
}

include 'includes/header.php';
?>

<main class="py-5 bg-light" style="padding-top: 150px; min-height: 100vh;">
    <div class="container">
        <div class="mx-auto" style="max-width: 700px;">
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-dark mb-3">Share Your Experience</h1>
                <p class="lead text-secondary">Your feedback helps us improve and inspires others.</p>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success d-flex align-items-center gap-3 p-4 rounded-4 mb-4 shadow-sm" role="alert">
                    <i class="fas fa-check-circle fa-2x"></i>
                    <p class="mb-0 fw-medium fs-5"><?php echo htmlspecialchars($success); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger d-flex align-items-center gap-3 p-4 rounded-4 mb-4 shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle fa-2x"></i>
                    <p class="mb-0 fw-medium fs-5"><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <div class="bg-white p-5 rounded-5 shadow-sm">
                <form method="POST">
                    <input type="hidden" name="submit_review" value="1">

                    <div class="mb-4">
                        <label class="form-label fw-bold text-primary">Your Name</label>
                        <input type="text" name="name" required placeholder="e.g. John Doe"
                            class="form-control form-control-lg bg-light border-0">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-primary">Role / Course (Optional)</label>
                        <input type="text" name="role" placeholder="e.g. IELTS Student"
                            class="form-control form-control-lg bg-light border-0">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-primary d-block">Rating</label>
                        <div class="d-flex flex-row-reverse justify-content-end gap-2 rating-input">
                            <input type="radio" name="rating" value="5" id="star5" required checked hidden>
                            <label for="star5" class="fs-1 text-muted cursor-pointer transition-colors">★</label>

                            <input type="radio" name="rating" value="4" id="star4" hidden>
                            <label for="star4" class="fs-1 text-muted cursor-pointer transition-colors">★</label>

                            <input type="radio" name="rating" value="3" id="star3" hidden>
                            <label for="star3" class="fs-1 text-muted cursor-pointer transition-colors">★</label>

                            <input type="radio" name="rating" value="2" id="star2" hidden>
                            <label for="star2" class="fs-1 text-muted cursor-pointer transition-colors">★</label>

                            <input type="radio" name="rating" value="1" id="star1" hidden>
                            <label for="star1" class="fs-1 text-muted cursor-pointer transition-colors">★</label>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold text-primary">Your Review</label>
                        <textarea name="content" rows="5" required
                            placeholder="Share your experience working with us..."
                            class="form-control form-control-lg bg-light border-0"></textarea>
                    </div>

                    <button type="submit"
                        class="btn btn-primary w-100 py-3 rounded-4 fw-bold fs-5 shadow-sm hover-lift">
                        Submit Review
                    </button>
                </form>
            </div>

            <div class="text-center mt-5">
                <a href="index.php" class="text-decoration-none text-secondary fw-medium hover-text-primary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</main>

<style>
    .rating-input input:checked~label,
    .rating-input label:hover,
    .rating-input label:hover~label {
        color: #fbbf24 !important;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .transition-colors {
        transition: color 0.2s;
    }

    .hover-text-primary:hover {
        color: var(--bs-primary) !important;
    }
</style>

<?php include 'includes/footer.php'; ?>