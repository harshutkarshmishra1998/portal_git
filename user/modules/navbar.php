<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid px-4">
        <!-- Left Side: Logo + Brand Name -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="<?php echo $base_url; ?>user/modules/img/logo.png" alt="Government Logo" class="logo-img">
            <span class="ms-2 brand-name">धनपालथान गाउँपालिका</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Right Side: Navigation Options -->
        <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
            <ul class="navbar-nav align-items-lg-center">
                <!-- Home Link -->
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_url; ?>user/public/homepage/index.php">गृहपृष्ठ</a>
                </li>
                <!-- About Us Link -->
                <li class="nav-item">
                    <a class="nav-link"
                        href="<?php echo $base_url; ?>user/public/homepage/index.php#jury-about-samiti">हाम्रो
                        बारेमा</a>
                </li>
                <!-- Members Link -->
                <li class="nav-item">
                    <a class="nav-link"
                        href="<?php echo $base_url; ?>user/public/homepage/index.php#members-nyayik-samiti">सदस्यहरू</a>
                </li>
                <!-- Dropdown for Complaints -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownComplaint" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        गुनासो
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownComplaint">
                        <li>
                            <a class="dropdown-item"
                                href="<?php echo $base_url; ?>user/application/registerApplication/complaintRegistration.php">
                                गुनासो दर्ता गर्नुहोस्
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                                href="<?php echo $base_url; ?>user/application/trackApplication/complaintTracker.php">
                                गुनासो ट्र्याक गर्नुहोस्
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Dropdown for Mediators -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMediator" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        मध्यस्थकर्ताहरू
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMediator">
                        <li>
                            <a class="dropdown-item" href="<?php echo $base_url; ?>user/public/rules/mediators.php">
                                मध्यस्थकर्ताहरू सूची
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                                href="<?php echo $base_url; ?>user/application/registerMediator/mediatorRegistration.php">
                                मध्यस्थकर्ताहरू दर्ता
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Act/Rule Link -->
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_url; ?>user/public/rules/index.php">ऐन/नियम</a>
                </li>
                
                <!-- Additional commented links remain unchanged -->
                <!-- <li class="nav-item">
                    <a class="nav-link" href="<?php //echo $base_url; ?>user/public/homepage/index.php#dispute-categories">Dispute Categories</a>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link" href="<?php //echo $base_url; ?>user/public/homepage/index.php#downloads">Downloads</a>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link" href="<?php //echo $base_url; ?>user/public/homepage/index.php#faq">FAQs</a>
                </li> -->
            </ul>
        </div>
    </div>
</nav>