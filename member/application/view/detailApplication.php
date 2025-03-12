<?php require_once '../../modules/header.php'; ?>
<?php $referenceId = $_GET['reference_id'] ?>
<?php require_once 'sqlQuery.php'; ?>

<?php //print_r($complaint_list); 
?>

<body>
    <?php require_once '../../modules/navbar.php'; ?>
    <?php require_once '../../modules/sidebar.php'; ?>

    <?php
    // Custom field labels
    $field_labels = [
        "reference_id" => "Reference ID",
        "title" => "Title",
        "subject" => "Subject",
        "type" => "Complaint Type",
        "description" => "Description",
        "plantiff_name" => "Plantiff Name",
        "plantiff_address" => "Plantiff Address",
        "plantiff_ward_number" => "Plantiff Ward Number",
        "plantiff_mobile" => "Plantiff Mobile",
        "plantiff_email" => "Plantiff Email",
        "plantiff_citizenship_id" => "Plantiff Identification Number",
        "plantiff_father_name" => "Plantiff Father Name",
        "plantiff_grandfather_name" => "Plantiff Grandfather Name",
        "defendant_name" => "Defendant Name",
        "defendant_address" => "Defendant Address",
        "defendant_ward_number" => "Defendant Ward Number",
        "defendant_mobile" => "Defendant Mobile",
        "defendant_email" => "Defendant Email",
        "defendant_citizenship_id" => "Defendant Identification Number",
        "defendant_father_name" => "Defendant Father Name",
        "defendant_grandfather_name" => "Defendant Grandfather Name",
        "created_at" => "Created At"
    ];

    $top_fields = ["reference_id", "title", "subject", "type", "description", "created_at"];
    $hidden_fields = ["complaint_id", "upload_file_1", "upload_file_2"];
    ?>

    <div class="content" id="mainContent">

        <?php if (!empty($complaint_list)): ?>
            <div class="card mb-4">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #6f42c1;">
                    Application Details
                    <button id="expandButton" class="btn btn-light btn-sm" data-bs-toggle="collapse" data-bs-target="#complaintDetails">
                        View More
                    </button>
                    <button id="collapseButton" class="btn btn-light btn-sm" data-bs-toggle="collapse" data-bs-target="#complaintDetails" style="display: none;">
                        View Less
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered w-100">
                        <tbody>
                            <?php foreach ($top_fields as $field): ?>
                                <tr>
                                    <th style="width: 30%; min-width: 150px; white-space: nowrap;">
                                        <?= $field_labels[$field] ?? ucfirst($field) ?>
                                    </th>
                                    <td style="width: 70%; word-wrap: break-word; overflow-wrap: break-word;">
                                        <?= htmlspecialchars($complaint_list[0][$field] ?? '-') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div id="complaintDetails" class="collapse">
                        <table class="table table-bordered mt-3 w-100">
                            <tbody>
                                <?php foreach ($field_labels as $key => $label): ?>
                                    <?php if (in_array($key, $hidden_fields, true) || in_array($key, $top_fields, true)) continue; ?>
                                    <tr>
                                        <th style="width: 30%; min-width: 150px; white-space: nowrap;">
                                            <?= $label ?>
                                        </th>
                                        <td style="width: 70%; word-wrap: break-word; overflow-wrap: break-word;">
                                            <?= htmlspecialchars($complaint_list[0][$key] ?? '-') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="d-flex gap-2 mt-3">
                            <a href="../file/fileView.php?ref_id=<?= urlencode($complaint_list[0]['reference_id']) ?>" class="btn btn-success" target="_blank">View Uploaded Files</a>
                            <a href="../edit/complaintEdit.php?reference_id=<?= urlencode($complaint_list[0]['reference_id']) ?>" class="btn btn-primary" target="_blank">Edit</a>
                            <button id="expandButton" class="btn btn-warning btn-sm" data-bs-toggle="collapse" data-bs-target="#complaintDetails">
                                View Less
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <?php require_once 'statusTableComplaint.php'; ?>

        <?php else: ?>
            <p class="text-warning">No complaint found for this Reference ID.</p>
        <?php endif; ?>

    </div>

    <?php require_once '../../modules/footer.php'; ?>

    <script>
        document.getElementById("expandButton").addEventListener("click", function() {
            document.getElementById("expandButton").style.display = "none";
            document.getElementById("collapseButton").style.display = "inline-block";
        });

        document.getElementById("collapseButton").addEventListener("click", function() {
            document.getElementById("expandButton").style.display = "inline-block";
            document.getElementById("collapseButton").style.display = "none";
        });
    </script>
</body>

</html>