<script>
    function updateMember(data) {
        // Create a form and submit it to updateAdmin.php
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'memberUpdate.php';

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

    function deleteMember(data) {
        if (confirm("Are you sure you want to active/deactivate this member?")) {
            // Create a form and submit it to deleteAdmin.php
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'memberDelete.php';

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
    }
</script>