<?php include '../../modules/header.php'; ?>
<link rel="stylesheet" href="complaintRegistration.css">

<body>
    <?php include '../../modules/navbar.php'; ?>

    <!-- फारम कन्टेनर -->
    <div class="container">
        <h1 class="mt-4 mb-3">मध्यस्थकर्ता दर्ता फारम</h1>
        <div id="alertContainer"></div>
        <form id="mediatorRegistrationForm" action="/submit" method="post" enctype="multipart/form-data" novalidate>
            <!-- CSRF टोकन -->
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="form-group">
                <label for="reference_id">सन्दर्भ आईडी</label>
                <input type="text" class="form-control" id="reference_id" name="reference_id"
                    placeholder="सन्दर्भ आईडी प्रविष्ट गर्नुहोस्" disabled>
            </div>
            
            <!-- पूर्ण नाम -->
            <div class="form-group">
                <label for="full_name">पूर्ण नाम</label>
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="पूर्ण नाम प्रविष्ट गर्नुहोस्" required>
            </div>

            <!-- बाबुको नाम -->
            <div class="form-group">
                <label for="father_name">बाबुको नाम</label>
                <input type="text" class="form-control" id="father_name" name="father_name" placeholder="बाबुको नाम प्रविष्ट गर्नुहोस्" required>
            </div>

            <!-- हजुरबुबाको नाम -->
            <div class="form-group">
                <label for="grandfather_name">हजुरबुबाको नाम</label>
                <input type="text" class="form-control" id="grandfather_name" name="grandfather_name" placeholder="हजुरबुबाको नाम प्रविष्ट गर्नुहोस्" required>
            </div>

            <!-- ठेगाना -->
            <div class="form-group">
                <label for="address">ठेगाना</label>
                <textarea class="form-control" id="address" name="address" rows="2" placeholder="ठेगाना प्रविष्ट गर्नुहोस्" required></textarea>
            </div>

            <!-- जन्म मिति -->
            <div class="form-group">
                <label for="date_of_birth">जन्म मिति</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
            </div>

            <!-- मोबाइल नम्बर -->
            <div class="form-group">
                <label for="mobile_number">मोबाइल नम्बर</label>
                <input type="tel" class="form-control" id="mobile_number" name="mobile_number" placeholder="१० अङ्क मोबाइल नम्बर प्रविष्ट गर्नुहोस्" pattern="^\d{10}$" required>
                <div class="invalid-feedback" id="mobile_error"></div>
            </div>

            <!-- इमेल -->
            <div class="form-group">
                <label for="email">इमेल</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="वैध इमेल प्रविष्ट गर्नुहोस्" required>
                <div class="invalid-feedback" id="email_error"></div>
            </div>

            <!-- शैक्षिक योग्यता -->
            <div class="form-group">
                <label for="educational_qualification">शैक्षिक योग्यता</label>
                <input type="text" class="form-control" id="educational_qualification" name="educational_qualification" placeholder="उच्चतम योग्यता प्रविष्ट गर्नुहोस्" required>
            </div>

            <!-- वडा -->
            <div class="form-group">
                <label for="ward">वडा</label>
                <input type="text" class="form-control" id="ward" name="ward" placeholder="वडा नम्बर प्रविष्ट गर्नुहोस्" required>
            </div>

            <hr>
            <h4>आवश्यक कागजातहरू</h4>

            <!-- शैक्षिक प्रमाणपत्रको फोटोकपी -->
            <div class="form-group">
                <label for="photocopy_educational_certificate">शैक्षिक प्रमाणपत्रको फोटोकपी</label>
                <input type="file" class="form-control-file" id="photocopy_educational_certificate" name="photocopy_educational_certificate" required>
                <div id="photocopy_educational_certificate_error" class="invalid-feedback d-block"></div>
            </div>

            <!-- व्यक्तिगत विवरण (बायोडाटा) -->
            <div class="form-group">
                <label for="personal_biodata">व्यक्तिगत विवरण (बायोडाटा)</label>
                <input type="file" class="form-control-file" id="personal_biodata" name="personal_biodata" required>
                <div id="personal_biodata_error" class="invalid-feedback d-block"></div>
            </div>

            <!-- मध्यस्थ प्रशिक्षण प्रमाणपत्रको फोटोकपी -->
            <div class="form-group">
                <label for="photocopy_mediator_training_certificate">मध्यस्थ प्रशिक्षण प्रमाणपत्रको&nbsp;</label>
                <input type="file" class="form-control-file" id="photocopy_mediator_training_certificate" name="photocopy_mediator_training_certificate" required>
                <div id="photocopy_mediator_training_certificate_error" class="invalid-feedback d-block"></div>
            </div>

            <!-- मध्यस्थ अनुभव प्रमाणपत्रको फोटोकपी -->
            <div class="form-group">
                <label for="photocopy_mediator_experience_certificate">मध्यस्थ अनुभव प्रमाणपत्रको</label>
                <input type="file" class="form-control-file" id="photocopy_mediator_experience_certificate" name="photocopy_mediator_experience_certificate" required>
                <div id="photocopy_mediator_experience_certificate_error" class="invalid-feedback d-block"></div>
            </div>

            <!-- स्क्यान गरिएको आवेदन -->
            <div class="form-group">
                <label for="scanned_application">स्क्यान गरिएको आवेदन</label>
                <input type="file" class="form-control-file" id="scanned_application" name="scanned_application" required>
                <div id="scanned_application_error" class="invalid-feedback d-block"></div>
            </div>

            <!-- पासपोर्ट आकारको फोटो -->
            <div class="form-group">
                <label for="passport_size_photo">पासपोर्ट आकारको फोटो</label>
                <input type="file" class="form-control-file" id="passport_size_photo" name="passport_size_photo" required>
                <div id="passport_size_photo_error" class="invalid-feedback d-block"></div>
            </div>

            <!-- अतिरिक्त ५ फिलर स्तम्भहरू
            <div class="form-group">
                <label for="filler1">फिलर १</label>
                <input type="text" class="form-control" id="filler1" name="filler1" placeholder="फिलर १ प्रविष्ट गर्नुहोस्">
            </div>
            <div class="form-group">
                <label for="filler2">फिलर २</label>
                <input type="text" class="form-control" id="filler2" name="filler2" placeholder="फिलर २ प्रविष्ट गर्नुहोस्">
            </div>
            <div class="form-group">
                <label for="filler3">फिलर ३</label>
                <input type="text" class="form-control" id="filler3" name="filler3" placeholder="फिलर ३ प्रविष्ट गर्नुहोस्">
            </div>
            <div class="form-group">
                <label for="filler4">फिलर ४</label>
                <input type="text" class="form-control" id="filler4" name="filler4" placeholder="फिलर ४ प्रविष्ट गर्नुहोस्">
            </div>
            <div class="form-group">
                <label for="filler5">फिलर ५</label>
                <input type="text" class="form-control" id="filler5" name="filler5" placeholder="फिलर ५ प्रविष्ट गर्नुहोस्">
            </div> -->

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary" id="submitButton">पेश गर्नुहोस्</button>
        </form>
    </div>

    <br>
    <?php include '../../modules/footer.php'; ?>
    <?php include 'dataValidation.php'; ?>
    <?php include 'dataInsertion.php'; ?>
</body>
</html>
