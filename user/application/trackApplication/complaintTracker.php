<?php include '../../modules/header.php'; ?>
<?php require_once 'translateHeader.php'; ?>
<?php include '../../modules/hashRefId.php'; ?>
<link rel="stylesheet" href="complaintRegistration.css">

<body>
    <?php include '../../modules/navbar.php'; ?>

    <!-- Form Container -->
    <div class="container">
        <h1 class="mt-4 mb-3">गुनासो ट्र्याक गर्नुहोस्</h1>
        <p>*गुनासो स्वीकृत हुनु अघि मात्र सम्पादन गर्न सकिन्छ।</p>
        <div id="alertContainer"></div>
        <form id="applicationForm" action="/submit" method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <label for="hashed_reference_id" translate="no">ह्यास गरिएको सन्दर्भ आईडी</label>
                <input type="text" class="form-control" id="hashed_reference_id" name="hashed_reference_id"
                    readonly>
            </div>
            <!-- General Fields -->
            <div class="form-group">
                <label for="reference_id">सन्दर्भ आईडी</label>
                <input type="text" class="form-control" id="reference_id" name="reference_id"
                    placeholder="सन्दर्भ आईडी प्रविष्ट गर्नुहोस्">
            </div>
            <div class="form-group">
                <label for="plantiff_mobile">वादीको मोबाइल</label>
                <div class="input-group">
                    <input type="number" pattern="\d{10}" class="form-control" id="plantiff_mobile"
                        name="plantiff_mobile" placeholder="मोबाइल नम्बर प्रविष्ट गर्नुहोस् (+977 बिना)">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" id="verifyMobileBtn">ओटिपी प्रमाणित
                            गर्नुहोस्</button>
                    </div>
                </div>
                <div class="invalid-feedback" id="plantiff_mobile_error"></div>
                <!-- Mobile OTP Section -->
                <div class="mt-2" id="mobile-otp-section" style="display: none;">
                    <input type="text" class="form-control mb-2" id="mobile_otp_input"
                        placeholder="मोबाइल ओटिपी प्रविष्ट गर्नुहोस्">
                    <div class="d-flex">
                        <button type="button" class="btn btn-success btn-sm mr-2" id="submitMobileOTP">ओटिपी पेश
                            गर्नुहोस्</button>
                        <button type="button" class="btn btn-secondary btn-sm" id="cancelMobileOTP">रद्द
                            गर्नुहोस्</button>
                    </div>
                    <small id="mobileOTPStatus" aria-live="polite" class="form-text text-success"></small>
                </div>
            </div>
            <div class="form-group">
                <label for="plantiff_email">वादीको इमेल</label>
                <div class="input-group">
                    <input type="email" class="form-control" id="plantiff_email" name="plantiff_email"
                        placeholder="इमेल प्रविष्ट गर्नुहोस्">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" id="verifyEmailBtn">ओटिपी प्रमाणित
                            गर्नुहोस्</button>
                    </div>
                </div>
                <div class="invalid-feedback" id="plantiff_email_error"></div>
                <!-- Email OTP Section -->
                <div class="mt-2" id="email-otp-section" style="display: none;">
                    <input type="text" class="form-control mb-2" id="email_otp_input"
                        placeholder="इमेल ओटिपी प्रविष्ट गर्नुहोस्">
                    <div class="d-flex">
                        <button type="button" class="btn btn-success btn-sm mr-2" id="submitEmailOTP">ओटिपी पेश
                            गर्नुहोस्</button>
                        <button type="button" class="btn btn-secondary btn-sm" id="cancelEmailOTP">रद्द
                            गर्नुहोस्</button>
                    </div>
                    <small id="emailOTPStatus" aria-live="polite" class="form-text text-success"></small>
                </div>
            </div>
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary" id="submitButton">पेश गर्नुहोस्</button>
            <div id="applicationDetails"></div>
            <br>
            <div id="applicationDetails2"></div>
        </form>
    </div>

    <!-- <script nonce="<?= $nonce ?>">
        document.getElementById('reference_id').addEventListener('change', function () {
            const refId = this.value.trim();
            document.getElementById('hashed_reference_id').value = refId;
            // In a real application, you would want to hash this value on the server-side.
            // This example just copies the value for demonstration.
        });
    </script> -->

    <script nonce="<?= $nonce ?>">
    document.getElementById('reference_id').addEventListener('input', function () {
        const refId = this.value.trim();
        const hashedRefIdInput = document.getElementById('hashed_reference_id');

        // Send an AJAX POST request to hashRefIdAjax.php to hash the refId
        $.ajax({
            url: 'hashRefIdAjax.php',
            type: 'POST',
            data: { ref_id: refId },
            dataType: 'json',
            success: function (data) {
                if (data && data.hashed_id) {
                    hashedRefIdInput.value = data.hashed_id;
                    // console.log("Plain Reference ID:", refId);
                    // console.log("Hashed Reference ID:", data.hashed_id);
                } else if (data && data.error) {
                    console.error("Error hashing Reference ID:", data.error);
                    hashedRefIdInput.value = ''; // Clear the hashed field on error
                } else {
                    console.error("Error hashing Reference ID: Invalid response from server.");
                    hashedRefIdInput.value = ''; // Clear the hashed field on error
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error hashing Reference ID:", error);
                hashedRefIdInput.value = ''; // Clear the hashed field on error
            }
        });
    });
</script>

    <?php include '../../modules/footer.php'; ?>
    <?php include 'dataValidation.php'; ?>
    <?php include 'dataFetcher.php'; ?>
</body>

</html>