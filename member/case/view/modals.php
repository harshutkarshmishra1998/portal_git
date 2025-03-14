<?php
require_once 'sqlQuery.php';
// echo $json_member;
?>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Add Hearing Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="approveForm">
                    <input type="hidden" name="csrf_token" id="csrf_token"
                        value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="mb-3">
                        <label for="hearing_date" class="form-label">Hearing Date <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="hearing_date" name="hearing_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="hearing_time" class="form-label">Hearing Time <span
                                class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="hearing_time" name="hearing_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="hearing_location" class="form-label">Hearing Location <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="hearing_location" name="hearing_location" required>
                    </div>
                    <div class="mb-3">
                        <label for="case_handler" class="form-label">Case Handler <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="case_handler" name="case_handler" required>
                            <option value="">Select Case Handler</option>
                            <?php
                            $members = json_decode($json_member, true);
                            if (is_array($members)) {
                                foreach ($members as $member) {
                                    echo '<option value="' . htmlspecialchars($member['member_id']) . '" data-name="' . htmlspecialchars($member['name']) . '">' . htmlspecialchars($member['name']) . ' - Ward ' . htmlspecialchars($member['ward_number']) . ' - ' . htmlspecialchars(formatRole($member['role'])) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comment1" class="form-label">Comment</label>
                        <textarea class="form-control" id="comment1" name="comment1" rows="3"></textarea>
                    </div>
                    <input type="hidden" id="reference_id" name="reference_id"
                        value="<?= htmlspecialchars($referenceId) ?>">
                    <input type="hidden" id="status" name="status" value="<?= htmlspecialchars($_GET['status']) ?>">
                    <input type="hidden" class="form-control" id="member_id_display" name="member_id_display" readonly>
                    <script>
                        document.getElementById('case_handler').addEventListener('change', function () {
                            const selectedOption = this.options[this.selectedIndex];
                            document.getElementById('member_id_display').value = this.value; // Set member_id
                        });
                    </script>
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

<!-- Resolved Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    <input type="hidden" name="csrf_token" id="csrf_token"
                        value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="mb-3">
                        <label for="hearing_date2" class="form-label">Resolved Date <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="hearing_date2" name="hearing_date2" required>
                    </div>
                    <div class="mb-3">
                        <label for="hearing_time2" class="form-label">Resolved Time <span
                                class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="hearing_time2" name="hearing_time2" required>
                    </div>
                    <div class="mb-3">
                        <label for="hearing_location2" class="form-label">Resolved Location <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="hearing_location2" name="hearing_location2"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="case_handler2" class="form-label">Case Handler <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="case_handler2" name="case_handler2" required>
                            <option value="">Select Case Resolver</option>
                            <?php
                            $members = json_decode($json_member, true);
                            if (is_array($members)) {
                                foreach ($members as $member) {
                                    echo '<option value="' . htmlspecialchars($member['member_id']) . '" data-name="' . htmlspecialchars($member['name']) . '">' . htmlspecialchars($member['name']) . ' - Ward ' . htmlspecialchars($member['ward_number']) . ' - ' . htmlspecialchars(formatRole($member['role'])) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comment2" class="form-label">Comment</label>
                        <textarea class="form-control" id="comment2" name="comment2" rows="3"></textarea>
                    </div>
                    <input type="hidden" id="reference_id" name="reference_id"
                        value="<?= htmlspecialchars($referenceId) ?>">
                    <input type="hidden" id="status" name="status" value="<?= htmlspecialchars($_GET['status']) ?>">
                    <input type="hidden" class="form-control" id="member_id_display2" name="member_id_display2"
                        readonly>
                    <script>
                        document.getElementById('case_handler2').addEventListener('change', function () {
                            const selectedOption = this.options[this.selectedIndex];
                            document.getElementById('member_id_display2').value = this.value; // Set member_id
                        });
                    </script>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="rejectComplaint">Approve</button>
            </div>
            <div class="alert alert-success" role="alert" id="rejectAlert" style="display: none;"></div>
        </div>
    </div>
</div>

<?php require_once 'modalFunctions/rejectRequest.php'; ?>

<!--Comment Modal -->
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">Add Comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="commentForm">
                    <input type="hidden" name="csrf_token" id="csrf_token"
                        value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="mb-3">
                        <label for="comment3" class="form-label">Comment</label>
                        <textarea class="form-control" id="comment3" name="comment3" rows="3"></textarea>
                    </div>
                    <input type="hidden" id="reference_id" name="reference_id"
                        value="<?= htmlspecialchars($referenceId) ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="commentComplaint">Comment</button>
            </div>
            <div class="alert alert-success" role="alert" id="commentAlert" style="display: none;"></div>
        </div>
    </div>
</div>

<?php require_once 'modalFunctions/commentRequest.php'; ?>

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
                        <label for="sendTo" class="form-label">Send Notification To</label>
                        <select class="form-select" id="sendTo" name="sendTo">
                            <option value="plaintiff">Plaintiff
                                (<?php echo htmlspecialchars($complaint_list[0]['plantiff_name']); ?>)</option>
                            <option value="defendant">Defendant
                                (<?php echo htmlspecialchars($complaint_list[0]['defendant_name']); ?>)</option>
                            <option value="both">Both</option>
                        </select>
                    </div>
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
                        <textarea class="form-control" id="comment4" name="comment4"
                            rows="3"><?php echo htmlspecialchars("Hearing Date " . $complaint_list[0]['hearing_date'] . " | Hearing Time " . $complaint_list[0]['hearing_time'] . " | Hearing Location " . $complaint_list[0]['hearing_location']); ?></textarea>
                    </div>
                    <input type="hidden" id="reference_id" name="reference_id"
                        value="<?= htmlspecialchars($referenceId) ?>">
                    <input type="hidden" id="plaintiff_email"
                        value="<?php echo htmlspecialchars($complaint_list[0]['plantiff_email']); ?>">
                    <input type="hidden" id="plaintiff_mobile"
                        value="<?php echo htmlspecialchars($complaint_list[0]['plantiff_mobile']); ?>">
                    <input type="hidden" id="defendant_email"
                        value="<?php echo htmlspecialchars($complaint_list[0]['defendant_email']); ?>">
                    <input type="hidden" id="defendant_mobile"
                        value="<?php echo htmlspecialchars($complaint_list[0]['defendant_mobile']); ?>">
                    <input type="hidden" id="plaintiff_name"
                        value="<?php echo htmlspecialchars($complaint_list[0]['plantiff_name']); ?>">
                    <input type="hidden" id="defendant_name"
                        value="<?php echo htmlspecialchars($complaint_list[0]['defendant_name']); ?>">
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