<?php require_once '../../modules/header.php'; ?>
<?php require_once 'sqlQuery.php'; ?>

<body>
    <?php require_once '../../modules/navbar.php'; ?>
    <?php require_once '../../modules/sidebar.php'; ?>

    <div class="content" id="mainContent">
        <h4 class="mb-4">Complaint Dashboard</h4>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="fieldFilter" class="form-label">Select Field to Filter:</label>
                <select id="fieldFilter" class="form-select">
                    <option value="">-- Select Field --</option>
                    <option value="0">Created At</option>
                    <option value="1">Reference ID</option>
                    <option value="5">Educational Qualification</option>
                    <option value="6">Approved</option>
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
                    <th>Reference ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Educational Qualification</th>
                    <th>Approved</th>
                    <th class="noExport">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($complaint_list as $complaint): ?>
                    <tr>
                        <td><?= htmlspecialchars($complaint['created_at']) ?></td>
                        <td><?= htmlspecialchars($complaint['reference_id']) ?></td>
                        <td><?= htmlspecialchars($complaint['full_name']) ?></td>
                        <td><?= htmlspecialchars($complaint['email']) ?></td>
                        <td><?= htmlspecialchars($complaint['mobile_number']) ?></td>
                        <td><?= htmlspecialchars($complaint['educational_qualification']) ?></td>
                        <td><?= htmlspecialchars($complaint['approved']) ?></td>
                        <td>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal">
                                <a href="detailApplication.php?reference_id=<?= urlencode($complaint['reference_id'])?>"
                                    target="_blank">Details</a>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button id="exportCSV" class="btn btn-primary mt-3">Export CSV</button>
    </div>

    <?php require_once '../../modules/footer.php'; ?>

</body>

</html>