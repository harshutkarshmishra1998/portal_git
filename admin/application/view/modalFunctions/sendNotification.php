<script>
    document.getElementById('sendNotificationAction').addEventListener('click', function() {
        const sendTo = document.getElementById('sendTo').value;
        const sendVia = document.getElementById('sendVia').value;
        const comment = document.getElementById('comment4').value; // Corrected ID
        const referenceId = document.getElementById('reference_id').value;
        const plaintiffEmail = document.getElementById('plaintiff_email').value;
        const plaintiffMobile = document.getElementById('plaintiff_mobile').value;
        const defendantEmail = document.getElementById('defendant_email').value;
        const defendantMobile = document.getElementById('defendant_mobile').value;
        const plaintiffName = document.getElementById('plaintiff_name').value;
        const defendantName = document.getElementById('defendant_name').value;
        const csrfToken = document.getElementById('csrf_token').value;

        const data = {
            sendTo: sendTo,
            sendVia: sendVia,
            comment: comment,
            referenceId: referenceId,
            plaintiffEmail: plaintiffEmail,
            plaintiffMobile: plaintiffMobile,
            defendantEmail: defendantEmail,
            defendantMobile: defendantMobile,
            plaintiffName: plaintiffName,
            defendantName: defendantName,
            csrfToken: csrfToken,
        };
        console.log("Notification Data:", data); // Corrected variable

        // Example of a fetch request (adapt to your server-side logic)
        $.ajax({
    url: 'modalFunctions/sendNotificationRequest.php',
    type: "POST",
    data: JSON.stringify(data),
    contentType: 'application/json',
    success: function(response) {
        console.log('Response from server:', response);
        if (response.success === true) { // Check for response.success
            $("#notificationAlert").text("Notification sent successfully!").show(); // Added success message
            $("#sendNotificationAction").hide();
            setTimeout(function() {
                window.location.reload();
            }, 1000);
        } else {
            $("#notificationAlert").removeClass("alert-success").addClass("alert-danger")
                .text(response.error || "An error occurred.").show(); // Use response.error or a default message
        }
    },
    error: function(xhr, status, error) {
        console.error('Error occurred:', error);
        $("#notificationAlert").removeClass("alert-success").addClass("alert-danger")
            .text("An unexpected error occurred.").show();
    },
});
    });
</script>