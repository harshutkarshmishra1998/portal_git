<script nonce="<?= $nonce ?>">
    function submitApplication2() {
        // Gather three fields: reference_id, plantiff.mobile, and plantiff.email
        const refId = document.getElementById('reference_id').value.trim();
        const mobile = document.getElementById('plantiff_mobile').value.trim();
        const email = document.getElementById('plantiff_email').value.trim();
        const csrf_token = document.getElementById('csrf_token').value.trim();
        const hashed_refID = document.getElementById('hashed_reference_id').value.trim();

        // Create a JSON object with these fields
        const formJson = {
            reference_id: refId,
            plantiff_mobile: mobile,
            plantiff_email: email,
            csrf_token: csrf_token,
            hashed_reference_id: hashed_refID
        };

        // console.log(formJson);
        // return;

        // ✅ Function to check if any field is empty
        function validateForm(formJson) {
            let missingFields = [];

            // Function to check fields
            function checkFields(obj, parent = '') {
                for (let key in obj) {
                    if (typeof obj[key] === 'object') {
                        checkFields(obj[key], key); // Recursively check nested objects
                    } else {
                        if (obj[key] === '') {
                            // Create a user-friendly name like 'Plaintiff Name' instead of 'plantiff.name'
                            let fieldName = parent ? `${parent} ${key}`.replaceAll('_', ' ') : key.replaceAll('_', ' ');
                            missingFields.push(fieldName);
                            document.getElementById(parent ? `${parent}_${key}` : key).style.border = '1px solid red';
                        } else {
                            document.getElementById(parent ? `${parent}_${key}` : key).style.border = '1px solid #ced4da';
                        }
                    }
                }
            }

            // Check all fields
            checkFields(formJson);

            // ✅ If any field is missing, throw an error
            if (missingFields.length > 0) {
                alert("Please fill the following required fields:\n\n" + missingFields.join('\n'));
                return false; // Prevent form submission
            }

            // ✅ All fields are filled, return true
            return true;
        }

        // ✅ Call the validation function before form submission
        if (!validateForm(formJson)) {
            console.error("Form validation failed.");
            throw new Error("Form validation failed."); // ✅ Completely stop execution
            return;
        } else {
            // console.log("Form data ready to be submitted:", formJson);
        }

        // if (emailOTP === null || mobileOTP === null) {
        //     alert("OTP not verified");
        //     throw new Error("Form validation failed."); // ✅ Completely stop execution
        //     return;
        // }

        // Send an AJAX POST request to fetchApplication.php with these fields
        $.ajax({
            url: 'fetchApplication.php',
            type: 'POST',
            data: formJson,
            dataType: 'json',
            success: function (data) {
                console.log("Response from server:", data);
                $('#submitButton').hide();

                let html = '<h2>Application Details</h2>';
                html += '<table class="table table-bordered">';

                const fieldsToShow = ['reference_id', 'title', 'subject', 'type', 'description', 'status', 'created_at'];

                fieldsToShow.forEach(field => {
                    if (data.hasOwnProperty(field)) {
                        const sanitizedValue = $('<div/>').text(data[field]).html();
                        html += '<tr><th>' + field.replace(/_/g, ' ').toUpperCase() + '</th><td>' + sanitizedValue + '</td></tr>';
                    }
                });

                html += '</table>';

                const applicationDetailsDiv = $('#applicationDetails');
                applicationDetailsDiv.empty(); // Clear previous content
                applicationDetailsDiv.append(html);

                if (data.status && data.status.toLowerCase().includes("pending")) {
                    const viewButton = $('<button class="btn btn-warning m-2" target="_blank">View Details/Edit</button>');
                    viewButton.on('click', function () {
                        window.open('../viewApplication/complaintView.php?ref_id=' + hashed_refID, '_blank');
                    });
                    applicationDetailsDiv.append(viewButton);
                }
                if (data.status && data.status.toLowerCase().includes("approved")) {
                    const viewButton = $('<button class="btn btn-warning m-2" target="_blank">View Details</button>');
                    viewButton.on('click', function () {
                        window.open('../viewApplication/complaintView.php?ref_id=' + hashed_refID, '_blank');
                    });
                    applicationDetailsDiv.append(viewButton);

                    const requestDateButton = $('<button class="btn btn-success m-2" target="_blank">Request Date</button>');
                    requestDateButton.on('click', function () {
                        window.open('requestDate.php?ref_id=' + hashed_refID, '_blank');
                    });
                    applicationDetailsDiv.append(requestDateButton);
                }
                if (data.status && data.status.toLowerCase().includes("resolved")) {
                    const viewButton = $('<button class="btn btn-warning m-2" target="_blank">View Details</button>');
                    viewButton.on('click', function () {
                        window.open('../viewApplication/complaintView.php?ref_id=' + hashed_refID, '_blank');
                    });
                    applicationDetailsDiv.append(viewButton);
                }

                const viewFilesButton = $('<button class="btn btn-info m-2" target="_blank">View Uploaded Files</button>');
                viewFilesButton.on('click', function () {
                    window.open('../uploadFile/fileView.php?ref_id=' + hashed_refID, '_blank');
                });
                applicationDetailsDiv.append(viewFilesButton);

                const uploadFilesButton = $('<button class="btn btn-info m-2" target="_blank">Upload Files</button>');
                uploadFilesButton.on('click', function () {
                    window.open('../uploadFile/fileUpload.php?ref_id=' + hashed_refID, '_blank');
                });
                applicationDetailsDiv.append(uploadFilesButton);
            },
            error: function (xhr, status, error) {
                console.error("Error fetching details:", error);
                $('#applicationDetails').html('<div class="alert alert-danger">Error fetching application details.</div>');
            }
        });

        $.ajax({
            url: 'statusHistorySql.php',
            type: 'POST',
            data: formJson,
            dataType: 'json',
            success: function (data2) {
                console.log("Response from server:", data2);
                $('#submitButton').hide();

                let html = `
                <div class="content" id="mainContent">
                    <h5 class="mb-3">Complaint Status History</h5>
                    <table class="display table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Created Date</th>
                                <th>Status</th>
                                <th>Comment</th>
                                <th>Point of Contact</th>
                            </tr>
                        </thead>
                        <tbody>`;

                if (Array.isArray(data2) && data2.length > 0) {
                    data2.forEach(row => {
                        html += `
                            <tr>
                                <td>${row.created_at}</td>
                                <td>${row.status}</td>
                                <td>${row.comment}</td>
                                <td>${row.editor_name} (${row.editor_mobile})</td>
                            </tr>`;
                    });
                } else {
                    html += `<tr><td colspan="3" class="text-center">No status history available</td></tr>`;
                }

                html += `</tbody></table></div>`;

                $('#applicationDetails2').html(html);
            },
            error: function (xhr, status, error) {
                console.error("Error fetching details:", error);
                $('#applicationDetails').html('<div class="alert alert-danger">Error fetching application details.</div>');
            }
        });
    }

    document.getElementById('applicationForm').addEventListener('submit', function (event) {
        event.preventDefault();
        submitApplication2();
    });
</script>