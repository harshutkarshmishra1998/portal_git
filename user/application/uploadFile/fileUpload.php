<?php include '../../modules/header.php'; ?>
<link rel="stylesheet" href="complaintRegistration.css">

<?php
require_once '../../modules/hashRefId.php';
$refIdFromURL = isset($_GET['ref_id']) ? $_GET['ref_id'] : '';
$refId = $refIdFromURL; // Initialize $refId

if ($refIdFromURL != '') {
    // Replace spaces with plus signs
    $refId = str_replace(' ', '+', $refIdFromURL);

    // Now pass the modified $refId to your unhashing function
    $refId = unhashedReferenceID($refId);
}
?>

<body>
    <?php include '../../modules/navbar.php'; ?>

    <!-- Form Container -->
    <div class="content container" id="mainContent">
        <h1 class="mt-4 mb-3">गुनासो नम्बर <?php echo $refId ?> का लागि फाइलहरू अपलोड गर्नुहोस्</h1>
        <div id="alertContainer"></div>
        <form id="applicationForm" action="/submit" method="post" enctype="multipart/form-data" novalidate>
            <!-- Hidden field to store reference ID -->
            <input type="hidden" id="reference_id" name="reference_id" value="<?php echo $refId; ?>">
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <!-- Plaintiff Citizenship Proof Upload -->
            <div class="form-group">
                <label for="plantiff_citizenship">वादीको नागरिकता प्रमाण</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="plantiff_citizenship" name="plantiff_citizenship">
                    <label class="custom-file-label" for="plantiff_citizenship">फाइल रोज्नुहोस्</label>
                </div>
                <div id="plantiff_citizenship_error" class="invalid-feedback d-block"></div>
            </div>

            <!-- Defendant Citizenship Proof Upload -->
            <div class="form-group">
                <label for="defendant_citizenship">प्रतिवादीको नागरिकता प्रमाण</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="defendant_citizenship" name="defendant_citizenship">
                    <label class="custom-file-label" for="defendant_citizenship">फाइल रोज्नुहोस्</label>
                </div>
                <div id="defendant_citizenship_error" class="invalid-feedback d-block"></div>
            </div>

            <!-- General File Upload -->
            <div class="form-group">
                <label for="file_upload">सामान्य फाइल अपलोड</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="file_upload" name="file_upload" multiple>
                    <label class="custom-file-label" for="file_upload">फाइलहरू रोज्नुहोस्</label>
                </div>
                <div id="file_upload_error" class="invalid-feedback d-block"></div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary my-2" id="submitButton">पेश गर्नुहोस्</button>
        </form>
    </div>

    <?php include '../../modules/footer.php'; ?>
    <?php include 'dataValidation.php'; ?>
    <?php include 'dataInsertion.php'; ?>
</body>

</html>

<!-- Script to update custom file label on file selection -->
<script nonce="<?= $nonce ?>">
    $(".custom-file-input").on("change", function () {
        let files = $(this)[0].files;
        if (files.length > 1) {
            let fileNames = Array.from(files).map(file => file.name).join(", ");
            $(this).siblings(".custom-file-label").addClass("selected").html(fileNames);
        } else {
            $(this).siblings(".custom-file-label").addClass("selected").html(files[0] ? files[0].name : "फाइल रोज्नुहोस्");
        }
    });
</script>
