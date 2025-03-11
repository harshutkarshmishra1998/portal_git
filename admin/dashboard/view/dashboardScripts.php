<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    $(function () {
        // Initialize datepicker for both fields with YYYY-MM-DD format
        $("#from_date, #to_date").datepicker({
            dateFormat: "yy-mm-dd" // jQuery UI uses "yy" for a four-digit year
        });
    });
</script>

<script>
    const complaintStats = <?php echo $jsData; ?>; // Get data from PHP

    const labels = Object.keys(complaintStats);
    const values = Object.values(complaintStats);

    const chartData = {
        labels: labels,
        datasets: [{
            label: 'Complaint Statistics',
            data: values,
            backgroundColor: 'rgba(54, 162, 235, 0.2)', // Customize color
            borderColor: 'rgba(54, 162, 235, 1)',      // Customize color
            borderWidth: 1
        }]
    };

    const ctx = document.getElementById('myChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar', // Use bar chart
        data: chartData,
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { display: false },
                    title: {
                        display: true,
                        text: 'Total Count' // Y-axis label
                    },
                    ticks: {
                        stepSize: 1 // Force integer steps
                    }
                },
                x: {
                    grid: { display: false },
                    title: {
                        display: true,
                        // text: "Hello" // X-axis label
                    }
                }
            },
            responsive: true,
            plugins: {
                legend: {
                    display: false, // Hide legend since there's only one bar
                },
                title: {
                    display: true,
                    // text: 'Complaint Sample Type Count'
                }
            }
        }
    });
</script>

<script>
    (function () {
        // Assume jsData2 is already defined as a JSON object via PHP
        var jsData2 = <?php echo $jsData2 ?>;

        // var jsData2 = { 
        //     "Dec-24": { "approve": 7, "pending": 7, "reject": 8 }, 
        //     "Feb-25": { "approve": 12, "pending": 2, "reject": 4 }, 
        //     "Jan-25": { "approve": 9, "pending": 9, "reject": 5 }, 
        //     "Mar-25": { "approve": 9, "pending": 8, "reject": 4 }, 
        //     "Nov-24": { "approve": 4, "pending": 9, "reject": 6 }
        // }; 
        // This is just for testing. Remove in production.

        // Extract month-year as x-axis labels
        var monthLabels = Object.keys(jsData2);

        // Extract all unique status categories (approve, pending, reject)
        var statusSet = new Set();
        monthLabels.forEach(function (month) {
            Object.keys(jsData2[month]).forEach(function (status) {
                statusSet.add(status);
            });
        });
        var statuses = Array.from(statusSet);

        // Define custom colors for each status
        var statusColors = {
            approve: 'rgba(54, 162, 235, 0.8)',   // Blue
            pending: 'rgba(255, 206, 86, 0.8)',   // Yellow
            reject: 'rgba(255, 99, 132, 0.8)'     // Red
        };

        // Build datasets for Chart.js
        var datasets = statuses.map(function (status) {
            var data = monthLabels.map(function (month) {
                // Default to 0 if a status doesn't exist for a month
                return jsData2[month][status] || 0;
            });

            return {
                label: status.charAt(0).toUpperCase() + status.slice(1),  // Capitalize labels
                data: data,
                backgroundColor: statusColors[status] || 'rgba(100, 100, 100, 0.8)',
                stack: 'Stack 0'
            };
        });

        // Create the Chart.js stacked bar chart
        var ctx2 = document.getElementById('myChart2').getContext('2d');
        var myChart2 = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: datasets
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        // text: 'Case Status By Month'
                    }
                },
                responsive: true,
                scales: {
                    x: {
                        grid: { display: false },
                        stacked: true,
                        title: {
                            display: true,
                            // text: 'Month-Year'
                        }
                    },
                    y: {
                        grid: { display: false },
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Count'
                        },
                        ticks: {
                            stepSize: 1 // Force integer steps
                        }
                    }
                }
            }
        });
    })();
</script>
