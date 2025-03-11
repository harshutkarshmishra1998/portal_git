<!-- SIDEBAR -->
<div id="sidebarMenu" class="sidebar">
    <nav class="nav flex-column mt-3">
    <a class="nav-link active" href="<?php echo $base_url;?>/dashboard/view/dashboard.php">Dashboard</a>
        <a class="nav-link" href="<?php echo $base_url;?>/application/view/listApplication.php">Applications</a>
        <a class="nav-link" href="<?php echo $base_url;?>/case/view/listApplication.php">Approved Cases</a>
        <a class="nav-link" href="<?php echo $base_url;?>/resolved/view/listApplication.php">Resolved Cases</a>
        <!-- <a class="nav-link" href="<?php echo $base_url;?>/adminManagement/view/adminList.php">Admin Management</a>
        <a class="nav-link" href="<?php echo $base_url;?>/memberManagement/view/memberList.php">Member Management</a> -->
        <a class="nav-link" href="<?php echo $base_url;?>/loginLogout/logout/index.php">Logout</a>
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