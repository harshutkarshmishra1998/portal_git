<?php include './include/dashboard_header.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<body>
    <?php include './include/dashboard_navbar.php'; ?>
    <?php include './include/dashboard_sidebar.php'; ?>

    <!-- Main Panel -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div style="overflow-x: auto; width: 100%;">
                <table id="example" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Jane Smith</td>
                            <td>Product Manager</td>
                            <td>London</td>
                            <td>35</td>
                            <td>2016-03-29</td>
                            <td>$150,000</td>
                        </tr>
                        <tr>
                            <td>John Doe</td>
                            <td>Software Engineer</td>
                            <td>New York</td>
                            <td>30</td>
                            <td>2018-06-12</td>
                            <td>$120,000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- Closing main-panel -->

    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true, // Enable responsiveness
                autoWidth: false, // Prevent automatic width shrinking
                paging: true, // Enable pagination
                searching: true, // Show search bar
                ordering: true, // Enable sorting
                lengthChange: true, // Allow changing number of rows per page
                info: true, // Show entry info
                language: {
                    paginate: {
                        previous: "«",
                        next: "»"
                    }
                }
            });
        });
    </script>

</body>

<?php
$disableVendorBundle = true;
include './include/dashboard_footer.php';
?>

</html>