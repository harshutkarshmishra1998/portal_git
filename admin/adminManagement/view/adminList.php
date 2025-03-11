<?php require_once '../../modules/header.php'; ?>
<?php require_once 'sqlQueries.php'; ?>
?>

<body>
    <?php require_once '../../modules/navbar.php'; ?>
    <?php require_once '../../modules/sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="content" id="mainContent">
        <h4 class="mb-4">Admin Management</h4>

        <!-- Custom Filters Section -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="fieldFilter" class="form-label">Select Field to Filter:</label>
                <select id="fieldFilter" class="form-select">
                    <option value="">-- Select Field --</option>
                    <option value="0">Full Name</option>
                    <option value="1">Email</option>
                    <option value="2">Mobile Number</option>
                    <option value="3">Created Date</option>
                </select>
            </div>
            <div class="col-md-4" id="valueFilterContainer">
                <label for="valueFilter" class="form-label">Select Value:</label>
                <select id="valueFilter" class="form-select">
                    <option value="">-- Select Value --</option>
                </select>
            </div>
            <!-- Align the clear button at the bottom -->
            <div class="col-md-4 d-flex align-items-end">
                <button id="clearFilters" class="btn btn-secondary w-100">Clear Filters</button>
            </div>
        </div>

        <!-- DataTable -->
        <table id="dataTable" class="display table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Created Date</th>
                    <th class="noExport">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
            <td>{$row['name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['mobile']}</td>
            <td>{$row['created_at']}</td>
            <td>
                <button class='btn btn-warning btn-sm' 
                        onclick=\"updateAdmin({
                            email: '" . htmlspecialchars($row['email'], ENT_QUOTES) . "',
                            name: '" . htmlspecialchars($row['name'], ENT_QUOTES) . "',
                            mobile: '" . htmlspecialchars($row['mobile'], ENT_QUOTES) . "',
                            hashed_password: '" . htmlspecialchars($row['password'], ENT_QUOTES) ."'";
                    echo "})\">
                    Update
                </button>
                <button class='btn btn-danger btn-sm' 
                        onclick=\"deleteAdmin('" . htmlspecialchars($row['email'], ENT_QUOTES) . "')\">
                    Delete
                </button>
            </td>
        </tr>";
                }
                ?>
            </tbody>
        </table>
        <!-- CSV Export Button -->
        <button id="exportCSV" class="btn btn-primary mt-3">Export CSV</button>
        <button class="btn btn-info mt-3"><a href="adminCreate.php">Add Admin</a></button>
    </div>

    <?php require_once 'adminList.js.php'; ?>

    <?php require_once '../../modules/footer.php'; ?>

</body>

</html>