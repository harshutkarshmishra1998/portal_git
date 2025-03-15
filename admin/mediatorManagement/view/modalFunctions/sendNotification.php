<script>
    document.getElementById('sendNotificationAction').addEventListener('click', function () {
        const sendVia = document.getElementById('sendVia').value;
        const comment = document.getElementById('comment4').value; // Corrected ID
        const referenceId = document.getElementById('reference_id').value;
        const email = document.getElementById('email').value;
        const mobile = document.getElementById('mobile_number').value;
        const name = document.getElementById('full_name').value;
        const csrfToken = document.getElementById('csrf_token').value;

        const data = {
            sendVia: sendVia,
            comment: comment,
            referenceId: referenceId,
            email: email,
            mobile: mobile,
            name: name,
            csrfToken: csrfToken,
        };
        // console.log("Notification Data:", data); // Corrected variable

        // Example of a fetch request (adapt to your server-side logic)
        $.ajax({
            url: 'modalFunctions/sendNotificationRequest.php',
            type: "POST",
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function (response) {
                console.log('Response from server:', response);
                if (response.success === true) { // Check for response.success
                    $("#notificationAlert").text("Notification sent successfully!").show(); // Added success message
                    $("#sendNotificationAction").hide();
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                } else {
                    $("#notificationAlert").removeClass("alert-success").addClass("alert-danger")
                        .text(response.error || "An error occurred.").show(); // Use response.error or a default message
                }
            },
            error: function (xhr, status, error) {
                console.error('Error occurred:', error);
                $("#notificationAlert").removeClass("alert-success").addClass("alert-danger")
                    .text("An unexpected error occurred.").show();
            },
        });
    });
</script>