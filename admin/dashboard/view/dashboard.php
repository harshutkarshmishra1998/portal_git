<?php
// // Tester
// session_start();
// print_r($_SESSION);
// die();
?>


<?php require_once '../../modules/header.php'; ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Update field session variables if present
  if (isset($_GET['field1']) || isset($_GET['field2'])) {
    $_SESSION['field1'] = $_GET['field1'];
    // $_SESSION['field2'] = $_GET['field2'];
  }

  // Update date session variables if present
  if (isset($_GET['from_date']) || isset($_GET['to_date'])) {
    $_SESSION['from_date'] = $_GET['from_date'];
    $_SESSION['to_date'] = $_GET['to_date'];
  }
}
?>

<body>
  <?php require_once '../../modules/navbar.php'; ?>
  <?php require_once '../../modules/sidebar.php'; ?>

  <div class="content" id="mainContent">
    <h4 class="mb-4">Dashboard</h4>

    <?php require_once 'absoluteCount.php'; ?>

    <!-- Chart -->
    <div class="card mb-4">
      <div class="card-header">
        <h6 class="mb-0">Chart</h6>
      </div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-12">
            <form id="fieldForm" method="GET" action="" class="row g-3 align-items-end">
              <div class="col-md-3">
                <label for="fieldFilter" class="form-label">Select Field:</label>
                <select id="fieldFilter1" class="form-select" name="field1">
                  <!-- <option value="Status" <?php //if (isset($_SESSION['field1']) && $_SESSION['field1'] == 'Status')
                  echo 'selected'; ?>>Status</option> -->
                  <option value="Type" <?php if (isset($_SESSION['field1']) && $_SESSION['field1'] == 'Type')
                    echo 'selected'; ?>>Type</option>
                  <option value="Ward" <?php if (isset($_SESSION['field1']) && $_SESSION['field1'] == 'Ward')
                    echo 'selected'; ?>>Ward</option>
                  <option value="Time" <?php if (isset($_SESSION['field1']) && $_SESSION['field1'] == 'Time')
                    echo 'selected'; ?>>Time</option>
                </select>
              </div>
              <!-- <div class="col-md-3">
                <label for="fieldFilter" class="form-label">Select Field:</label>
                <select id="fieldFilter2" class="form-select" name="field2">
                  <option value="Status" <?php //if (isset($_SESSION['field2']) && $_SESSION['field2'] == 'Status')
                  echo 'selected'; ?>>Status</option>
                  <option value="Type" <?php //if (isset($_SESSION['field2']) && $_SESSION['field2'] == 'Type')
                  echo 'selected'; ?>>Type</option>
                  <option value="Ward" <?php //if (isset($_SESSION['field2']) && $_SESSION['field2'] == 'Ward')
                  echo 'selected'; ?>>Ward</option>
                  <option value="Time" <?php //if (isset($_SESSION['field2']) && $_SESSION['field2'] == 'Time')
                  echo 'selected'; ?>>Time</option>
                </select>
              </div> -->
              <div class="col-md-3">
                <button class="btn btn-secondary w-100" type="submit">Filter</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Add charts here -->
        <?php require_once '1dCharts.php'; ?>
        <?php //print_r(array_key_first(json_decode($jsData, true))); ?>
        <?php require_once '2dCharts.php'; ?>

        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <div class="card p-3 chart-container">
              <canvas id="myChart"></canvas>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card p-3 chart-container">
              <canvas id="myChart2"></canvas>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Pending Applications -->
    <div class="card mb-4">
      <div class="card-header">
        <h6 class="mb-0">Pending Applications</h6>
      </div>
      <?php //print_r($_SESSION); ?>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-12">
            <form id="dateForm" method="GET" action="" class="row g-3 align-items-end">
              <div class="col-md-3">
                <label for="from_date" class="form-label">From Date:</label>
                <input type="text" id="from_date" name="from_date" class="form-control" placeholder="dd-mm-yyyy"
                  value="<?php echo isset($_SESSION['from_date']) ? htmlspecialchars($_SESSION['from_date']) : ''; ?>">
              </div>
              <div class="col-md-3">
                <label for="to_date" class="form-label">To Date:</label>
                <input type="text" id="to_date" name="to_date" class="form-control" placeholder="dd-mm-yyyy"
                  value="<?php echo isset($_SESSION['to_date']) ? htmlspecialchars($_SESSION['to_date']) : ''; ?>">
              </div>
              <div class="col-md-3">
                <button class="btn btn-secondary w-100" type="submit">Filter</button>
              </div>
            </form>
          </div>
        </div>
        <?php require_once 'sqlQuery.php'; ?>
        <?php //print_r($from_date); ?>
        <?php //print_r($to_date); ?>
        <!-- DataTable -->
        <table id="dataTable" class="display table table-striped" style="width:100%">
          <thead>
            <tr>
              <th>Reference ID</th>
              <th>Title</th>
              <th>Subject</th>
              <th>Type</th>
              <th>Created Date</th>
              <th>Status</th>
              <th class="noExport">Actions</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            <?php foreach ($entries as $complaint): ?>
              <tr>
                <td><?= htmlspecialchars($complaint['reference_id']) ?></td>
                <td><?= htmlspecialchars($complaint['title']) ?></td>
                <td><?= htmlspecialchars($complaint['subject']) ?></td>
                <td><?= htmlspecialchars($complaint['type']) ?></td>
                <td><?= htmlspecialchars($complaint['created_at']) ?></td>
                <td><?= htmlspecialchars($complaint['status'] ?? 'N/A') ?></td>
                <td>
                  <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal">
                    <a href="../../application/view/detailApplication.php?reference_id=<?= urlencode($complaint['reference_id']) ?>&status=<?= urlencode($complaint['status']) ?>"
                      target="_blank">Details</a>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <!-- CSV Export Button -->
        <button id="exportCSV" class="btn btn-primary mt-3">Export CSV</button>
      </div>
    </div>
  </div>

  <?php require_once '../../modules/footer.php'; ?>

</body>

</html>

<?php include 'dashboardScripts.php'; ?>
<?php include 'dashboardCss.php'; ?>