<?php if (session_status() === PHP_SESSION_NONE)
    session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Consultancy | Professional Business Solutions</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/media.css">
</head>
<header class="custom-header" id="main-header">
    <div class="header-container">
        <!-- Branding -->
        <a href="index.php" class="custom-logo">
            <img src="Images/logo Psol.png" alt="PSOL Logo">
            <div class="logo-text">
                <span class="brand-name">PSOL <span class="highlight">FRANCA</span></span>
                <span class="brand-tag">Global Consultancy</span>
            </div>
        </a>

        <!-- Navigation Links -->
        <nav class="custom-nav" id="custom-nav">
            <!-- Mobile Logo Header -->
            <div class="mobile-menu-header">
                <img src="Images/logo Psol.png" alt="PSOL Logo">
                <span class="brand-name">PSOL <span class="highlight">FRANCA</span></span>
            </div>

            <ul class="nav-links-wrapper">
                <li><a href="index.php" class="nav-item-link">Home</a></li>
                <li><a href="services.php" class="nav-item-link">Services</a></li>
                <li><a href="about.php" class="nav-item-link">About Us</a></li>
                <li><a href="index.php#case-studies" class="nav-item-link">Our Achievements</a></li>
                <li><a href="contact.php" class="nav-item-link">Contact Us</a></li>
                <li class="mobile-only-cta">
                    <a href="get-started.php" class="custom-cta-btn">Get Started</a>
                </li>
            </ul>
        </nav>

        <!-- Desktop CTA -->
        <div class="header-action">
            <a href="get-started.php" class="custom-cta-btn">Get Started</a>
        </div>

        <!-- Hamburger Button -->
        <button class="hamburger-btn" id="hamburger-toggle" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<div class="nav-overlay" id="nav-overlay"></div>

