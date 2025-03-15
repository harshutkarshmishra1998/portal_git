<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Approve Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="approveForm">
                    <input type="hidden" name="csrf_token" id="csrf_token"
                        value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" id="reference_id" name="reference_id"
                        value="<?= htmlspecialchars($referenceId) ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="approveComplaint">Approve</button>
            </div>
            <div class="alert alert-success" role="alert" id="approveAlert" style="display: none;"></div>
        </div>
    </div>
</div>

<?php require_once 'modalFunctions/approveRequest.php'; ?>

<!--Send Notification Modal -->
<div class="modal fade" id="sendNotificationModal" tabindex="-1" aria-labelledby="sendNotificationLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendNotificationLabel">Send Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="notificationForm">
                    <input type="hidden" name="csrf_token" id="csrf_token"
                        value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="mb-3">
                        <label for="sendVia" class="form-label">Send Notification Via</label>
                        <select class="form-select" id="sendVia" name="sendVia">
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                            <option value="both">Email & SMS</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comment4" class="form-label">Comment</label>
                        <textarea class="form-control" id="comment4" name="comment4" rows="3"></textarea>
                    </div>
                    <input type="hidden" id="reference_id" name="reference_id"
                        value="<?= htmlspecialchars($referenceId) ?>">
                    <input type="hidden" id="email"
                        value="<?php echo htmlspecialchars($complaint_list[0]['email']); ?>">
                    <input type="hidden" id="mobile_number"
                        value="<?php echo htmlspecialchars($complaint_list[0]['mobile_number']); ?>">
                    <input type="hidden" id="full_name"
                        value="<?php echo htmlspecialchars($complaint_list[0]['full_name']); ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="sendNotificationAction">Send</button>
            </div>
            <div class="alert alert-success" role="alert" id="notificationAlert" style="display: none;"></div>
        </div>
    </div>
</div>

<?php require_once 'modalFunctions/sendNotification.php'; ?>