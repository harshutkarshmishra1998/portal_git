<?php require_once '../../modules/header.php'; ?>
<?php require_once "../../modules/translateHeader.php"; ?>


<body>
    <?php require_once '../../modules/navbar.php'; ?>
    <?php require_once '../../modules/sidebar.php'; ?>

    <br><br>

    <div class="content" id="mainContent">
        <h1 class="mt-4 mb-3">Uploaded Files for Application: <?php echo isset($_GET['ref_id']) ? htmlspecialchars($_GET['ref_id']) : ''; ?></h1>
        
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
                // SQL query to fetch file records for this reference ID from the file_upload table
                $stmt = $pdo->prepare("SELECT file_name FROM file_upload WHERE reference_id = :ref_id");
                $stmt->bindParam(':ref_id', $refId);
                $stmt->execute();
                $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error fetching files: ' . htmlspecialchars($e->getMessage()) . '</div>';
                exit;
            }

            // Build a nested grouping: first by D, then C, then B.
            // Expected file format: A_B_C_D_E.extension
            $groups = [];

            foreach ($files as $row) {
                $file_name = $row['file_name'];
                // Make sure the file name starts with the reference id and an underscore
                if (strpos($file_name, $refId . '_') === 0) {
                    // Split into parts; assuming underscores are used only as delimiters here.
                    $parts = explode('_', $file_name);
                    if (count($parts) < 5) {
                        // Skip files not matching the expected format.
                        continue;
                    }
                    // A = $parts[0] (reference id), 
                    // B = $parts[1], 
                    // C = $parts[2], 
                    // D = $parts[3],
                    // E = $parts[4] (includes extension)
                    $B = $parts[1];
                    $C = $parts[2];
                    $D = $parts[3];

                    // Build the nested array
                    if (!isset($groups[$D])) {
                        $groups[$D] = [];
                    }
                    if (!isset($groups[$D][$C])) {
                        $groups[$D][$C] = [];
                    }
                    if (!isset($groups[$D][$C][$B])) {
                        $groups[$D][$C][$B] = [];
                    }
                    $groups[$D][$C][$B][] = $file_name;
                }
            }

            // Helper function to format the B category names (file type) for display
            function formatFileCategory($category)
            {
                switch (strtolower($category)) {
                    case 'plantiff_citizenship':
                    case 'plaintiff_citizenship':
                        return 'Plaintiff Citizenship Proof';
                    case 'defendant_citizenship':
                        return 'Defendant Citizenship Proof';
                    case 'general_files':
                        return 'General Files';
                    case 'decision_files':
                        return 'Decision Files';
                    default:
                        return ucwords(str_replace('_', ' ', $category));
                }
            }

            // Display the groups as nested Bootstrap cards
            if (!empty($groups)) {
                foreach ($groups as $groupD => $groupC) {
                    echo '<div class="card mb-3">';
                    echo '<div class="card-header"><strong>' . htmlspecialchars(ucwords($groupD)) . '</strong></div>';
                    echo '<div class="card-body">';
                    
                    foreach ($groupC as $groupCKey => $groupB) {
                        echo '<div class="card mb-2">';
                        echo '<div class="card-header"><em>' . htmlspecialchars(ucwords($groupCKey)) . '</em></div>';
                        echo '<div class="card-body">';
                        
                        foreach ($groupB as $groupBKey => $fileList) {
                            echo '<div class="mb-3">';
                            echo '<h5>' . htmlspecialchars(formatFileCategory($groupBKey)) . '</h5>';
                            if (count($fileList) > 0) {
                                echo '<div class="row">';
                                foreach ($fileList as $file) {
                                    // Add "file-item" class to the outer div for filtering.
                                    echo '<div class="col-md-3 mb-2 file-item">';
                                    echo '<div class="card">';
                                    echo '<div class="card-body">';
                                    echo '<p class="card-text">' . htmlspecialchars($file) . '</p>';
                                    // Adjust the path to the uploads folder as necessary.
                                    echo '<a href="../../../uploads/' . urlencode($file) . '" target="_blank" class="btn btn-primary btn-sm">Download</a>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            } else {
                                echo '<p>No files found in this category.</p>';
                            }
                            echo '</div>'; // End of groupB block
                        }
                        
                        echo '</div>'; // End of card-body for groupC
                        echo '</div>'; // End of card for groupC
                    }
                    
                    echo '</div>'; // End of card-body for groupD
                    echo '</div>'; // End of card for groupD
                }
            } else {
                echo '<div class="alert alert-info">No files found for this application.</div>';
            }
            ?>
        </div><!-- /#alertContainer -->

        <div class="text-center mt-4">
            <a href="fileUpload.php?ref_id=<?php echo urlencode($refId); ?>&status=<?php echo urlencode($_GET['status']); ?>" target="_blank">
                <button class="btn btn-secondary">Upload Additional Files</button>
            </a>
        </div>
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
