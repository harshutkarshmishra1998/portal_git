<?php //print_r($statusHistory[0]['status']); 
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
                <option value="3">Editor Name</option>
                <option value="4">Editor Email</option>
                <option value="5">Editor Mobile</option>
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
                <th>Editor Name</th>
                <th>Editor Email</th>
                <th>Editor Mobile</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($statusHistory as $status): ?>
                <tr>
                    <td><?= htmlspecialchars($status['created_at']) ?: '-' ?></td>
                    <td><?= htmlspecialchars($status['status']) ?: '-' ?></td>
                    <td><?= htmlspecialchars($status['comment']) ?: '-' ?></td>
                    <td><?= htmlspecialchars($status['editor_name']) ?: '-' ?></td>
                    <td><?= htmlspecialchars($status['editor_email']) ?: '-' ?></td>
                    <td><?= htmlspecialchars($status['editor_mobile']) ?: '-' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php require_once 'actionButtons.php'; 
    ?>

</div>