<?php
require_once 'includes/db_connect.php';
include 'includes/header.php';
?>

<main style="padding-top: 100px;">

    <!-- About Section -->
    <section id="about" class="about-section py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="about-image-wrapper animate-float text-center">
                        <img src="assets/images/about-us.png" alt="Our Consultancy Team"
                            class="img-fluid rounded-4 shadow-lg">
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="text-primary fw-bold text-uppercase">Who We Are</span>
                    <h2 class="display-5 fw-bold text-dark mb-4">A Trusted Name in Global Education & Careers</h2>
                    <p class="lead text-secondary mb-4">PSOL FRANCA is a professional study abroad and skills
                        consultancy founded in 2021, with over 4 years of experience helping students achieve
                        international education and career goals.</p>
                    <p class="text-secondary mb-4">Located on 6th Road, Rawalpindi, we are a trusted destination for
                        students seeking expert guidance in university admissions, visa processing, and modern skill
                        development to succeed in the global job market.</p>

                    <div class="row g-4 text-center text-lg-start">
                        <div class="col-6 col-sm-3">
                            <h3 class="fw-bold text-primary">150+</h3>
                            <p class="text-muted small">Global Clients</p>
                        </div>
                        <div class="col-6 col-sm-3">
                            <h3 class="fw-bold text-primary">4+</h3>
                            <p class="text-muted small">Years Exp.</p>
                        </div>
                        <div class="col-6 col-sm-3">
                            <h3 class="fw-bold text-primary">95%</h3>
                            <p class="text-muted small">Satisfaction</p>
                        </div>
                        <div class="col-6 col-sm-3">
                            <h3 class="fw-bold text-primary">500+</h3>
                            <p class="text-muted small">Projects</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Empower Your Future Section -->
    <section class="empower-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold text-dark mb-3">Empower Your Future</h2>
                <p class="lead text-secondary mx-auto" style="max-width: 700px;">Expert guidance for study abroad,
                    language training, and skill development in today's market.</p>
            </div>

            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="d-flex flex-column gap-4">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-laptop-code"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-2">Skill Development</h4>
                                <p class="text-secondary mb-0">Learn in-demand skills like digital marketing, Shopify,
                                    Amazon, and web development.</p>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-plane-departure"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-2">Study Abroad Support</h4>
                                <p class="text-secondary mb-0">Comprehensive guidance for pursuing education in
                                    world-renowned institutions with seamless admissions.</p>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-language"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-2">Language Training</h4>
                                <p class="text-secondary mb-0">Premium English training including IELTS and TOEFL
                                    preparation for confident learners.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 order-1 order-lg-2">
                    <img src="Images/Numl.jpg" alt="NUML Collaboration" class="img-fluid rounded-4 shadow-lg w-100">
                </div>
            </div>
        </div>
    </section>

    <!-- Achievements Section -->
    <section id="case-studies" class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-primary fw-bold text-uppercase letter-spacing-2">Milestones</span>
                <h2 class="display-4 fw-bold text-dark mt-2">Our Achievements</h2>
                <p class="text-secondary mx-auto mt-3" style="max-width: 700px; font-size: 1.1rem;">Since 2021, we have
                    been dedicated to transforming lives through global education and practical skill development.</p>
            </div>

            <div class="row g-4">
                <!-- Result 1 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm bg-light rounded-4 p-4 hover-lift">
                        <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center mb-4"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-passport fa-lg"></i>
                        </div>
                        <h3 class="h4 fw-bold text-dark mb-3">Global Education Success</h3>
                        <p class="text-secondary mb-0">Successfully guided hundreds of students toward international
                            education across Europe and top destinations with high visa success rates and reliable
                            counseling.</p>
                    </div>
                </div>

                <!-- Result 2 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm bg-light rounded-4 p-4 hover-lift">
                        <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center mb-4"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                        <h3 class="h4 fw-bold text-dark mb-3">Career Skills Mastery</h3>
                        <p class="text-secondary mb-0">Trained numerous students in IT and Digital Marketing from basic
                            to advanced levels, helping them develop practical career skills for the global market.</p>
                    </div>
                </div>

                <!-- Result 3 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm bg-light rounded-4 p-4 hover-lift">
                        <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center mb-4"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-certificate fa-lg"></i>
                        </div>
                        <h3 class="h4 fw-bold text-dark mb-3">Academic Excellence</h3>
                        <p class="text-secondary mb-0">Consistently produced excellent results in language tests (IELTS,
                            TOEFL, PTE, etc.) and secured admissions through expert UKVI and credibility interview prep.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Student Reviews Section -->
            <div class="mt-5 pt-5">
                <div class="text-center mb-5">
                    <span class="text-primary fw-bold text-uppercase letter-spacing-2">Testimonials</span>
                    <h3 class="display-5 fw-bold text-dark mt-2">What Our Students Say</h3>
                    <a href="submit-review.php" class="btn btn-outline-primary rounded-pill px-4 mt-3 fw-bold">
                        <i class="fas fa-pen me-2"></i> Write a Review
                    </a>
                </div>

                <div class="row g-4">
                    <?php
                    $stmt = $pdo->query("SELECT * FROM reviews WHERE status = 'approved' ORDER BY created_at DESC LIMIT 3");
                    $reviews = $stmt->fetchAll();

                    if (count($reviews) > 0):
                        foreach ($reviews as $review):
                            // Generate initials
                            $initials = strtoupper(substr($review['author_name'], 0, 1));
                            if (strpos($review['author_name'], ' ') !== false) {
                                $initials .= strtoupper(substr($review['author_name'], strpos($review['author_name'], ' ') + 1, 1));
                            }
                            ?>
                            <!-- Dynamic Review -->
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-0 shadow-sm h-100 p-4 rounded-4 position-relative">
                                    <i class="fas fa-quote-left position-absolute top-0 end-0 m-4 text-light display-1"
                                        style="opacity: 0.2;"></i>
                                    <div class="d-flex text-warning mb-3">
                                        <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                                            <i class="fas fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="fst-italic text-muted mb-4 position-relative z-1" style="flex: 1;">
                                        "<?php echo htmlspecialchars($review['content']); ?>"
                                    </p>
                                    <div class="d-flex align-items-center gap-3 mt-auto">
                                        <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 50px; height: 50px;">
                                            <?php echo $initials; ?>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold fs-6">
                                                <?php echo htmlspecialchars($review['author_name']); ?></h5>
                                            <p class="text-muted small mb-0">
                                                <?php echo htmlspecialchars($review['author_role']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <div class="col-12 text-center text-muted">
                            <p>No reviews yet. Be the first to share your experience!</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-5 py-5 px-4 bg-primary rounded-5 text-center text-white">
                    <h3 class="display-6 fw-bold mb-3">Ready to be our next success story?</h3>
                    <p class="lead text-white-50 mb-4 mx-auto" style="max-width: 600px;">
                        Join the growing community of students and families who trust PSOL FRANCA for their
                        international journey.
                    </p>
                    <a href="get-started.php"
                        class="btn btn-light rounded-pill px-5 py-3 fw-bold text-primary shadow hover-lift">Start Your
                        Journey</a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>