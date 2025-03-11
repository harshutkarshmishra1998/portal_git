<script>
    function updateAdmin(data) {
        // Create a form and submit it to updateAdmin.php
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'adminUpdate.php';

        // Add hidden input fields for each data property
        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = data[key];
                form.appendChild(input);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }

    function deleteAdmin(email) {
        if (confirm("Are you sure you want to delete this admin?")) {
            // Create a form and submit it to deleteAdmin.php
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'adminDelete.php';

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'email';
            input.value = email;
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>