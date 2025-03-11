<?php
function updateStatus($status)
{
    if ($status === "Approved") {
        return "Approved (1st Date Allotted)"; // Correct spelling
    }

    // Fix: Allow "Alloted" and "Allotted"
    if (preg_match('/^Approved \((\d+)(st|nd|rd|th) Date Allotted\)$/', $status, $matches)) {
        $dateNumber = (int) $matches[1]; // Extract number
        if ($dateNumber < 100) {
            return "Approved (" . ($dateNumber + 1) . getOrdinalSuffix($dateNumber + 1) . " Date Allotted)";
        }
    }

    return $status; // Return original status if no match
}

function getOrdinalSuffix($number)
{
    $lastDigit = $number % 10;
    $lastTwoDigits = $number % 100;

    if ($lastDigit === 1 && $lastTwoDigits !== 11) {
        return "st";
    } elseif ($lastDigit === 2 && $lastTwoDigits !== 12) {
        return "nd";
    } elseif ($lastDigit === 3 && $lastTwoDigits !== 13) {
        return "rd";
    } else {
        return "th";
    }
}
?>

<script>
    function submitApplication1() {
        var referenceId = '<?= htmlspecialchars($referenceId, ENT_QUOTES, "UTF-8") ?>';
        var status = '<?= htmlspecialchars($_GET['status'], ENT_QUOTES, "UTF-8") ?>';
        var comment = $("#comment1").val().trim();
        var hearing_date = $("#hearing_date").val();
        var hearing_time = $("#hearing_time").val();
        var hearing_location = $("#hearing_location").val();
        var case_handler = $("#case_handler").val();
        var member_id = $("#member_id_display").val();
        var editorName = '<?= isset($_SESSION["name"]) ? htmlspecialchars($_SESSION["name"], ENT_QUOTES, "UTF-8") : "Default Name" ?>';
        var editorEmail = '<?= isset($_SESSION["email"]) ? htmlspecialchars($_SESSION["email"], ENT_QUOTES, "UTF-8") : "Default Email" ?>';
        var editorMobile = '<?= isset($_SESSION["mobile"]) ? htmlspecialchars($_SESSION["mobile"], ENT_QUOTES, "UTF-8") : "Default Mobile" ?>';

        // Create a data object
        var data = {
            reference_id: referenceId,
            comment: comment,
            hearing_date: hearing_date,
            hearing_time: hearing_time,
            hearing_location: hearing_location,
            case_handler: case_handler,
            member_id:member_id,
            editor_name: editorName,
            editor_email: editorEmail,
            editor_mobile: editorMobile,
            status: status
        };

        // Convert data to JSON string
        var jsonData = JSON.stringify(data);

        // console.log("Approval");

        $.ajax({
            url: 'modalFunctions/submitApproval.php',
            type: "POST",
            data: jsonData, // Send JSON data
            contentType: 'application/json', // Set content type to JSON
            // ... rest of your AJAX options
            success: function(response) {
                console.log('Response from server:', response);
                if (response.status === "success") {
                    $("#approveAlert").text(response.message).show();
                    $("#approveComplaint").hide();
                    setTimeout(function() {
                        // window.location.reload();
                        window.location.href = 'listApplication.php'
                    }, 1000);
                } else {
                    $("#approveAlert").removeClass("alert-success").addClass("alert-danger")
                        .text(response.message).show();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error occurred:', error);
            },
        });
    }

    // Example: Attach submitApplication to a button click
    document.getElementById('approveComplaint').addEventListener('click', function(event) {
        event.preventDefault(); // Not strictly necessary for a button, but good practice
        submitApplication1();
    });
</script>