<?php include './include/dashboard_header.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->

<body>
    <?php include './include/dashboard_navbar.php'; ?>
    <?php include './include/dashboard_sidebar.php'; ?>

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
                            <th>Action</th>
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
                            <td>A</td>
                        </tr>
                        <tr>
                            <td>John Doe</td>
                            <td>Software Engineer</td>
                            <td>New York</td>
                            <td>30</td>
                            <td>2018-06-12</td>
                            <td>$120,000</td>
                            <td>A</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable();
            var selectedRow = null;

            // Function to open SweetAlert2 modal
            function openSweetAlert(row, currentValue) {
                Swal.fire({
                    title: 'Edit Action',
                    html: `
        <select id="modalDropdown" class="swal2-input">
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
        </select>
    `,
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    didOpen: () => {
                        document.getElementById('modalDropdown').value = currentValue;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        var newValue = document.getElementById('modalDropdown').value;
                        if (newValue === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Invalid Selection',
                                text: 'Please select a valid option.'
                            });
                        } else {
                            row.find('.edit-dropdown-btn')
                                .text(newValue)
                                .data('value', newValue);
                            table.cell(row, 6).data(newValue).draw();
                        }
                    }
                });

            }

            // Open modal when button is clicked
            $('#example tbody').on('click', '.edit-dropdown-btn', function() {
                selectedRow = $(this).closest('tr');
                var currentValue = $(this).data('value');
                openSweetAlert(selectedRow, currentValue);
            });

            // Open modal when action column value is clicked
            $('#example tbody').on('click', 'td:nth-child(7)', function() {
                selectedRow = $(this).closest('tr');
                var currentValue = selectedRow.find('.edit-dropdown-btn').data('value');
                openSweetAlert(selectedRow, currentValue);
            });
        });
    </script>

</body>

<?php
$disableVendorBundle = true;
include './include/dashboard_footer.php';
?>

</html>