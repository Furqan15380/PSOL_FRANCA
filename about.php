<?php include 'includes/header.php'; ?>

<main class="py-5 bg-light" style="padding-top: 100px;">
    <div class="container">
        <!-- Hero Section for About -->
        <div class="text-center mb-5">
            <span class="text-primary fw-bold text-uppercase letter-spacing-2">Since 2021</span>
            <h1 class="display-4 fw-bold text-dark mt-3">About PSOL FRANCA</h1>
            <p class="lead text-secondary mx-auto mt-3" style="max-width: 800px;">
                PSOL FRANCA is a professional study abroad and skills consultancy founded in 2021, with over 4 years of
                experience helping students achieve international education and career goals.
            </p>
        </div>

        <div class="row g-5 align-items-start mb-5">
            <div class="col-lg-6">
                <div class="bg-white p-5 rounded-5 shadow-sm h-100">
                    <h2 class="h2 text-dark mb-4 ps-3 border-start border-4 border-primary">
                        Our Mission
                    </h2>
                    <p class="text-secondary fs-5 mb-4 lh-lg">
                        At PSOL FRANCA, our mission is to empower students with the knowledge, skills, and professional
                        support they need to succeed internationally. We are a trusted destination for students seeking
                        expert guidance and modern skill development.
                    </p>
                    <div class="p-4 bg-info-subtle rounded-4">
                        <h4 class="h5 text-primary fw-bold mb-2">Conveniently Located</h4>
                        <p class="text-secondary mb-0">Located on 6th Road, behind Midway Center in Millan Height Plaza
                            (Ground Floor), Rawalpindi.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="d-flex flex-column gap-4">
                    <!-- Expertise Card 1 -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 d-flex flex-row gap-4 align-items-start">
                        <i class="fas fa-graduation-cap fa-2x text-primary flex-shrink-0"></i>
                        <div>
                            <h3 class="h4 text-dark mb-2">Global Education</h3>
                            <p class="text-secondary mb-0 lh-lg">University selection, application processing, visa
                                guidance, and interview preparation for top destinations.</p>
                        </div>
                    </div>
                    <!-- Expertise Card 2 -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 d-flex flex-row gap-4 align-items-start">
                        <i class="fas fa-laptop-code fa-2x text-primary flex-shrink-0"></i>
                        <div>
                            <h3 class="h4 text-dark mb-2">Digital Mastery</h3>
                            <p class="text-secondary mb-0 lh-lg">IT and Digital Marketing training from basic to
                                advanced level, equipping you with practical job-ready skills.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Destinations Section -->
        <div class="bg-primary p-5 rounded-5 text-white mb-5">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold mb-3">Study Abroad Destinations</h2>
                <p class="text-white-50 fs-5">We provide comprehensive consultancy services for leading global
                    destinations:</p>
            </div>
            <div class="row g-3 justify-content-center">
                <?php
                $countries = ["UK", "France", "Germany", "Italy", "Ireland", "Netherlands", "Sweden", "Lithuania", "Cyprus", "Latvia", "Hungary", "Belgium", "Finland", "Australia", "New Zealand", "Austria", "Spain", "Greece", "Denmark", "Poland", "Czech Republic", "Romania", "Bulgaria", "Malta", "Estonia", "Luxembourg", "Slovakia", "Slovenia", "Switzerland", "Portugal", "Croatia"];
                foreach ($countries as $country) {
                    echo "<div class='col-auto'><div class='px-3 py-2 bg-white bg-opacity-10 rounded-3 border border-white border-opacity-10 hover-bg-primary transition-all'>$country</div></div>";
                }
                ?>
            </div>
        </div>

        <!-- Additional Services -->
        <div class="bg-white p-5 rounded-5 shadow-sm">
            <div class="row g-5">
                <div class="col-lg-6">
                    <h3 class="h3 text-dark mb-4">Language Test Preparation</h3>
                    <p class="text-secondary mb-4 lh-lg">
                        We specialize in language test coaching from beginner to advanced levels. Our experienced
                        instructors ensure your success in:
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <span
                            class="badge bg-light text-primary border border-primary-subtle rounded-pill px-3 py-2 fs-6 fw-semibold">IELTS</span>
                        <span
                            class="badge bg-light text-primary border border-primary-subtle rounded-pill px-3 py-2 fs-6 fw-semibold">TOEFL</span>
                        <span
                            class="badge bg-light text-primary border border-primary-subtle rounded-pill px-3 py-2 fs-6 fw-semibold">Oxford
                            LanguageCert</span>
                        <span
                            class="badge bg-light text-primary border border-primary-subtle rounded-pill px-3 py-2 fs-6 fw-semibold">PTE</span>
                        <span
                            class="badge bg-light text-primary border border-primary-subtle rounded-pill px-3 py-2 fs-6 fw-semibold">Duolingo</span>
                        <span
                            class="badge bg-light text-primary border border-primary-subtle rounded-pill px-3 py-2 fs-6 fw-semibold">GRE</span>
                        <span
                            class="badge bg-light text-primary border border-primary-subtle rounded-pill px-3 py-2 fs-6 fw-semibold">OET</span>
                    </div>
                </div>
                <div class="col-lg-6 border-start-lg ps-lg-5">
                    <h3 class="h3 text-dark mb-4">Interview Excellence</h3>
                    <p class="text-secondary mb-4 lh-lg">
                        Success in interviews is critical for visa and university approvals. We provide specialized
                        training for:
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center gap-3 text-secondary">
                            <i class="fas fa-check-circle text-primary"></i>
                            <strong>Credibility Interview Preparation</strong>
                        </li>
                        <li class="mb-3 d-flex align-items-center gap-3 text-secondary">
                            <i class="fas fa-check-circle text-primary"></i>
                            <strong>UKVI Interview Preparation</strong>
                        </li>
                        <li class="mb-3 d-flex align-items-center gap-3 text-secondary">
                            <i class="fas fa-check-circle text-primary"></i>
                            <strong>Spoken English (Beginner to Advanced)</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>