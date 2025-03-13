<?php
require_once __DIR__ . '/../../modules/header.php';
require_once __DIR__ . '/sqlQueries.php';
?>

<body>
    <?php require_once __DIR__ . '/../../modules/navbar.php'; ?>
    <?php require_once __DIR__ . '/../../modules/sidebar.php'; ?>

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
                <?php while ($row = $stmt->fetch()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['mobile'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <form method="POST" action="adminUpdate.php" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="email" value="<?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="mobile" value="<?= htmlspecialchars($row['mobile'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="hashed_password" value="<?= htmlspecialchars($row['password'], ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit" class="btn btn-warning btn-sm">Update</button>
                            </form>

                            <form method="POST" action="adminDelete.php" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="email" value="<?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this admin?');">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- CSV Export Button -->
        <button id="exportCSV" class="btn btn-primary mt-3">Export CSV</button>
        <a href="adminCreate.php" class="btn btn-info mt-3">Add Admin</a>
    </div>

    <?php require_once __DIR__ . '/adminList.js.php'; ?>
    <?php require_once __DIR__ . '/../../modules/footer.php'; ?>
</body>
</html>