<style>
    /* Modern Design Tokens */
    :root {
        --header-bg: rgba(255, 255, 255, 0.82);
        --header-blur: 12px;
        --accent-blue: #3b82f6;
        --dark-slate: #0f172a;
        --text-gray: #64748b;
        --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Essential Reset for Header */
    .custom-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 2000;
        background: var(--header-bg);
        backdrop-filter: blur(var(--header-blur));
        -webkit-backdrop-filter: blur(var(--header-blur));
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        transition: var(--transition-smooth);
        padding: 15px 0;
    }

    .custom-header.is-scrolled {
        padding: 10px 0;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .header-container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Custom Logo Section */
    .custom-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        transition: transform 0.3s ease;
    }

    .custom-logo:hover {
        transform: translateY(-1px);
    }

    .custom-logo img {
        height: 48px;
        width: auto;
    }

    .logo-text {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
    }

    .brand-name {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--dark-slate);
        letter-spacing: -0.5px;
    }

    .brand-name .highlight {
        color: var(--accent-blue);
    }

    .brand-tag {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--text-gray);
        font-weight: 600;
    }

    /* Navigation Links */
    .nav-links-wrapper {
        display: flex;
        list-style: none;
        gap: 35px;
        margin: 0;
        padding: 0;
    }

    .nav-item-link {
        text-decoration: none;
        color: var(--dark-slate);
        font-weight: 600;
        font-size: 0.95rem;
        transition: var(--transition-smooth);
        position: relative;
        padding: 5px 0;
    }

    .nav-item-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--accent-blue);
        transition: var(--transition-smooth);
    }

    .nav-item-link:hover {
        color: var(--accent-blue);
    }

    .nav-item-link:hover::after {
        width: 100%;
    }

    /* Custom CTA Button */
    .custom-cta-btn {
        background: var(--dark-slate);
        color: #fff;
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: var(--transition-smooth);
        display: inline-block;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.15);
        font-size: 0.9rem;
    }

    .custom-cta-btn:hover {
        background: var(--accent-blue);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        color: #fff;
    }

    /* Hamburger Styles */
    .hamburger-btn {
        display: none;
        flex-direction: column;
        justify-content: space-between;
        width: 30px;
        height: 21px;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0;
        z-index: 2002;
    }

    .hamburger-btn span {
        width: 100%;
        height: 3px;
        background: var(--dark-slate);
        border-radius: 10px;
        transition: var(--transition-smooth);
    }

    .hamburger-btn.is-active span:nth-child(1) {
        transform: translateY(9px) rotate(45deg);
    }

    .hamburger-btn.is-active span:nth-child(2) {
        opacity: 0;
    }

    .hamburger-btn.is-active span:nth-child(3) {
        transform: translateY(-9px) rotate(-45deg);
    }

    .mobile-only-cta {
        display: none;
        margin-top: 20px;
    }

    .mobile-menu-header {
        display: none;
        align-items: center;
        gap: 12px;
        padding-bottom: 25px;
        margin-bottom: 30px;
        border-bottom: 1px solid #f1f5f9;
    }

    .mobile-menu-header img {
        height: 40px;
    }

    .mobile-menu-header .brand-name {
        font-size: 1.15rem;
    }

    .nav-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 1999;
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    /* Main Content Offset */
    main {
        padding-top: 85px;
    }

    /* Responsiveness (Custom-built Media Queries) */
    @media (max-width: 1100px) {
        .nav-links-wrapper {
            gap: 20px;
        }
    }

    @media (max-width: 991px) {
        .header-action {
            display: none;
        }

        .hamburger-btn {
            display: flex;
        }

        .mobile-menu-header {
            display: flex;
        }

        .custom-nav {
            position: fixed;
            top: 0;
            right: -100%;
            width: 300px;
            height: 100vh;
            background: #fff;
            padding: 100px 40px;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.05);
            transition: var(--transition-smooth);
            z-index: 2001;
            overflow-y: auto;
        }

        .custom-nav.is-active {
            right: 0;
        }

        .nav-links-wrapper {
            flex-direction: column;
            gap: 30px;
        }

        .nav-item-link {
            font-size: 1.2rem;
        }

        .mobile-only-cta {
            display: block;
        }

        .nav-overlay.is-active {
            display: block;
            opacity: 1;
        }

        .brand-name {
            font-size: 1.1rem;
        }
    }

    @media (max-width: 480px) {
        .custom-nav {
            width: 85%;
        }

        .custom-logo img {
            height: 40px;
        }

        .brand-tag {
            font-size: 0.55rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const header = document.getElementById('main-header');
        const nav = document.getElementById('custom-nav');
        const toggle = document.getElementById('hamburger-toggle');
        const overlay = document.getElementById('nav-overlay');

        // Scroll Logic
        window.addEventListener('scroll', () => {
            if (window.scrollY > 30) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
        });

        // Mobile Toggle Logic
        const toggleMenu = () => {
            nav.classList.toggle('is-active');
            toggle.classList.toggle('is-active');
            overlay.classList.toggle('is-active');
            document.body.style.overflow = nav.classList.contains('is-active') ? 'hidden' : '';
        };

        toggle.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);

        // Close menu on link click
        const navLinks = document.querySelectorAll('.nav-item-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (nav.classList.contains('is-active')) {
                    toggleMenu();
                }
            });
        });
    });
</script>
<div class="floating-contact">
    <a href="https://wa.me/923437775995" target="_blank" class="contact-btn whatsapp-btn" title="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    <a href="mailto:info@psolfranca.com" class="contact-btn email-btn" title="Send us an Email">
        <i class="fas fa-envelope"></i>
    </a>
</div>

<style>
    .floating-contact {
        position: fixed;
        bottom: 30px;
        right: 30px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        z-index: 1000;
    }

    .contact-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 30px;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .contact-btn:hover {
        transform: scale(1.1) translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    .whatsapp-btn {
        background-color: #25D366;
    }

    .whatsapp-btn:hover {
        background-color: #20BA5C;
    }

    .email-btn {
        background-color: #EA4335;
    }

    .email-btn:hover {
        background-color: #D3382C;
    }

    @media (max-width: 768px) {
        .floating-contact {
            bottom: 20px;
            right: 20px;
            gap: 10px;
        }

        .contact-btn {
            width: 50px;
            height: 50px;
            font-size: 24px;
        }
    }
</style>