<?php include '../../modules/header.php'; ?>
<link rel="stylesheet" href="complaintRegistration.css">

<body>
    <?php include '../../modules/navbar.php'; ?>

    <div class="content" id="mainContent">
        <h1 class="mt-4 mb-3">गुनासो हेर्नुहोस्</h1>
        <div id="alertContainer"></div>
        <form id="applicationForm" action="/submit" method="post" enctype="multipart/form-data" novalidate>
            <!-- General Fields -->
            <div class="form-group">
                <label for="reference_id">सन्दर्भ आईडी</label>
                <input type="text" class="form-control" id="reference_id" name="reference_id"
                    placeholder="सन्दर्भ आईडी प्रविष्ट गर्नुहोस्" readonly>
            </div>
            <div class="form-group">
                <label for="title">शीर्षक</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="शीर्षक प्रविष्ट गर्नुहोस्">
            </div>
            <div class="form-group">
                <label for="subject">विषय</label>
                <textarea class="form-control" id="subject" name="subject" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label for="type">प्रकार</label>
                <input type="text" class="form-control" id="type" name="type" placeholder="प्रकार प्रविष्ट गर्नुहोस्">
            </div>
            <div class="form-group">
                <label for="description">विवरण</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="status">स्थिति</label>
                <input type="text" class="form-control" id="status" name="status" readonly>
            </div>

            <!-- Side-by-side Plaintiff and Defendant Info -->
            <div class="row">
                <!-- Plaintiff Information -->
                <div class="col-md-6">
                    <h4>वादीको जानकारी</h4>
                    <div class="form-group">
                        <label for="plantiff_name">वादीको नाम</label>
                        <input type="text" class="form-control" id="plantiff_name" name="plantiff_name">
                    </div>
                    <div class="form-group">
                        <label for="plantiff_address">वादीको ठेगाना</label>
                        <textarea class="form-control" id="plantiff_address" name="plantiff_address" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="plantiff_ward_number">वादीको वडा नम्बर</label>
                        <input type="text" class="form-control" id="plantiff_ward_number" name="plantiff_ward_number">
                    </div>
                    <div class="form-group">
                        <label for="plantiff_mobile">वादीको मोबाइल</label>
                        <input type="text" pattern="\d{10}" class="form-control" id="plantiff_mobile"
                            name="plantiff_mobile" placeholder="१०-अंकीय मोबाइल नम्बर" readonly>
                    </div>
                    <div class="form-group">
                        <label for="plantiff_email">वादीको इमेल</label>
                        <input type="email" class="form-control" id="plantiff_email" name="plantiff_email"
                            placeholder="इमेल प्रविष्ट गर्नुहोस्" readonly>
                    </div>
                    <div class="form-group">
                        <label for="plantiff_citizenship_id">वादीको नागरिकता प्रमाणपत्र नम्बर</label>
                        <input type="text" class="form-control" id="plantiff_citizenship_id" name="plantiff_citizenship_id">
                    </div>
                    <div class="form-group">
                        <label for="plantiff_father_name">वादीको बाबुको नाम</label>
                        <input type="text" class="form-control" id="plantiff_father_name" name="plantiff_father_name">
                    </div>
                    <div class="form-group">
                        <label for="plantiff_grandfather_name">वादीको हजुरबुबाको नाम</label>
                        <input type="text" class="form-control" id="plantiff_grandfather_name" name="plantiff_grandfather_name">
                    </div>
                </div>
                <!-- Defendant Information -->
                <div class="col-md-6">
                    <h4>प्रतिवादीको जानकारी</h4>
                    <div class="form-group">
                        <label for="defendant_name">प्रतिवादीको नाम</label>
                        <input type="text" class="form-control" id="defendant_name" name="defendant_name">
                    </div>
                    <div class="form-group">
                        <label for="defendant_address">प्रतिवादीको ठेगाना</label>
                        <textarea class="form-control" id="defendant_address" name="defendant_address" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="defendant_ward_number">प्रतिवादीको वडा नम्बर</label>
                        <input type="text" class="form-control" id="defendant_ward_number" name="defendant_ward_number">
                    </div>
                    <div class="form-group">
                        <label for="defendant_mobile">प्रतिवादीको मोबाइल</label>
                        <input type="text" pattern="\d{10}" class="form-control" id="defendant_mobile" name="defendant_mobile" placeholder="१०-अंकीय मोबाइल नम्बर">
                    </div>
                    <div class="form-group">
                        <label for="defendant_email">प्रतिवादीको इमेल</label>
                        <input type="email" class="form-control" id="defendant_email" name="defendant_email">
                    </div>
                    <div class="form-group">
                        <label for="defendant_citizenship_id">प्रतिवादीको नागरिकता प्रमाणपत्र नम्बर</label>
                        <input type="text" class="form-control" id="defendant_citizenship_id" name="defendant_citizenship_id">
                    </div>
                    <div class="form-group">
                        <label for="defendant_father_name">प्रतिवादीको बाबुको नाम</label>
                        <input type="text" class="form-control" id="defendant_father_name" name="defendant_father_name">
                    </div>
                    <div class="form-group">
                        <label for="defendant_grandfather_name">प्रतिवादीको हजुरबुबाको नाम</label>
                        <input type="text" class="form-control" id="defendant_grandfather_name" name="defendant_grandfather_name">
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <button type="button" class="btn btn-info my-2" id="viewFilesButton">अपलोड गरिएका फाइलहरू हेर्नुहोस्</button>
            <button type="button" class="btn btn-warning my-2" id="editFormButton">फारम सम्पादन गर्नुहोस्</button>
        </form>
    </div>

    <?php include '../../modules/footer.php'; ?>
    <?php include 'dataFetcher.php'; ?>
</body>

</html>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Function to get URL parameters
        function getUrlParameter(name) {
            return new URLSearchParams(window.location.search).get(name);
        }

        // Get ref_id from URL
        let refId = getUrlParameter('ref_id');

        if (refId) {
            // Set up the "View Files" button
            let viewFilesButton = document.getElementById("viewFilesButton");
            if (viewFilesButton) {
                viewFilesButton.onclick = () => window.location.href = `../uploadFile/fileView.php?ref_id=${refId}`;
            }

            // Set up the "Edit Form" button
            let editFormButton = document.getElementById("editFormButton");
            if (editFormButton) {
                editFormButton.onclick = () => window.location.href = `../editApplication/complaintEdit.php?ref_id=${refId}`;
            }
        }

        // Make all input and textarea fields read-only
        document.querySelectorAll("input, textarea").forEach(field => field.readOnly = true);
    });
</script>
