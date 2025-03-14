<!-- jQuery UI for Datepicker -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    $(function () {
        // Initialize datepicker for both fields with YYYY-MM-DD format
        $("#from_date, #to_date").datepicker({
            dateFormat: "yy-mm-dd" // jQuery UI uses "yy" for a four-digit year
        });
    });
</script>

<!-- Chart 1: Single Bar Chart -->
<script>
    // Define a global default font and color (optional)
    Chart.defaults.font.family = 'Arial, sans-serif';
    Chart.defaults.color = '#333'; // Axis/label color

    const complaintStats = <?php echo $jsData; ?>; // Data from PHP
    const labels = Object.keys(complaintStats);
    const values = Object.values(complaintStats);

    const chartData = {
        labels: labels,
        datasets: [{
            label: 'Complaint Statistics',
            data: values,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };

    const ctx = document.getElementById('myChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            // High-DPI rendering
            devicePixelRatio: window.devicePixelRatio || 2,
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { display: false },
                    title: {
                        display: true,
                        text: 'Total Count',
                        font: {
                            size: 14
                        }
                    },
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    title: {
                        display: true,
                        // text: 'Months',
                        font: {
                            size: 14
                        }
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false // Hide legend since there's only one dataset
                },
                title: {
                    display: true,
                    // text: 'Complaint Sample Type Count',
                    font: {
                        size: 16
                    }
                }
            }
        }
    });
</script>

<!-- Chart 2: Stacked Bar Chart -->
<script>
    (function () {
        // PHP data for second chart
        var jsData2 = <?php echo $jsData2; ?>;

        // Extract month-year as x-axis labels
        var monthLabels = Object.keys(jsData2);

        // Extract all unique status categories (approve, pending, reject, etc.)
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
            // Add more if you have other statuses
        };

        // Build datasets for Chart.js
        var datasets = statuses.map(function (status) {
            var data = monthLabels.map(function (month) {
                return jsData2[month][status] || 0; // default to 0 if missing
            });
            return {
                label: status.charAt(0).toUpperCase() + status.slice(1),
                data: data,
                backgroundColor: statusColors[status] || 'rgba(100, 100, 100, 0.8)',
                stack: 'Stack 0'
            };
        });

        // Create the stacked bar chart
        var ctx2 = document.getElementById('myChart2').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: datasets
            },
            options: {
                devicePixelRatio: window.devicePixelRatio || 2,
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        // text: 'Case Status By Month',
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        stacked: true,
                        title: {
                            display: true,
                            // text: 'Month-Year',
                            font: {
                                size: 14
                            }
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        grid: { display: false },
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Count',
                            font: {
                                size: 14
                            }
                        },
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    })();
</script>