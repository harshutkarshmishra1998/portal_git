<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid px-4">
        <!-- Left Side: Logo + Brand Name -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="<?php echo $base_url; ?>/modules/img/logo.png" alt="Government Logo" class="logo-img">
            <span class="ms-2 brand-name">Nyayik Samiti of Kathmandu</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Right Side: Navigation Options + Search Bar -->
        <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
            <ul class="navbar-nav align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_url; ?>/public/homepage/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_url; ?>/public/homepage/index.php#jury-about-samiti">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_url; ?>/public/homepage/index.php#members-nyayik-samiti">Members</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_url; ?>/public/homepage/index.php#dispute-categories">Dispute Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_url; ?>/public/homepage/index.php#downloads">Downloads</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_url; ?>/public/homepage/index.php#faq">FAQs</a>
                </li>
                <!-- Mobile-only Options -->
                <li class="nav-item mobile-only">
                    <a class="nav-link" href="<?php echo $base_url; ?>/application/registerApplication/complaintRegistration.php">Register Complaint</a>
                </li>
                <li class="nav-item mobile-only">
                    <a class="nav-link" href="#">Track Complaint</a>
                </li>
                <!-- <li class="nav-item mobile-only">
                    <a class="nav-link" href="#">View/Edit Complaint</a>
                </li> -->
                <!-- <li class="nav-item mobile-only">
                    <a class="nav-link" href="#">Daily Hearings</a>
                </li>
                <li class="nav-item mobile-only">
                    <a class="nav-link" href="#">Weekly Hearings</a>
                </li> -->
                <li class="nav-item mobile-only">
                    <a class="nav-link" href="#">Act/Rule</a>
                </li>
                <!-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Language (English)
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                        <li><a class="dropdown-item" href="#" data-lang="en">English</a></li>
                        <li><a class="dropdown-item" href="#" data-lang="np">Nepali</a></li>
                    </ul>
                </li> -->
            </ul>
        </div>
    </div>
</nav>