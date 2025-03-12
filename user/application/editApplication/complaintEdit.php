<?php include '../../modules/header.php'; ?>
<link rel="stylesheet" href="complaintRegistration.css">

<body>
    <?php include '../../modules/navbar.php'; ?>

    <!-- Form Container -->
    <div class="container">
        <h1 class="mt-4 mb-3">COMPLAINT REGISTRATION FORM</h1>
        <div id="alertContainer"></div>
        <form id="applicationForm" action="/submit" method="post" enctype="multipart/form-data" novalidate>
            <!-- General Fields -->
            <div class="form-group">
                <label for="reference_id">Reference ID</label>
                <input type="text" class="form-control" id="reference_id" name="reference_id"
                    placeholder="Enter Reference ID" readonly>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title">
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <textarea class="form-control" id="subject" name="subject" rows="2"></textarea>
            </div>
            <!-- <div class="form-group">
                <label for="type">Type</label>
                <input type="text" class="form-control" id="type" name="type" placeholder="Enter Type">
            </div> -->

            <div class="form-group">
                <label for="type">Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="Border dispute">Border dispute</option>
                    <option value="Building construction encroaching land">Building construction encroaching land
                    </option>
                    <option value="Domestic violence">Domestic violence</option>
                    <option value="Husband-wife relationship issue">Husband-wife relationship issue</option>
                    <option value="House rent dispute">House rent dispute</option>
                    <option value="Impact caused by animals or birds">Impact caused by animals or birds</option>
                    <option value="Looting">Looting</option>
                    <option value="Map dispute (land or building)">Map dispute (land or building)</option>
                    <option value="Not paid wages">Not paid wages</option>
                    <option value="Not providing care and support to senior citizens">Not providing care and support to
                        senior citizens</option>
                    <option value="Not providing education and guidance">Not providing education and guidance</option>
                    <option value="Not providing food and shelter">Not providing food and shelter</option>
                    <option value="Physical assault">Physical assault</option>
                    <option value="Placing bricks, tiles, or roofing against standards">Placing bricks, tiles, or
                        roofing against standards</option>
                    <option value="Planting trees affecting surroundings">Planting trees affecting surroundings</option>
                    <option value="Right to use public land/road">Right to use public land/road</option>
                    <option value="Stopping construction against standards">Stopping construction against standards
                    </option>
                    <option value="Verbal abuse or defamation">Verbal abuse or defamation</option>
                    <option value="Water drainage affecting property">Water drainage affecting property</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" class="form-control" id="status" name="status" readonly>
            </div>

            <!-- Side-by-side Plaintiff and Defendant Info -->
            <div class="row">
                <!-- Plaintiff Information -->
                <div class="col-md-6">
                    <h4>PLANTIFF INFORMATION</h4>
                    <div class="form-group">
                        <label for="plantiff_name">Plaintiff Name</label>
                        <input type="text" class="form-control" id="plantiff_name" name="plantiff_name">
                    </div>
                    <div class="form-group">
                        <label for="plantiff_address">Plaintiff Address</label>
                        <textarea class="form-control" id="plantiff_address" name="plantiff_address"
                            rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="plantiff_ward_number">Plaintiff Ward Number</label>
                        <input type="text" class="form-control" id="plantiff_ward_number" name="plantiff_ward_number">
                    </div>
                    <div class="form-group">
                        <label for="plantiff_mobile">Plaintiff Mobile</label>
                        <input type="text" pattern="\d{10}" class="form-control" id="plantiff_mobile"
                            name="plantiff_mobile" placeholder="10-digit mobile number" readonly>
                    </div>
                    <div class="form-group">
                        <label for="plantiff_email">Plaintiff Email</label>
                        <input type="email" class="form-control" id="plantiff_email" name="plantiff_email"
                            placeholder="Enter Email" readonly>
                    </div>
                    <div class="form-group">
                        <label for="plantiff_citizenship_id">Plaintiff Citizenship ID</label>
                        <input type="text" class="form-control" id="plantiff_citizenship_id" name="plantiff_citizenship_id">
                    </div>
                    <div class="form-group">
                        <label for="plantiff_father_name">Plaintiff Father Name</label>
                        <input type="text" class="form-control" id="plantiff_father_name" name="plantiff_father_name">
                    </div>
                    <div class="form-group">
                        <label for="plantiff_grandfather_name">Plaintiff Grandfather Name</label>
                        <input type="text" class="form-control" id="plantiff_grandfather_name"
                            name="plantiff_grandfather_name">
                    </div>
                </div>
                <!-- Defendant Information -->
                <div class="col-md-6">
                    <h4>DEFENDANT INFORMATION</h4>
                    <div class="form-group">
                        <label for="defendant_name">Defendant Name</label>
                        <input type="text" class="form-control" id="defendant_name" name="defendant_name">
                    </div>
                    <div class="form-group">
                        <label for="defendant_address">Defendant Address</label>
                        <textarea class="form-control" id="defendant_address" name="defendant_address"
                            rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="defendant_ward_number">Defendant Ward Number</label>
                        <input type="text" class="form-control" id="defendant_ward_number" name="defendant_ward_number">
                    </div>
                    <div class="form-group">
                        <label for="defendant_mobile">Defendant Mobile</label>
                        <input type="text" pattern="\d{10}" class="form-control" id="defendant_mobile"
                            name="defendant_mobile" placeholder="10-digit mobile number">
                    </div>
                    <div class="form-group">
                        <label for="defendant_email">Defendant Email</label>
                        <input type="email" class="form-control" id="defendant_email" name="defendant_email">
                    </div>
                    <div class="form-group">
                        <label for="defendant_citizenship_id">Defendant Citizenship ID</label>
                        <input type="text" class="form-control" id="defendant_citizenship_id" name="defendant_citizenship_id">
                    </div>
                    <div class="form-group">
                        <label for="defendant_father_name">Defendant Father Name</label>
                        <input type="text" class="form-control" id="defendant_father_name" name="defendant_father_name">
                    </div>
                    <div class="form-group">
                        <label for="defendant_grandfather_name">Defendant Grandfather Name</label>
                        <input type="text" class="form-control" id="defendant_grandfather_name"
                            name="defendant_grandfather_name">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary" id="#submitButton">Edit</button>
        </form>
    </div>

    <?php include '../../modules/footer.php'; ?>
    <?php include 'dataValidation.php'; ?>
    <?php include 'dataFetcher.php'; ?>
    <?php include 'dataUpdation.php'; ?>
</body>

</html>