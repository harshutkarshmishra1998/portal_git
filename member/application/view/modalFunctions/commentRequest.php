<script>
    function submitApplication3() {
        var referenceId = '<?= htmlspecialchars($referenceId, ENT_QUOTES, "UTF-8") ?>';
        var commentInput = $("#comment3").val().trim();

        if (commentInput === "") {
            var comment = "Comment Added by member";
        } else {
            var comment = commentInput + " (Comment Added by member)";
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
            csrf_token: csrfToken,
            status: "Pending"
        };

        // Convert data to JSON string
        var jsonData = JSON.stringify(data);

        // console.log("Comment");

        $.ajax({
            url: 'modalFunctions/submitComment.php',
            type: "POST",
            data: jsonData, // Send JSON data
            contentType: 'application/json', // Set content type to JSON
            // ... rest of your AJAX options
            success: function (response) {
                console.log('Response from server:', response);
                if (response.status === "success") {
                    $("#commentAlert").text(response.message).show();
                    $("#commentComplaint").hide();
                    setTimeout(function() {
                        // window.location.href = '../';
                        window.location.reload();
                    }, 1000);
                } else {
                    $("#commentAlert").removeClass("alert-success").addClass("alert-danger")
                        .text(response.message).show();
                }
            },
            error: function (xhr, status, error) {
                console.error('Error occurred:', error);
            },
        });
    }

    // Example: Attach submitApplication to a button click
    document.getElementById('commentComplaint').addEventListener('click', function (event) {
        event.preventDefault(); // Not strictly necessary for a button, but good practice
        submitApplication3();
    });
</script>