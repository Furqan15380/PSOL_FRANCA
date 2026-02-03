<?php
require_once 'includes/db_connect.php';

$success = false;
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_inquiry'])) {
    try {
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $service = $_POST['service'];
        $message = trim($_POST['message']);

        // Validate inputs
        if (empty($full_name) || empty($email) || empty($phone) || empty($service)) {
            $error = 'Please fill in all required fields.';
        } else {
            // Insert into database
            $stmt = $pdo->prepare("INSERT INTO inquiries (full_name, email, phone, service_interested, message, status, created_at) VALUES (?, ?, ?, ?, ?, 'new', NOW())");
            $stmt->execute([$full_name, $email, $phone, $service, $message]);
            $success = true;
        }
    } catch (PDOException $e) {
        $error = 'An error occurred. Please try again later.';
    }
}

include 'includes/header.php';
?>

<style>
    .get-started-main {
        margin-top: 80px;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        padding: 4rem 0;
    }

    .form-container {
        background: white;
        padding: 2.5rem;
        border-radius: 25px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        max-width: 650px;
        margin: 0 auto;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: #fff !important;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
    }

    /* Custom Dropdown Styling */
    .custom-dropdown {
        position: relative;
        width: 100%;
    }

    .dropdown-selected {
        background-color: #f8fafc;
        border-radius: 12px;
        padding: 0.8rem 1.25rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: var(--transition);
        font-weight: 500;
        color: var(--text-muted);
        border: 1px solid transparent;
    }

    .dropdown-selected:hover {
        background-color: #f1f5f9;
    }

    .dropdown-selected.active {
        background-color: #fff;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        color: var(--primary);
    }

    .dropdown-content {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        width: 100%;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        max-height: 180px;
        /* Fits approx 4 items at ~45px each */
        overflow-y: auto;
        display: none;
        border: 1px solid #e2e8f0;
    }

    .dropdown-content.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    .dropdown-item {
        padding: 0.75rem 1.25rem;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.95rem;
        color: var(--text-main);
    }

    .dropdown-item:hover {
        background-color: #f1f5f9;
        color: var(--accent);
    }

    .dropdown-item.selected {
        background-color: #eff6ff;
        color: var(--accent);
        font-weight: 600;
    }

    .optgroup-header {
        background-color: #f8fafc;
        padding: 0.5rem 1.25rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 800;
        color: #94a3b8;
        pointer-events: none;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .dropdown-content::-webkit-scrollbar {
        width: 6px;
    }

    .dropdown-content::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .dropdown-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2);
    }

    @media (max-width: 767.98px) {
        .get-started-main {
            padding: 2.5rem 0;
        }

        .form-container {
            padding: 2rem;
            border-radius: 20px;
        }

        .display-4 {
            font-size: 2.2rem;
        }
    }
</style>

