<?php require_once '../../modules/header.php'; ?>
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
        "full_name" => " Name",
        "father_name" => " Father Name",
        "grandfather_name" => "Grandfather Name",
        "address" => " Address",
        "date_of_birth" => "Date of Birth",
        "mobile_number" => " Mobile",
        "email" => " Email",
        "educational_qualification" => "Educational Qualification",
        "ward" => "Ward",
        "created_at" => "Created At",
        "approved" => "Approved"
    ];

    // $top_fields = ["reference_id", "full_name", "email", "mobile_number", "ward", "educational_qualification", "created_at"];
    $hidden_fields = ["mediator_id"];
    ?>

    <div class="content" id="mainContent">

        <?php if (!empty($complaint_list)): ?>
            <div class="card mb-4">
                <div class="card-header text-white d-flex justify-content-between align-items-center"
                    style="background-color: #6f42c1;">
                    Application Details
                </div>
                <div class="card-body">
                    <div id="complaintDetails">
                        <table class="table table-bordered mt-3 w-100">
                            <tbody>
                                <?php foreach ($field_labels as $key => $label): ?>
                                    <?php if (in_array($key, $hidden_fields, true))
                                        continue; ?>
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
                            <a href="../file/fileView.php?ref_id=<?= urlencode($complaint_list[0]['reference_id']) ?>"
                                class="btn btn-warning" target="_blank">View Uploaded Files</a>
                            <a href="../edit/complaintEdit.php?reference_id=<?= urlencode($complaint_list[0]['reference_id']) ?>"
                                class="btn btn-primary" target="_blank">Edit</a>
                            <button id="approveBtn" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#approveModal">Approve</button>
                            <button id="sendNotificationButton" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#sendNotificationModal">Send Notification</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php require_once 'modals.php'; ?>

        <?php else: ?>
            <p class="text-warning">No complaint found for this Reference ID.</p>
        <?php endif; ?>

    </div>

    <?php require_once '../../modules/footer.php'; ?>
</body>

</html>