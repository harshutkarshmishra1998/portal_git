<?php include '../../modules/header.php'; ?>
<link rel="stylesheet" href="complaintRegistration.css">

<body>
    <?php include '../../modules/navbar.php'; ?>

    <!-- Form Container -->
    <div class="container">
        <h1 class="mt-4 mb-3">TRACK COMPLAINT</h1>
        <p>*The complaint can only be edited until approved</p>
        <div id="alertContainer"></div>
        <form id="applicationForm" action="/submit" method="post" enctype="multipart/form-data" novalidate>
            <!-- General Fields -->
            <div class="form-group">
                <label for="reference_id">Reference ID</label>
                <input type="text" class="form-control" id="reference_id" name="reference_id" placeholder="Enter Reference ID">
            </div>
            <div class="form-group">
                <label for="plantiff_mobile">Plaintiff Mobile</label>
                <div class="input-group">
                    <input type="number" pattern="\d{10}" class="form-control" id="plantiff_mobile" name="plantiff_mobile" placeholder="Enter mobile number without +977">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" id="verifyMobileBtn">Verify OTP</button>
                    </div>
                </div>
                <div class="invalid-feedback" id="plantiff_mobile_error"></div>
                <!-- Mobile OTP Section -->
                <div class="mt-2" id="mobile-otp-section" style="display: none;">
                    <input type="text" class="form-control mb-2" id="mobile_otp_input" placeholder="Enter Mobile OTP">
                    <div class="d-flex">
                        <button type="button" class="btn btn-success btn-sm mr-2" id="submitMobileOTP">Submit OTP</button>
                        <button type="button" class="btn btn-secondary btn-sm" id="cancelMobileOTP">Cancel</button>
                    </div>
                    <small id="mobileOTPStatus" aria-live="polite" class="form-text text-success"></small>
                </div>
            </div>
            <div class="form-group">
                <label for="plantiff_email">Plaintiff Email</label>
                <div class="input-group">
                    <input type="email" class="form-control" id="plantiff_email" name="plantiff_email" placeholder="Enter Email">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" id="verifyEmailBtn">Verify OTP</button>
                    </div>
                </div>
                <div class="invalid-feedback" id="plantiff_email_error"></div>
                <!-- Email OTP Section -->
                <div class="mt-2" id="email-otp-section" style="display: none;">
                    <input type="text" class="form-control mb-2" id="email_otp_input" placeholder="Enter Email OTP">
                    <div class="d-flex">
                        <button type="button" class="btn btn-success btn-sm mr-2" id="submitEmailOTP">Submit OTP</button>
                        <button type="button" class="btn btn-secondary btn-sm" id="cancelEmailOTP">Cancel</button>
                    </div>
                    <small id="emailOTPStatus" aria-live="polite" class="form-text text-success"></small>
                </div>
            </div>
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary" id="submitButton">Submit</button>
            <div id="applicationDetails"></div>
            <br>
            <div id="applicationDetails2"></div>
        </form>
    </div>

    <?php include '../../modules/footer.php'; ?>
    <?php include 'dataValidation.php'; ?>
    <?php include 'dataFetcher.php'; ?>
</body>

</html>