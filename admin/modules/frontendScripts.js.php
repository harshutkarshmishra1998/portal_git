<script>
// Sidebar toggle for smaller screens (and possibly desktop)
const sidebarToggleBtn = document.getElementById("sidebarToggle");
const sidebarMenu = document.getElementById("sidebarMenu");
const mainContent = document.getElementById("mainContent");

function toggleSidebar() {
    // If mobile mode (width < 992px), slide in/out
    if (window.innerWidth < 992) {
        sidebarMenu.classList.toggle("show");
    } else {
        // Desktop mode: shift content
        sidebarMenu.classList.toggle("show");
        mainContent.classList.toggle("shifted");
    }
}

sidebarToggleBtn.addEventListener("click", toggleSidebar);

// Close sidebar if user clicks outside it in mobile mode
document.addEventListener("click", (event) => {
    if (window.innerWidth < 992) {
        if (
            sidebarMenu.classList.contains("show") &&
            !sidebarMenu.contains(event.target) &&
            !sidebarToggleBtn.contains(event.target)
        ) {
            sidebarMenu.classList.remove("show");
        }
    }
});

// Sidebar active link toggling
document.addEventListener("DOMContentLoaded", () => {
    const navLinks = document.querySelectorAll(".sidebar .nav-link");
    navLinks.forEach(link => {
        link.addEventListener("click", () => {
            navLinks.forEach(item => item.classList.remove("active"));
            link.classList.add("active");
        });
    });
});

// DataTables + Filters
$(document).ready(function () {
    // Export CSV
    var table = $('#dataTable').DataTable({
        dom: 'lfrtip',  // No visible CSV button in the DOM
        buttons: [{
            extend: 'csvHtml5',
            title: 'Complaint_Data',
            exportOptions: {
                // Exclude columns with the class "noExport"
                columns: ':not(.noExport)'
            },
            // Hide the button using a CSS class
            className: 'd-none'
        }],
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[0, 'desc']] // Sort the first column in descending order
    });

    // Field Filter: When a field is selected, automatically clear old filters
    $('#fieldFilter').on('change', function () {
        // Clear any existing filters
        table.columns().search('');
        table.draw();

        var colIndex = $(this).val();
        if (colIndex === "") {
            // Hide the second dropdown
            $('#valueFilterContainer').hide();
            return;
        }
        // Get unique values from the chosen column
        var data = table.column(colIndex).data().unique().sort();
        var options = '<option value="">-- Select Value --</option>';
        data.each(function (value) {
            options += '<option value="' + value + '">' + value + '</option>';
        });
        $('#valueFilter').html(options);
        $('#valueFilterContainer').show();
    });

    // Value Filter: Filter the table when an option is selected
    $('#valueFilter').on('change', function () {
        var colIndex = $('#fieldFilter').val();
        var searchVal = $(this).val();
        table.column(colIndex).search(searchVal).draw();
    });

    // Clear Filters
    $('#clearFilters').on('click', function () {
        $('#fieldFilter').val('');
        $('#valueFilter').html('<option value="">-- Select Value --</option>');
        $('#valueFilterContainer').hide();
        table.columns().search('').draw();
    });

    // CSV export button
    $('#exportCSV').on('click', function () {
        // table.button('.buttons-csv').trigger();
        table.button(0).trigger();
    });
});
</script>