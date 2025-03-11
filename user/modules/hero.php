<!-- Hero Section -->
<section class="hero-section position-relative text-white">
    <!-- Background Overlay -->
    <div class="hero-overlay"></div>

    <div class="container-fluid px-4 py-3 hero-content">
        <div class="row align-items-center">
            <!-- Left Side: Main Text + Government Logo -->
            <div class="col-lg-7 mb-3 mb-lg-0">
                <div class="d-flex align-items-center mb-2">
                    <!-- Gov Logo (Left) -->
                    <img src="<?php echo $base_url; ?>/modules/img/hero-flag.gif" alt="Nepal Government Logo"
                        class="me-3 hero-gov-logo">
                    <!-- Main Heading and Subheading -->
                    <div>
                        <p class="mb-0 hero-subtitle">
                            Kathmandu Metropolitan City
                        </p>
                        <h2 class="mb-0 hero-title">
                            Electronic Justice Management System
                        </h2>
                        <h3 class="hero-tagline mb-0">
                            (Judgement)
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Right Side: Buttons -->
            <div class="col-lg-5 text-lg-end">
                <div class="d-flex justify-content-lg-end justify-content-center gap-2">
                    <a href="<?php echo $base_url; ?>/application/registerApplication/complaintRegistration.php" class="btn btn-primary">Register Complaint</a>
                    <a href="<?php echo $base_url; ?>/application/trackApplication/complaintTracker.php" class="btn btn-outline-light">Track Complaint</a>
                </div>
            </div>
        </div>
    </div>
</section>