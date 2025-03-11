<!-- jQuery 3.6.0 (Required for AJAX + Form Handling) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5.3.3 (JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Popper JS (Required for Modals, Tooltips, etc.) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>

<!-- DataTables (for Complaint List, Complaint Status, etc.) -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 (for Success, Error, Confirmation Popups) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.js"></script>

<!-- Toastr Notifications (Small Success/Failure Popups) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- jQuery Validation (Form Validation) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<!-- Custom Frontend JS -->
<?php require_once 'frontendJs.js.php'; ?>

<footer class="jury-footer py-5">
    <div class="container-fluid px-4r">
        <div class="row">
            <!-- Left Side: Contact Details -->
            <div class="col-md-6">
                <h5 class="jury-footer-title">Judicial Committee, [Your Municipality Name]</h5>
                <ul class="list-unstyled">
                    <li><strong>Address:</strong> Ward No. 5, City Area, District Name, Nepal</li>
                    <li><strong>Phone:</strong> +977-1234567890</li>
                    <li><strong>Email:</strong> contact@municipality.gov.np</li>
                    <li><strong>Office Hours:</strong> Sunday - Friday (10 AM to 5 PM)</li>
                </ul>
            </div>

            <!-- Right Side: Quick Links -->
            <div class="col-md-3">
                <h5 class="jury-footer-title">Quick Navigation</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $base_url; ?>/public/homepage/index.php" class="jury-footer-link">Home</a></li>
                    <li><a href="<?php echo $base_url; ?>/public/homepage/index.php#jury-about-samiti" class="jury-footer-link">About Us</a></li>
                    <li><a href="<?php echo $base_url; ?>/public/homepage/index.php#members-nyayik-samiti" class="jury-footer-link">Member</a></li>
                    <li><a href="<?php echo $base_url; ?>/public/homepage/index.php#dispute-categories" class="jury-footer-link">Dispute Categories</a></li>
                    <li><a href="<?php echo $base_url; ?>/public/homepage/index.php#downloads" class="jury-footer-link">Downloads</a></li>
                    <li><a href="<?php echo $base_url; ?>/public/homepage/index.php#faq" class="jury-footer-link">FAQs</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5 class="jury-footer-title">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $base_url; ?>/application/registerApplication/complaintRegistration.php" class="jury-footer-link">Register Complaint</a></li>
                    <li><a href="<?php echo $base_url; ?>/application/trackApplication/complaintTracker.php" class="jury-footer-link">Track Complaint</a></li>
                    <!-- <li><a href="#" class="jury-footer-link">View/Edit Complaint</a></li> -->
                    <!-- <li><a href="#" class="jury-footer-link">Daily Hearings</a></li>
                    <li><a href="#" class="jury-footer-link">Weekly Hearings</a></li> -->
                    <li><a href="<?php echo $base_url; ?>/public/rules/index.php" class="jury-footer-link">Act/Rule</a></li>
                </ul>
            </div>
        </div>
        <!-- Bottom Bar -->
        <div class="jury-footer-bottom text-center mt-4">
            <p class="mb-0">&copy; 2025 Judicial Committee, [Your Municipality Name]. All Rights Reserved.</p>
        </div>
    </div>
</footer>