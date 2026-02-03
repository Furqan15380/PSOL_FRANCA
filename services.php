<?php
require_once 'includes/db_connect.php';
include 'includes/header.php';

// Fetch all services from database
$services = $pdo->query("SELECT * FROM services ORDER BY category, title")->fetchAll();

// Group services by category
$grouped_services = [];
foreach ($services as $service) {
    $grouped_services[$service['category']][] = $service;
}

// Function to get icon based on category or title (fallback)
function getServiceIcon($service)
{
    $title = strtolower($service['title']);
    if (strpos($title, 'study') !== false || strpos($title, 'consultancy') !== false)
        return 'fa-graduation-cap';
    if (strpos($title, 'language') !== false || strpos($title, 'ielts') !== false)
        return 'fa-language';
    if (strpos($title, 'interview') !== false)
        return 'fa-user-tie';
    if (strpos($title, 'web development') !== false || strpos($title, 'code') !== false)
        return 'fa-code';
    if (strpos($title, 'design') !== false)
        return 'fa-paint-brush';
    if (strpos($title, 'marketing') !== false || strpos($title, 'digital') !== false)
        return 'fa-bullhorn';
    if (strpos($title, 'it') !== false || strpos($title, 'training') !== false)
        return 'fa-laptop-code';
    return 'fa-briefcase'; // Default icon
}
?>

<main class="py-5 bg-light" style="padding-top: 100px;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-primary fw-bold text-uppercase letter-spacing-2">Our Expertise</span>
            <h1 class="display-4 fw-bold text-dark mt-3">Solutions We Provide</h1>
            <p class="lead text-secondary mx-auto mt-3" style="max-width: 700px;">Comprehensive consultancy services
                tailored to help you achieve your educational and digital transformation goals.</p>
        </div>

        <?php if (empty($grouped_services)): ?>
            <div class="text-center p-5 bg-white rounded-5 shadow-sm">
                <i class="fas fa-info-circle fa-3x text-primary mb-3"></i>
                <h3 class="h4">No services found.</h3>
                <p class="text-muted">Please check back later or add services from the admin dashboard.</p>
            </div>
        <?php else: ?>
            <?php foreach ($grouped_services as $category => $category_services): ?>
                <div class="mb-5 <?php echo $category !== array_key_first($grouped_services) ? 'mt-5' : ''; ?>">
                    <h2 class="h2 fw-bold text-dark mb-4 ps-3 border-start border-4 border-primary">
                        <?php echo htmlspecialchars($category); ?>
                    </h2>

                    <div class="row g-4">
                        <?php foreach ($category_services as $service): ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 hover-lift">
                                    <div class="mb-4">
                                        <i class="fas <?php echo getServiceIcon($service); ?> fa-2x text-primary"></i>
                                    </div>
                                    <h3 class="h4 fw-bold mb-3"><?php echo htmlspecialchars($service['title']); ?></h3>
                                    <p class="text-muted mb-0 lh-lg">
                                        <?php echo nl2br(htmlspecialchars($service['description'])); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>