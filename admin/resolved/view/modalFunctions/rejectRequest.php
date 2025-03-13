<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> -->

<script>
    function submitApplication2() {
        var referenceId = '<?= htmlspecialchars($referenceId, ENT_QUOTES, "UTF-8") ?>';
        var status = "Approved (Resolved)";
        var csrfToken = $("#csrf_token").val().trim();
        var status = '<?= htmlspecialchars($_GET['status'], ENT_QUOTES, "UTF-8") ?>';
        var comment = $("#comment2").val().trim();
        var hearing_date = $("#hearing_date2").val();
        var hearing_time = $("#hearing_time2").val();
        var hearing_location = $("#hearing_location2").val();
        var case_handler = $("#case_handler2").val();
        var member_id = $("#member_id_display2").val();
        var editorName = '<?= isset($_SESSION["name"]) ? htmlspecialchars($_SESSION["name"], ENT_QUOTES, "UTF-8") : "Default Name" ?>';
        var editorEmail = '<?= isset($_SESSION["email"]) ? htmlspecialchars($_SESSION["email"], ENT_QUOTES, "UTF-8") : "Default Email" ?>';
        var editorMobile = '<?= isset($_SESSION["mobile"]) ? htmlspecialchars($_SESSION["mobile"], ENT_QUOTES, "UTF-8") : "Default Mobile" ?>';

        // Create a data object
        var data = {
            reference_id: referenceId,
            comment: comment,
            csrf_token: csrfToken,
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

        // console.log(comment);

        console.log("Reject");

        $.ajax({
            url: 'modalFunctions/submitRejection.php',
            type: "POST",
            data: jsonData, // Send JSON data
            contentType: 'application/json', // Set content type to JSON
            // ... rest of your AJAX options
            success: function(response) {
                console.log('Response from server:', response);
                if (response.status === "success") {
                    $("#rejectAlert").text(response.message).show();
                    $("#rejectComplaint").hide();
                    setTimeout(function() {
                        window.location.href = 'listApplication.php'
                    }, 1000);
                } else {
                    $("#rejectAlert").removeClass("alert-success").addClass("alert-danger")
                        .text(response.message).show();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error occurred:', error);
            },
        });
    }

    // Example: Attach submitApplication to a button click
    document.getElementById('rejectComplaint').addEventListener('click', function(event) {
        event.preventDefault(); // Not strictly necessary for a button, but good practice
        submitApplication2();
    });
</script>