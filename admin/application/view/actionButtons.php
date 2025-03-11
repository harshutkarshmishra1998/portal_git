<div class="mt-3">
    <button id="exportCSV" class="btn btn-primary">Export CSV</button>

    <?php // if (str_contains($statusHistory[0]['status'], 'Pending')): ?>
        <button id="approveBtn" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">Approve</button>
        <button id="rejectBtn" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>
        <button id="commentBtn" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#commentModal">Add Comment</button>
        <a href="../file/fileUpload.php?ref_id=<?php echo urlencode($_GET['reference_id']); ?>" target="_blank"><button class="btn btn-info">Add File</button></a>
        <button id="sendNotificationButton" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#sendNotificationModal">Send Notification</button>
    <?php // endif; ?>
    
</div>

<?php require_once 'modals.php'; ?>