<?php include '../../modules/header.php'; ?>
<link rel="stylesheet" href="complaintRegistration.css">

<body>
    <?php include '../../modules/navbar.php'; ?>

    <!-- Form Container -->
    <div class="content container" id="mainContent">
    <h1 class="mt-4 mb-3">UPLOAD FILES FOR COMPLAINT NUMBER:
            <?php echo isset($_GET['ref_id']) ? htmlspecialchars($_GET['ref_id']) : ''; ?>
        </h1>
        <div id="alertContainer"></div>
        <form id="applicationForm" action="/submit" method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" id="reference_id" name="reference_id"
                value="<?php echo htmlspecialchars($_GET['ref_id']); ?>">

            <div class="form-group">
                <label for="plantiff_citizenship">Plaintiff Citizenship Proof</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="plantiff_citizenship" name="plantiff_citizenship">
                    <label class="custom-file-label" for="plantiff_citizenship">Choose file</label>
                </div>
                <div id="plantiff_citizenship_error" class="invalid-feedback d-block"></div>
            </div>

            <div class="form-group">
                <label for="defendant_citizenship">Defendant Citizenship Proof</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="defendant_citizenship"
                        name="defendant_citizenship">
                    <label class="custom-file-label" for="defendant_citizenship">Choose file</label>
                </div>
                <div id="defendant_citizenship_error" class="invalid-feedback d-block"></div>
            </div>

            <div class="form-group">
                <label for="file_upload">General File Upload</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="file_upload" name="file_upload" multiple>
                    <label class="custom-file-label" for="file_upload">Choose files</label>
                </div>
                <div id="file_upload_error" class="invalid-feedback d-block"></div>
            </div>

            <button type="submit" class="btn btn-primary my-2" id="submitButton">Submit</button>
        </form>
    </div>

    <?php include '../../modules/footer.php'; ?>
    <?php include 'dataValidation.php'; ?>
    <?php include 'dataInsertion.php'; ?>
</body>

</html>

<!-- <style>
        .custom-file-label::after {
            content: "Browse";
            /* Changes the button text */
        }
    </style> -->
    <script>
        $(".custom-file-input").on("change", function () {
            let files = $(this)[0].files;
            if (files.length > 1) {
                let fileNames = Array.from(files).map(file => file.name).join(", ");
                $(this).siblings(".custom-file-label").addClass("selected").html(fileNames);
            } else {
                $(this).siblings(".custom-file-label").addClass("selected").html(files[0] ? files[0].name : "Choose file");
            }
        });
    </script>