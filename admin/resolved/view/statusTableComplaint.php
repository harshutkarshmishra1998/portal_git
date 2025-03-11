<?php // print_r($statusHistory[0]); ?>

<?php
function formatRole($role)
{
    $role = strtolower($role); // Convert to lowercase for case-insensitive matching
    switch ($role) {
        case 'ward_member':
            return 'Ward Member';
        case 'case_handler':
            return 'Case Handler';
        case 'both':
            return 'Both';
        default:
            return ucfirst(str_replace('_', ' ', $role)); // Capitalize and replace underscores with spaces for other roles
    }
}
?>

<div class="content" id="mainContent">
    <h5 class="mb-3">Complaint Status History</h5>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="fieldFilter" class="form-label">Select Field to Filter:</label>
            <select id="fieldFilter" class="form-select">
                <option value="">-- Select Field --</option>
                <option value="0">Created Date</option>
                <option value="1">Status</option>
                <option value="2">Comment</option>
                <option value="3">Hearing Details</option>
                <option value="4">Case Handler | Role | Ward Number</option>
                <option value="5">Editor Name | Email | Mobile</option>
            </select>
        </div>
        <div class="col-md-4" id="valueFilterContainer">
            <label for="valueFilter" class="form-label">Select Value:</label>
            <select id="valueFilter" class="form-select">
                <option value="">-- Select Value --</option>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button id="clearFilters" class="btn btn-secondary w-100">Clear Filters</button>
        </div>
    </div>

    <table id="dataTable" class="display table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Created Date</th>
                <th>Status</th>
                <th>Comment</th>
                <th>Hearing Details</th>
                <th>Case Handler | Role | Ward Number</th>
                <th>Editor Name | Email | Mobile</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($statusHistory as $status): ?>
                <tr>
                    <td><?= htmlspecialchars($status['created_at']) ?: '-' ?></td>
                    <td><?= htmlspecialchars($status['status']) ?: '-' ?></td>
                    <td><?= htmlspecialchars($status['comment']) ?: '-' ?></td>
                    <td><?= htmlspecialchars($status['hearing_date']." ".$status['hearing_time']." ".$status['hearing_location']) ?: '-' ?></td>
                    <td><?= htmlspecialchars($status['name']." | ".formatRole($status['role'])." | ".$status['ward_number']) ?: '-' ?></td>
                    <td><?= htmlspecialchars($status['editor_name']." | ".$status['editor_email']." | ".$status['editor_mobile']) ?: '-' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php include 'actionButtons.php'; 
    ?>

</div>