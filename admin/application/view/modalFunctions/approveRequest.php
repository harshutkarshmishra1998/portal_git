<script>
    function submitApplication1() {
        var referenceId = '<?= htmlspecialchars($referenceId, ENT_QUOTES, "UTF-8") ?>';
        // var comment = "Approved " + $("#comment1").val().trim();
        var commentInput = $("#comment1").val().trim();

        if (commentInput === "") {
            var comment = "Approved by Admin";
        } else {
            var comment = commentInput + " (Approved by Admin)";
        }
        var editorName = '<?= isset($_SESSION["name"]) ? htmlspecialchars($_SESSION["name"], ENT_QUOTES, "UTF-8") : "Default Name" ?>';
        var editorEmail = '<?= isset($_SESSION["email"]) ? htmlspecialchars($_SESSION["email"], ENT_QUOTES, "UTF-8") : "Default Email" ?>';
        var editorMobile = '<?= isset($_SESSION["mobile"]) ? htmlspecialchars($_SESSION["mobile"], ENT_QUOTES, "UTF-8") : "Default Mobile" ?>';
        var csrfToken = $("#csrf_token").val().trim();

        // Create a data object
        var data = {
            reference_id: referenceId,
            comment: comment,
            editor_name: editorName,
            editor_email: editorEmail,
            editor_mobile: editorMobile,
            status: "Approved",
            csrf_token: csrfToken
        };

        // Convert data to JSON string
        var jsonData = JSON.stringify(data);

        // console.log(jsonData);

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
                        window.location.href = 'listApplication.php';
                        // window.location.reload();
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