<main class="get-started-main">
    <div class="container px-3">
        <div class="form-container">
            <div class="text-center mb-4">
                <span class="text-primary fw-bold text-uppercase ls-1"
                    style="font-size: 0.8rem; letter-spacing: 2px;">Take the First Step</span>
                <h2 class="display-6 fw-bold text-dark mt-2">Get Started</h2>
                <p class="text-muted small mt-2">Tell us about your goals and our experts will reach out within 24
                    hours.</p>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success p-4 rounded-4 text-center shadow-sm mb-4 border-0" role="alert">
                    <div class="text-success mb-3"><i class="fas fa-check-circle fa-3x"></i></div>
                    <h3 class="h4 fw-bold mb-2">Inquiry Submitted! 🎉</h3>
                    <p class="mb-3 opacity-75">Thank you for reaching out! Our team will contact you within 24 hours.</p>
                    <a href="index.php" class="btn btn-success fw-bold px-4 rounded-pill">Return Home</a>
                </div>
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger p-3 rounded-4 shadow-sm mb-4 border-0 d-flex align-items-center gap-3"
                        role="alert">
                        <i class="fas fa-exclamation-circle fs-4"></i>
                        <p class="mb-0 fw-medium"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>

                <form action="get-started.php" method="POST" class="row g-4">
                    <input type="hidden" name="submit_inquiry" value="1">

                    <div class="col-md-6">
                        <label for="full_name" class="form-label fw-semibold text-dark small">Full Name <span
                                class="text-danger">*</span></label>
                        <input type="text" id="full_name" name="full_name"
                            class="form-control bg-light border-0 py-2.5 px-3 rounded-3" placeholder="John Doe" required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold text-dark small">Email Address <span
                                class="text-danger">*</span></label>
                        <input type="email" id="email" name="email"
                            class="form-control bg-light border-0 py-2.5 px-3 rounded-3" placeholder="info@psolfranca.com"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-semibold text-dark small">Phone Number <span
                                class="text-danger">*</span></label>
                        <input type="tel" id="phone" name="phone"
                            class="form-control bg-light border-0 py-2.5 px-3 rounded-3" placeholder="+92 3XX XXXXXXX"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark small">Service Needed <span
                                class="text-danger">*</span></label>
                        <div class="custom-dropdown" id="serviceDropdown">
                            <div class="dropdown-selected" id="selectedService">
                                <span>Select a Service</span>
                                <i class="fas fa-chevron-down small"></i>
                            </div>
                            <div class="dropdown-content" id="dropdownContent">
                                <div class="optgroup-header">Study & Language</div>
                                <div class="dropdown-item" data-value="study-abroad">Study Abroad Consultancy</div>
                                <div class="dropdown-item" data-value="ielts">IELTS Preparation</div>
                                <div class="dropdown-item" data-value="toefl">TOEFL Preparation</div>
                                <div class="dropdown-item" data-value="pte">PTE Preparation</div>
                                <div class="dropdown-item" data-value="duolingo">Duolingo Language Certification</div>
                                <div class="dropdown-item" data-value="spoken-english">Oxford Spoken English</div>
                                <div class="dropdown-item" data-value="gre-oet">GRE / OET Preparation</div>

                                <div class="optgroup-header">Digital & IT</div>
                                <div class="dropdown-item" data-value="web-dev">Web Development</div>
                                <div class="dropdown-item" data-value="web-design">Web Designing</div>
                                <div class="dropdown-item" data-value="digital-marketing">Digital Marketing</div>
                                <div class="dropdown-item" data-value="basic-it">Basic IT Training</div>

                                <div class="optgroup-header">Interview Prep</div>
                                <div class="dropdown-item" data-value="ukvi-interview">UKVI Interview Prep</div>
                                <div class="dropdown-item" data-value="credibility-interview">Credibility Interview Prep
                                </div>
                            </div>
                            <input type="hidden" name="service" id="service-value" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="message" class="form-label fw-semibold text-dark small">Your Message (Optional)</label>
                        <textarea id="message" name="message" rows="3"
                            class="form-control bg-light border-0 py-2.5 px-3 rounded-3"
                            placeholder="Tell us more about your requirements..."></textarea>
                    </div>

                    <div class="col-12 pt-1">
                        <button type="submit"
                            class="btn btn-primary w-100 py-2.5 rounded-pill fw-bold shadow-sm hover-lift bg-blue border-0"
                            style="background-color: #3b82f6;">
                            Submit Inquiry Now
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdown = document.getElementById('serviceDropdown');
        const selected = document.getElementById('selectedService');
        const content = document.getElementById('dropdownContent');
        const items = content.querySelectorAll('.dropdown-item');
        const hiddenInput = document.getElementById('service-value');

        if (!selected || !content) return;

        selected.addEventListener('click', function (e) {
            e.stopPropagation();
            content.classList.toggle('show');
            selected.classList.toggle('active');
        });

        items.forEach(item => {
            item.addEventListener('click', function () {
                const val = this.getAttribute('data-value');
                const text = this.innerText;

                selected.querySelector('span').innerText = text;
                hiddenInput.value = val;

                items.forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');

                content.classList.remove('show');
                selected.classList.remove('active');
                selected.style.color = '#0f172a';
            });
        });

        document.addEventListener('click', function () {
            content.classList.remove('show');
            selected.classList.remove('active');
        });
    });
</script>



<?php include 'includes/footer.php'; ?>