<!-- SIDEBAR -->
<div id="sidebarMenu" class="sidebar">
    <nav class="nav flex-column mt-3">
        <a class="nav-link active" href="<?php echo $base_url;?>admin/dashboard/view/dashboard.php">Dashboard</a>
        <a class="nav-link" href="<?php echo $base_url;?>admin/application/view/listApplication.php">Applications</a>
        <a class="nav-link" href="<?php echo $base_url;?>admin/case/view/listApplication.php">Approved Cases</a>
        <a class="nav-link" href="<?php echo $base_url;?>admin/resolved/view/listApplication.php">Resolved Cases</a>
        <a class="nav-link" href="<?php echo $base_url;?>admin/adminManagement/view/adminList.php">Admin Management</a>
        <a class="nav-link" href="<?php echo $base_url;?>admin/memberManagement/view/memberList.php">Member Management</a>
        <a class="nav-link" href="<?php echo $base_url;?>admin/loginLogout/logout/index.php">Logout</a>
    </nav>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navLinks = document.querySelectorAll('#sidebarMenu .nav-link');

        navLinks.forEach(link => {
            link.addEventListener('mouseover', function () {
                this.classList.add('active');
            });

            link.addEventListener('mouseout', function () {
                this.classList.remove('active');
            });
        });
    });
</script>