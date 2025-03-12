<!-- Global Styles -->
<style>
    :root {
        /* Colors derived from the Nepal flag (used as subtle accents) */
        --primary-color: #B71C1C;
        /* Deep Red (used for headings, buttons) */
        --secondary-color: #0277bd;
        /* Deep Blue (used for hover states, accents) */
        --background-color: #FFFFFF;
        /* White background */
        --text-color: #333333;
        /* Dark gray text for readability */
        --bg-color: rgba(255, 255, 255, 0.8);
        /* Glassmorphic background */
        --border-color: #e0e0e0;
        /* Light border color for separation */
        --text-color: #333;

        /* Footer Colors */
        --footer-bg-color: #1a1a1a;
        --footer-text-color: #f2f2f2;
        --footer-link-color: #f2f2f2;
        --footer-link-hover-color: #B71C1C;
    }

    /* Global Body Settings */
    body {
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
        line-height: 1.6;
        color: var(--text-color);
        background-color: var(--background-color);
        margin: 0;
        padding: 0;
    }

    /* Headings */
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: 'Arial', sans-serif;
        color: var(--text-color);
        margin-top: 0;
        margin-bottom: 0.5em;
    }

    /* Paragraphs */
    p {
        margin-bottom: 1em;
    }

    /* Links */
    a {
        color: var(--primary-color);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    a:hover {
        color: var(--secondary-color);
    }

    /* Buttons */
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }

    /* Utility Classes */
    .border-light {
        border-color: var(--border-color) !important;
    }
</style>

<style>
    /* Navbar Logo */
    .navbar-brand .logo-img {
        height: 50px;
        width: auto;
    }

    /* Brand Name beside the logo */
    .brand-name {
        font-weight: 600;
        font-size: 1rem;
    }

    /* Hide brand name below 300px screen width */
    @media (max-width: 400px) {
        .brand-name {
            display: none;
        }
    }

    /* Toggler focus outline removal */
    .navbar-toggler:focus {
        outline: none;
        box-shadow: none;
    }

    /* Navbar links */
    .nav-link {
        margin-right: 1rem;
        color: var(--text-color);
        position: relative;
        transition: color 0.3s ease;
        /* text-align: center; */
    }

    .nav-link:hover {
        color: var(--secondary-color);
    }

    /* Underline effect for nav links (hover, focus, active) */
    .nav-link::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -4px;
        width: 0;
        height: 2px;
        background-color: var(--primary-color);
        transition: width 0.3s ease;
    }

    .nav-link:hover::after,
    .nav-link:focus::after,
    .nav-link:active::after,
    .nav-link.active::after {
        width: 100%;
    }

    /* Hide mobile-only options on desktop */
    @media (min-width: 992px) {
        .mobile-only {
            display: none !important;
        }

        .nav-link {
            text-align: left;
        }
    }

    /* Dropdown container */
.nav-item.dropdown {
    position: relative;
}

/* Dropdown menu */
.dropdown-menu {
    border: none;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
    display: none; /* Hide initially */
    opacity: 0;
    transform: translateY(10px);
    transition: opacity 0.3s ease, transform 0.3s ease;
}

/* Show dropdown on hover */
.nav-item.dropdown:hover .dropdown-menu {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

/* Dropdown links */
.dropdown-item {
    position: relative;
    padding: 10px 20px;
    color: var(--text-color);
    transition: color 0.3s ease;
}

/* Underline effect for dropdown items */
.dropdown-item::after {
    content: "";
    position: absolute;
    left: 50%;
    bottom: 4px;
    width: 0;
    height: 2px;
    background-color: var(--primary-color); /* Adjust this to match your theme */
    transition: width 0.3s ease, left 0.3s ease;
}

/* Apply underline effect on hover */
.dropdown-item:hover::after {
    width: 100%;
    left: 0;
}

.nav-item.dropdown .nav-link::after {
    display: none;
}

    @media (max-width: 991px) {
        .mobile-only {
            display: block !important;
        }

        .nav-link {
            text-align: left;
        }
    }
</style>

<style>
    /* Hero Section */
    .hero-section {
        width: 100%;
        min-height: 250px;
        background: url("<?php echo $base_url; ?>/modules/img/hero-background.png") no-repeat center center;
        background-size: cover;
        position: relative;
        overflow: hidden;
    }

    /* Dark Overlay for readability */
    .hero-overlay {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.4);
        z-index: 1;
    }

    /* Content sits above the overlay */
    .hero-content {
        position: relative;
        z-index: 2;
    }

    /* Government Logo (left) */
    .hero-gov-logo {
        width: 150px;
        /* Adjust size as needed */
        height: 200px;
        /* opacity: 0.5; */
    }

    /* Hero Text Styling */
    .hero-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.4rem;
    }

    .hero-subtitle {
        font-size: 1.5rem;
        font-weight: 500;
        opacity: 0.9;
    }

    .hero-tagline {
        font-size: 1rem;
        opacity: 0.85;
        /* Align tagline with the text block (logo width + gap) */
    }

    /* Button Styling */
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline-light {
        color: #fff;
        border-color: #fff;
    }

    /* Mobile (≤992px): Center the content and adjust spacing */
    @media (max-width: 992px) {
        .hero-content .row {
            text-align: center;
        }

        .hero-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 500;
            opacity: 0.9;
        }

        .hero-tagline {
            font-size: 1rem;
            opacity: 0.85;
            margin-left: 70px;
            /* Align tagline with the text block (logo width + gap) */
        }

        .hero-gov-logo {
            width: 60px;
            /* Adjust size as needed */
            height: auto;
        }

        .hero-content .col-lg-7,
        .hero-content .col-lg-5 {
            text-align: center;
        }

        .d-flex.align-items-center.mb-2 {
            flex-direction: column;
            align-items: center;
        }

        .hero-gov-logo {
            margin-right: 0;
            margin-bottom: 10px;
        }

        .hero-tagline {
            margin-left: 0;
        }
    }
</style>

<style>
    /* Sub-navbar Container */
    .sub-navbar {
        background-color: #f8f9fa;
        /* Light background */
        border-bottom: 1px solid #ddd;
        padding: 10px 0;
    }
</style>

<style>
    /* ============================
    ✅ Section: Footer
   ============================ */
    .jury-footer {
        background-color: var(--footer-bg-color);
        color: var(--footer-text-color);
        padding: 3rem 0;
    }

    .jury-footer-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--background-color);
    }

    .jury-footer-link {
        color: var(--footer-link-color);
        text-decoration: none;
        font-size: 0.95rem;
        display: block;
        margin-bottom: 0.5rem;
        transition: color 0.3s;
    }

    .jury-footer-link:hover {
        color: var(--footer-link-hover-color);
        text-decoration: underline;
    }

    .jury-footer-bottom {
        border-top: 1px solid #333;
        padding-top: 1rem;
        margin-top: 1rem;
        font-size: 0.9rem;
        max-height: 5px;
    }
</style>