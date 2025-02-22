<?php include 'include.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register Complaint</title>
    <!-- Bootstrap CSS for styling and tabs -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .required-asterisk {
            color: red;
        }

        .otp-container {
            display: none;
            margin-top: 10px;
        }

        .fade-out {
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Register Complaint <small class="required-asterisk">(* compulsory)</small></h1>
        <form id="complaintForm">
            <!-- Complaint Details -->
            <div class="mb-3">
                <label for="subject" class="form-label">Subject <span class="required-asterisk">*</span></label>
                <input type="text" class="form-control" id="subject" name="subject" required />
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="required-asterisk">*</span></label>
                <input type="text" class="form-control" id="title" name="title" required />
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description <span class="required-asterisk">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <!-- Two Upload File Columns after Description -->
            <div class="mb-3">
                <label for="upload_file_1" class="form-label">Upload File 1</label>
                <input type="file" class="form-control" id="upload_file_1" name="upload_file_1" accept="*/*" />
            </div>
            <div class="mb-3">
                <label for="upload_file_2" class="form-label">Upload File 2</label>
                <input type="file" class="form-control" id="upload_file_2" name="upload_file_2" accept="*/*" />
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type <span class="required-asterisk">*</span></label>
                <select class="form-select" id="type" name="type" required>
                    <option value="">Select Type</option>
                    <option value="Type1">Type 1</option>
                    <option value="Type2">Type 2</option>
                    <option value="Type3">Type 3</option>
                </select>
            </div>

            <!-- Contact Details with Separate Verify Buttons -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="mobile_number" class="form-label">Mobile Number <span class="required-asterisk">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="mobile_number" name="mobile_number" required />
                        <script>
                            const mobileInput = document.getElementById("mobile_number");
                            // Add +91 infront of number if missed
                            mobileInput.addEventListener("blur", function() {
                                let value = mobileInput.value.trim();
                                if (!value) return; // do nothing if the field is empty

                                if (value.startsWith("+91")) {
                                    // Already has the correct prefix; do nothing.
                                    return;
                                } else if (value.startsWith("0")) {
                                    // Replace the starting 0 with +91.
                                    mobileInput.value = value.replace(/^0/, "+91");
                                } else {
                                    // Prepend +91 if not already present.
                                    mobileInput.value = "+91" + value;
                                }
                            });
                        </script>
                        <button type="button" id="verifyMobileBtn" class="btn btn-outline-secondary">Verify Mobile</button>
                    </div>
                    <!-- Mobile OTP Container -->
                    <div id="mobileOtpContainer" class="otp-container">
                        <input type="text" id="mobileOtp" class="form-control" placeholder="Enter OTP" />
                        <button type="button" id="submitMobileOtp" class="btn btn-primary mt-2">Submit OTP</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email <span class="required-asterisk">*</span></label>
                    <div class="input-group">
                        <input type="email" class="form-control" id="email" name="email" required />
                        <button type="button" id="verifyEmailBtn" class="btn btn-outline-secondary">Verify Email</button>
                    </div>
                    <!-- Email OTP Container -->
                    <div id="emailOtpContainer" class="otp-container">
                        <input type="text" id="emailOtp" class="form-control" placeholder="Enter OTP" />
                        <button type="button" id="submitEmailOtp" class="btn btn-primary mt-2">Submit OTP</button>
                    </div>
                </div>
            </div>

            <!-- Tabbed interface for additional details -->
            <ul class="nav nav-tabs" id="detailsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="true">
                        Personal Details
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="family-tab" data-bs-toggle="tab" data-bs-target="#family" type="button" role="tab" aria-controls="family" aria-selected="false">
                        Family Details
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="permanent-tab" data-bs-toggle="tab" data-bs-target="#permanent" type="button" role="tab" aria-controls="permanent" aria-selected="false">
                        Permanent Address
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="temporary-tab" data-bs-toggle="tab" data-bs-target="#temporary" type="button" role="tab" aria-controls="temporary" aria-selected="false">
                        Temporary Address
                    </button>
                </li>
            </ul>
            <div class="tab-content border border-top-0 p-3" id="detailsTabContent">
                <!-- Personal Details Tab -->
                <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name <span class="required-asterisk">*</span></label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required />
                    </div>
                    <div class="mb-3">
                        <label for="age" class="form-label">Age <span class="required-asterisk">*</span></label>
                        <input type="number" class="form-control" id="age" name="age" required />
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender <span class="required-asterisk">*</span></label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="caste" class="form-label">Caste</label>
                        <input type="text" class="form-control" id="caste" name="caste" />
                    </div>
                </div>
                <!-- Family Details Tab -->
                <div class="tab-pane fade" id="family" role="tabpanel" aria-labelledby="family-tab">
                    <div class="mb-3">
                        <label for="father_name" class="form-label">Father's Name</label>
                        <input type="text" class="form-control" id="father_name" name="father_name" />
                    </div>
                    <div class="mb-3">
                        <label for="mother_name" class="form-label">Mother's Name</label>
                        <input type="text" class="form-control" id="mother_name" name="mother_name" />
                    </div>
                    <div class="mb-3">
                        <label for="spouse_name" class="form-label">Spouse's Name</label>
                        <input type="text" class="form-control" id="spouse_name" name="spouse_name" />
                    </div>
                    <div class="mb-3">
                        <label for="guardian_name" class="form-label">Guardian's Name</label>
                        <input type="text" class="form-control" id="guardian_name" name="guardian_name" />
                    </div>
                    <div class="mb-3">
                        <label for="num_of_dependents" class="form-label">Number of Dependents</label>
                        <input type="number" class="form-control" id="num_of_dependents" name="num_of_dependents" />
                    </div>
                    <div class="mb-3">
                        <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                        <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" />
                    </div>
                    <div class="mb-3">
                        <label for="emergency_contact_number" class="form-label">Emergency Contact Number</label>
                        <input type="text" class="form-control" id="emergency_contact_number" name="emergency_contact_number" />
                    </div>
                </div>
                <!-- Permanent Address Tab -->
                <div class="tab-pane fade" id="permanent" role="tabpanel" aria-labelledby="permanent-tab">
                    <div class="mb-3">
                        <label for="perm_state" class="form-label">State</label>
                        <input type="text" class="form-control" id="perm_state" name="perm_state" />
                    </div>
                    <div class="mb-3">
                        <label for="perm_district" class="form-label">District</label>
                        <input type="text" class="form-control" id="perm_district" name="perm_district" />
                    </div>
                    <div class="mb-3">
                        <label for="perm_city" class="form-label">City</label>
                        <input type="text" class="form-control" id="perm_city" name="perm_city" />
                    </div>
                    <div class="mb-3">
                        <label for="perm_ward_number" class="form-label">Ward Number</label>
                        <input type="text" class="form-control" id="perm_ward_number" name="perm_ward_number" />
                    </div>
                    <div class="mb-3">
                        <label for="perm_street" class="form-label">Street</label>
                        <input type="text" class="form-control" id="perm_street" name="perm_street" />
                    </div>
                    <div class="mb-3">
                        <label for="perm_postal_code" class="form-label">Postal Code</label>
                        <input type="text" class="form-control" id="perm_postal_code" name="perm_postal_code" />
                    </div>
                </div>
                <!-- Temporary Address Tab -->
                <div class="tab-pane fade" id="temporary" role="tabpanel" aria-labelledby="temporary-tab">
                    <div class="mb-3">
                        <label for="temp_state" class="form-label">State</label>
                        <input type="text" class="form-control" id="temp_state" name="temp_state" />
                    </div>
                    <div class="mb-3">
                        <label for="temp_district" class="form-label">District</label>
                        <input type="text" class="form-control" id="temp_district" name="temp_district" />
                    </div>
                    <div class="mb-3">
                        <label for="temp_city" class="form-label">City</label>
                        <input type="text" class="form-control" id="temp_city" name="temp_city" />
                    </div>
                    <div class="mb-3">
                        <label for="temp_ward_number" class="form-label">Ward Number</label>
                        <input type="text" class="form-control" id="temp_ward_number" name="temp_ward_number" />
                    </div>
                    <div class="mb-3">
                        <label for="temp_street" class="form-label">Street</label>
                        <input type="text" class="form-control" id="temp_street" name="temp_street" />
                    </div>
                    <div class="mb-3">
                        <label for="temp_postal_code" class="form-label">Postal Code</label>
                        <input type="text" class="form-control" id="temp_postal_code" name="temp_postal_code" />
                    </div>
                    <div class="mb-3">
                        <label for="temp_duration_of_stay" class="form-label">Duration of Stay</label>
                        <input type="text" class="form-control" id="temp_duration_of_stay" name="temp_duration_of_stay" />
                    </div>
                </div>
            </div>

            <!-- Form Submission -->
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Submit Complaint</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let mobileOtpCode, emailOtpCode;
        let mobileVerifiedStatus = 0;
        let emailVerifiedStatus = 0;

        // Function to generate a random 6-digit OTP as a string.
        function generateOtp() {
            return Math.floor(100000 + Math.random() * 900000).toString();
        }

        // Mobile Verification: Show OTP container and simulate sending OTP.
        document.getElementById("verifyMobileBtn").addEventListener("click", function() {
            const mobileNumber = document.getElementById("mobile_number").value.trim();
            if (mobileNumber === "") {
                alert("Please enter your mobile number before verifying.");
                return;
            }
            mobileOtpCode = generateOtp();
            // Call backend to send SMS (sendSMS.php should call sendSMS($recipient, "Your OTP is: $otp"))
            fetch('sendSMS.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        recipient: mobileNumber,
                        otp: mobileOtpCode
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Data received from sendSMS.php:", data);
                })
                .catch(error => {
                    console.error("Error sending SMS:", error);
                });
            // console.log("Mobile OTP:", mobileOtpCode); // For debugging; remove in production.
            document.getElementById("mobileOtpContainer").style.display = "block";
        });

        // Email Verification: Show OTP container and simulate sending OTP.
        document.getElementById("verifyEmailBtn").addEventListener("click", function() {
            const emailAddress = document.getElementById("email").value.trim();
            if (emailAddress === "" || !validateEmail(emailAddress)) {
                alert("Please enter a valid email before verifying.");
                return;
            }
            emailOtpCode = generateOtp();
            console.log("Email OTP:", emailOtpCode); // For debugging; remove in production.
            // Call backend to send Email (sendMail.php should call sendMail($toEmail, $toName, $subject, $message))
            fetch('sendMail.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        toEmail: emailAddress,
                        toName: "User", // Add a name if available
                        subject: "OTP Verification",
                        message: "Your OTP is: " + emailOtpCode
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Data received from sendMail.php:", data);
                })
                .catch(error => {
                    console.error("Error sending Email:", error);
                });
            // console.log("Email OTP:", emailOtpCode); // For debugging; remove in production.
            document.getElementById("emailOtpContainer").style.display = "block";
        });

        function validateEmail(email) {
            // Simple regex for email validation.
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }

        // Submit Mobile OTP Verification
        document.getElementById("submitMobileOtp").addEventListener("click", function() {
            const enteredOtp = document.getElementById("mobileOtp").value.trim();
            if (enteredOtp === "") {
                alert("OTP cannot be blank.");
                return;
            }
            if (enteredOtp === mobileOtpCode) {
                const container = document.getElementById("mobileOtpContainer");
                container.classList.add("fade-out");
                setTimeout(() => {
                    container.style.display = "none";
                    document.getElementById("verifyMobileBtn").textContent = "Verified";
                    document.getElementById("verifyMobileBtn").disabled = true;
                    document.getElementById("mobile_number").disabled = true;
                    // Set mobile verification status to 1.
                    mobileVerifiedStatus = 1;
                    // console.log("Mobile verification status:", mobileVerifiedStatus);
                }, 500);
            } else {
                alert("Incorrect OTP. Please try again.");
            }
        });

        // Submit Email OTP Verification
        document.getElementById("submitEmailOtp").addEventListener("click", function() {
            const enteredOtp = document.getElementById("emailOtp").value.trim();
            if (enteredOtp === "") {
                alert("OTP cannot be blank.");
                return;
            }
            if (enteredOtp === emailOtpCode) {
                const container = document.getElementById("emailOtpContainer");
                container.classList.add("fade-out");
                setTimeout(() => {
                    container.style.display = "none";
                    document.getElementById("verifyEmailBtn").textContent = "Verified";
                    document.getElementById("verifyEmailBtn").disabled = true;
                    document.getElementById("email").disabled = true;
                    // Set email verification status to 1.
                    emailVerifiedStatus = 1;
                    console.log("Email verification status:", emailVerifiedStatus);
                }, 500);
            } else {
                alert("Incorrect OTP. Please try again.");
            }
        });
    </script>

</body>

</html>