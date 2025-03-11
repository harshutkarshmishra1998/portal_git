<!-- NAVBAR -->
<nav class="navbar navbar-expand navbar-light bg-white shadow-sm" style="height: 56px;">
    <div class="container-fluid">
        <!-- Sidebar toggle button -->
        <button class="btn btn-light me-2" type="button" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>

        <!-- Brand name -->
        <a class="navbar-brand me-auto text-dark fw-semibold" href="#">Complaint Portal Admin</a>

        <!-- Right side content (user info, dropdown) -->
        <div class="dropdown">
            <button class="btn d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="me-2 text-dark fw-semibold">
                    <?= isset($_SESSION['name']) ? $_SESSION['name'] : "Default Name"; ?>
                </span>
                <img src="https://www.w3schools.com/howto/img_avatar.png" alt="Profile" class="profile-pic rounded-circle" width="40" height="40" />
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item text-danger" href="<?php echo $base_url;?>/loginLogout/logout/index.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .profile-pic {
        border: 2px solid #6c757d; /* Subtle border */
        object-fit: cover;
    }
</style>

