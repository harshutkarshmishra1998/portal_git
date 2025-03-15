<?php include '../../modules/header.php'; ?>

<?php 
// Define field labels if not defined elsewhere
$field_labels = [
    "reference_id"             => "Reference ID",
    "full_name"                => "Name",
    "father_name"              => "Father Name",
    "grandfather_name"         => "Grandfather Name",
    "address"                  => "Address",
    "date_of_birth"            => "Date of Birth",
    "mobile_number"            => "Mobile",
    "email"                    => "Email",
    "educational_qualification" => "Educational Qualification",
    "ward"                   => "Ward",
    "created_at"             => "Created At",
    "approved"               => "Approved"
];
?>

<body>
    <?php include '../../modules/navbar.php'; ?>

    <!-- Form Container -->
    <div class="container">
        <h1 class="mt-4 mb-3">Mediator Registration Form</h1>
        <div id="alertContainer"></div>
        <form id="mediatorRegistrationForm" action="/submit" method="post" enctype="multipart/form-data" novalidate>
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <!-- Reference ID (readonly) -->
            <div class="form-group">
                <label for="reference_id"><?php echo $field_labels['reference_id']; ?></label>
                <input type="text" class="form-control" id="reference_id" name="reference_id" placeholder="Enter Reference ID" readonly>
            </div>
            
            <!-- Full Name -->
            <div class="form-group">
                <label for="full_name"><?php echo $field_labels['full_name']; ?></label>
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter Name" required>
            </div>
            
            <!-- Father Name -->
            <div class="form-group">
                <label for="father_name"><?php echo $field_labels['father_name']; ?></label>
                <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Enter Father Name" required>
            </div>
            
            <!-- Grandfather Name -->
            <div class="form-group">
                <label for="grandfather_name"><?php echo $field_labels['grandfather_name']; ?></label>
                <input type="text" class="form-control" id="grandfather_name" name="grandfather_name" placeholder="Enter Grandfather Name" required>
            </div>
            
            <!-- Address -->
            <div class="form-group">
                <label for="address"><?php echo $field_labels['address']; ?></label>
                <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter Address" required></textarea>
            </div>
            
            <!-- Date of Birth -->
            <div class="form-group">
                <label for="date_of_birth"><?php echo $field_labels['date_of_birth']; ?></label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
            </div>
            
            <!-- Mobile Number -->
            <div class="form-group">
                <label for="mobile_number"><?php echo $field_labels['mobile_number']; ?></label>
                <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Enter Mobile Number" pattern="\d{10}" required>
            </div>
            
            <!-- Email -->
            <div class="form-group">
                <label for="email"><?php echo $field_labels['email']; ?></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
            </div>
            
            <!-- Educational Qualification -->
            <div class="form-group">
                <label for="educational_qualification"><?php echo $field_labels['educational_qualification']; ?></label>
                <input type="text" class="form-control" id="educational_qualification" name="educational_qualification" placeholder="Enter Educational Qualification" required>
            </div>
            
            <!-- Ward -->
            <div class="form-group">
                <label for="ward"><?php echo $field_labels['ward']; ?></label>
                <input type="text" class="form-control" id="ward" name="ward" placeholder="Enter Ward" required>
            </div>
            
            <!-- Created At & Approved fields are system-generated and thus not editable by the user -->
            <button type="submit" class="btn btn-primary" id="submitButton">Submit</button>
        </form>
    </div>

    <?php include '../../modules/footer.php'; ?>
    <?php include 'dataValidation.php'; ?>
    <?php include 'dataFetcher.php'; ?>
    <?php include 'dataUpdation.php'; ?>
</body>
</html>
