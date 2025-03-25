<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Application Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
</head> -->

<?php require_once '../../modules/header.php'; ?>
<?php require_once "../../modules/translateHeader.php"; ?>

<body>
    <?php require_once '../../modules/navbar.php'; ?>
    <?php require_once '../../modules/sidebar.php'; ?>

    <br><br>
    <!-- Form Container -->
    <div class="content" id="mainContent">
        <h1 class="mt-4 mb-3">Upload File</h1>
        <div id="alertContainer"></div>
        <form id="applicationForm" action="/submit" method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" id="reference_id" name="reference_id"
                value="<?php echo htmlspecialchars($_GET['ref_id']); ?>">
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
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

            <button type="submit" class="btn btn-primary" id="submitButton">Submit</button>
        </form>
    </div>
    <!-- Bootstrap JS and dependencies (using full jQuery for animations) -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

    <?php require_once '../../modules/footer.php'; ?>

    <?php require_once 'dataValidation.php'; ?>
    <?php require_once 'dataInsertion.php'; ?>
    <style>
        .custom-file-label::after {
            content: "Browse";
            /* Changes the button text */
        }
    </style>
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
</body>

</html>