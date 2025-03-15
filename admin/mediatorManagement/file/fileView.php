<?php require_once '../../modules/header.php'; ?>

<?php 
$uploadDir2 = "../../../../uploads_mediator/";
?>

<body>
    <?php require_once '../../modules/navbar.php'; ?>
    <?php require_once '../../modules/sidebar.php'; ?>

    <br><br>


    <div class="content" id="mainContent">
        <h1 class="mt-4 mb-3">Uploaded Files for Mediator: 
            <?php echo isset($_GET['ref_id']) ? htmlspecialchars($_GET['ref_id']) : ''; ?>
        </h1>

        <!-- Search Bar -->
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search files..." />
        </div>

        <div id="alertContainer">
            <?php
            // Check for ref_id parameter in URL
            if (!isset($_GET['ref_id']) || empty(trim($_GET['ref_id']))) {
                echo '<div class="alert alert-danger">Reference ID not provided.</div>';
                exit;
            }
            $refId = trim($_GET['ref_id']);

            require_once '../../../include/db.php';

            try {
                // Fetch file columns from mediators table
                $stmt = $pdo->prepare("
                    SELECT 
                        citizenship_certificate,
                        photocopy_educational_certificate,
                        personal_biodata,
                        photocopy_mediator_training_certificate,
                        photocopy_mediator_experience_certificate,
                        scanned_application,
                        passport_size_photo
                    FROM mediators
                    WHERE reference_id = :ref_id
                    LIMIT 1
                ");
                $stmt->bindParam(':ref_id', $refId);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$row) {
                    echo '<div class="alert alert-info">No files found for this mediator.</div>';
                } else {
                    // Collect non-empty file names into an array
                    $files = [];
                    foreach ($row as $column => $filename) {
                        if (!empty($filename)) {
                            $files[] = $filename;
                        }
                    }

                    // Sort files alphabetically
                    sort($files, SORT_NATURAL | SORT_FLAG_CASE);

                    if (count($files) === 0) {
                        echo '<div class="alert alert-info">No files found for this mediator.</div>';
                    } else {
                        // Display files in a grid
                        echo '<div class="row">';
                        foreach ($files as $file) {
                            echo '<div class="col-md-3 mb-2 file-item">';
                            echo '  <div class="card">';
                            echo '      <div class="card-body">';
                            echo '          <p class="card-text">' . htmlspecialchars($file) . '</p>';
                            // Adjust path to match your uploads folder
                            echo '<a href="' . $uploadDir2 . urlencode($file) . '" target="_blank" class="btn btn-primary btn-sm">Download</a>';
                            echo '      </div>';
                            echo '  </div>';
                            echo '</div>';
                        }
                        echo '</div>';
                    }
                }
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error fetching files: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>
        </div><!-- /#alertContainer -->

        <!-- <div class="text-center mt-4">
            <a href="fileUpload.php?ref_id=<?php echo urlencode($refId); ?>" target="_blank">
                <button class="btn btn-secondary">Upload Additional Files</button>
            </a>
        </div> -->
    </div>

    <?php require_once '../../modules/footer.php'; ?>

    <!-- Real-time search script -->
    <script>
        document.getElementById('searchInput').addEventListener('input', function () {
            var filter = this.value.toLowerCase();
            var fileItems = document.getElementsByClassName('file-item');
            for (var i = 0; i < fileItems.length; i++) {
                var text = fileItems[i].textContent || fileItems[i].innerText;
                if (text.toLowerCase().indexOf(filter) > -1) {
                    fileItems[i].style.display = "";
                } else {
                    fileItems[i].style.display = "none";
                }
            }
        });
    </script>
</body>
</html>
