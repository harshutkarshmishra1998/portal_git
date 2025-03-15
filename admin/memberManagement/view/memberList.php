<?php require_once '../../modules/header.php'; ?>
<?php require_once 'sqlQueries.php'; ?>

<body>
    <?php require_once '../../modules/navbar.php'; ?>
    <?php require_once '../../modules/sidebar.php'; ?>

    <div class="content" id="mainContent">
        <h4 class="mb-4">Member Management</h4>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="fieldFilter" class="form-label">Select Field to Filter:</label>
                <select id="fieldFilter" class="form-select">
                    <option value="">-- Select Field --</option>
                    <option value="0">Full Name</option>
                    <option value="1">Email</option>
                    <option value="2">Mobile Number</option>
                    <option value="3">Ward Number</option>
                    <option value="4">Role</option>
                    <option value="5">Created Date</option>
                    <option value="6">Active</option>
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
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Ward Number</th>
                    <th>Role</th>
                    <th>Created Date</th>
                    <th>Active Status</th>
                    <th class="noExport">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $stmt->fetch()) {
                    $role = $row['role'];
                    if ($role === 'ward_member') {
                        $role = 'Ward Member';
                    } elseif ($role === 'case_handler') {
                        $role = 'Case Handler';
                    } elseif ($role === 'both') {
                        $role = 'Both';
                    }

                    echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['mobile']}</td>
                        <td>{$row['ward_number']}</td>
                        <td>{$role}</td>
                        <td>{$row['created_at']}</td>
                        <td>{$row['active']}</td>
                        <td>
                            <button class='btn btn-warning btn-sm' 
                                    onclick=\"updateMember({
                                        email: '" . htmlspecialchars($row['email'], ENT_QUOTES) . "',
                                        name: '" . htmlspecialchars($row['name'], ENT_QUOTES) . "',
                                        mobile: '" . htmlspecialchars($row['mobile'], ENT_QUOTES) . "',
                                        ward_number: '" . htmlspecialchars($row['ward_number'], ENT_QUOTES) . "',
                                        role: '" . htmlspecialchars($row['role'], ENT_QUOTES) . "',
                                        csrf_token: '" . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES) . "',
                                        hashed_password: '" . htmlspecialchars($row['password'], ENT_QUOTES) . "'
                                    })\">
                                Update
                            </button>
                            <button class='btn btn-info btn-sm my-2' 
                                    onclick=\"deleteMember({
                                        email: '" . htmlspecialchars($row['email'], ENT_QUOTES) . "',
                                        active: '" . htmlspecialchars($row['active'], ENT_QUOTES) . "',
                                        csrf_token: '" . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES) . "',
                                    })\">
                                Activate/Deactivate
                            </button>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
        <button id="exportCSV" class="btn btn-primary mt-3">Export CSV</button>
        <button class="btn btn-info mt-3"><a href="memberCreate.php">Add Member</a></button>
    </div>

    <?php require_once 'memberList.js.php'; ?>

    <?php require_once '../../modules/footer.php'; ?>

</body>

</html>