<?php include '../../modules/header.php'; ?>
<link rel="stylesheet" href="complaintRegistration.css">

<body>
    <?php include '../../modules/navbar.php'; ?>

    <!-- Form Container -->
    <div class="container">
        <h1 class="mt-4 mb-3">गुनासो दर्ता फारम</h1>
        <div id="alertContainer"></div>
        <form id="applicationForm" action="/submit" method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <!-- General Fields -->
            <div class="form-group">
                <label for="reference_id">सन्दर्भ आईडी</label>
                <input type="text" class="form-control" id="reference_id" name="reference_id"
                    placeholder="सन्दर्भ आईडी प्रविष्ट गर्नुहोस्" disabled>
            </div>
            <div class="form-group">
                <label for="title">शीर्षक</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="शीर्षक प्रविष्ट गर्नुहोस्"
                    required>
            </div>
            <div class="form-group">
                <label for="subject">विषय</label>
                <textarea class="form-control" id="subject" name="subject" rows="2" required></textarea>
            </div>
            <div class="form-group">
                <label for="type">प्रकार</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="सीमा विवाद">सीमा विवाद</option>
                    <option value="भवन निर्माणले भूमिमा अतिक्रमण">भवन निर्माणले भूमिमा अतिक्रमण</option>
                    <option value="घरेलु हिंसा">घरेलु हिंसा</option>
                    <option value="पति-पत्नी सम्बन्ध समस्या">पति-पत्नी सम्बन्ध समस्या</option>
                    <option value="घर भाडा विवाद">घर भाडा विवाद</option>
                    <option value="जनावर वा चराहरूले पारेको असर">जनावर वा चराहरूले पारेको असर</option>
                    <option value="लूटपाट">लूटपाट</option>
                    <option value="नक्सा विवाद (भूमि वा भवन)">नक्सा विवाद (भूमि वा भवन)</option>
                    <option value="मजुरी नतिरेको">मजुरी नतिरेको</option>
                    <option value="वरिष्ठ नागरिकहरूलाई हेरचाह र सहयोग नदिने">वरिष्ठ नागरिकहरूलाई हेरचाह र सहयोग नदिने
                    </option>
                    <option value="शिक्षा र मार्गदर्शन नदिने">शिक्षा र मार्गदर्शन नदिने</option>
                    <option value="खाना र आश्रय नदिने">खाना र आश्रय नदिने</option>
                    <option value="शारीरिक आक्रमण">शारीरिक आक्रमण</option>
                    <option value="इट्टा, टाइल वा छानो निर्माण मापदण्ड अनुसार नराख्ने">इट्टा, टाइल वा छानो निर्माण
                        मापदण्ड अनुसार नराख्ने</option>
                    <option value="आसपासको वातावरणमा असर पुर्याउने रूख रोपण">आसपासको वातावरणमा असर पुर्याउने रूख रोपण
                    </option>
                    <option value="सार्वजनिक भू-भूमि/मार्ग प्रयोगको अधिकार">सार्वजनिक भू-भूमि/मार्ग प्रयोगको अधिकार
                    </option>
                    <option value="निर्माणलाई मापदण्ड विरुद्ध रोक्नु">निर्माणलाई मापदण्ड विरुद्ध रोक्नु</option>
                    <option value="मौखिक दुर्व्यवहार वा मानहानि">मौखिक दुर्व्यवहार वा मानहानि</option>
                    <option value="सम्पत्तिमा असर गर्ने पानी निकास">सम्पत्तिमा असर गर्ने पानी निकास</option>
                    <option value="अन्य">अन्य</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">विवरण</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>

            <!-- Side-by-side Plaintiff and Defendant Info -->
            <div class="row">
                <!-- Plaintiff Information -->
                <div class="col-md-6">
                    <h4>वादीको जानकारी</h4>
                    <div class="form-group">
                        <label for="plantiff_name">वादीको नाम</label>
                        <input type="text" class="form-control" id="plantiff_name" name="plantiff_name" required>
                    </div>
                    <div class="form-group">
                        <label for="plantiff_address">वादीको ठेगाना</label>
                        <textarea class="form-control" id="plantiff_address" name="plantiff_address" rows="2"
                            required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="plantiff_ward_number">वादीको वडा नं. (संख्या प्रविष्ट गर्नुहोस्)</label>
                        <input type="number" min="1" class="form-control" id="plantiff_ward_number"
                            name="plantiff_ward_number" required>
                    </div>
                    <div class="form-group">
                        <label for="plantiff_mobile">वादीको मोबाइल</label>
                        <div class="input-group">
                            <input type="number" pattern="\d{10}" class="form-control" id="plantiff_mobile"
                                name="plantiff_mobile" placeholder="मोबाइल नम्बर प्रविष्ट गर्नुहोस् (+977 बिना)"
                                required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" id="verifyMobileBtn">ओटिपी
                                    प्रमाणित गर्नुहोस्</button>
                            </div>
                        </div>
                        <div class="invalid-feedback" id="plantiff_mobile_error"></div>
                        <!-- Mobile OTP Section -->
                        <div class="mt-2" id="mobile-otp-section" style="display: none;">
                            <input type="text" class="form-control mb-2" id="mobile_otp_input"
                                placeholder="मोबाइल ओटिपी प्रविष्ट गर्नुहोस्">
                            <div class="d-flex">
                                <button type="button" class="btn btn-success btn-sm mr-2" id="submitMobileOTP">ओटिपी पेश
                                    गर्नुहोस्</button>
                                <button type="button" class="btn btn-secondary btn-sm" id="cancelMobileOTP">रद्द
                                    गर्नुहोस्</button>
                            </div>
                            <small id="mobileOTPStatus" aria-live="polite" class="form-text text-success"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="plantiff_email">वादीको इमेल</label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="plantiff_email" name="plantiff_email"
                                placeholder="इमेल प्रविष्ट गर्नुहोस्" required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" id="verifyEmailBtn">ओटिपी
                                    प्रमाणित गर्नुहोस्</button>
                            </div>
                        </div>
                        <div class="invalid-feedback" id="plantiff_email_error"></div>
                        <!-- Email OTP Section -->
                        <div class="mt-2" id="email-otp-section" style="display: none;">
                            <input type="text" class="form-control mb-2" id="email_otp_input"
                                placeholder="इमेल ओटिपी प्रविष्ट गर्नुहोस्">
                            <div class="d-flex">
                                <button type="button" class="btn btn-success btn-sm mr-2" id="submitEmailOTP">ओटिपी पेश
                                    गर्नुहोस्</button>
                                <button type="button" class="btn btn-secondary btn-sm" id="cancelEmailOTP">रद्द
                                    गर्नुहोस्</button>
                            </div>
                            <small id="emailOTPStatus" aria-live="polite" class="form-text text-success"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="plantiff_citizenship_id">वादीको नागरिकता प्रमाणपत्र नम्बर</label>
                        <input type="text" class="form-control" id="plantiff_citizenship_id"
                            name="plantiff_citizenship_id" required>
                    </div>
                    <div class="form-group">
                        <label for="plantiff_father_name">वादीको बाबुको नाम</label>
                        <input type="text" class="form-control" id="plantiff_father_name" name="plantiff_father_name">
                    </div>
                    <div class="form-group">
                        <label for="plantiff_grandfather_name">वादीको हजुरबुबाको नाम</label>
                        <input type="text" class="form-control" id="plantiff_grandfather_name"
                            name="plantiff_grandfather_name">
                    </div>
                    <!-- Plaintiff Citizenship Proof Upload -->
                    <div class="form-group">
                        <label for="plantiff_citizenship">वादीको नागरिकता प्रमाण</label>
                        <input type="file" class="form-control-file" id="plantiff_citizenship"
                            name="plantiff_citizenship" required>
                        <div id="plantiff_citizenship_error" class="invalid-feedback d-block"></div>
                    </div>
                </div>
                <!-- Defendant Information -->
                <div class="col-md-6">
                    <h4>प्रतिवादीको जानकारी</h4>
                    <div class="form-group">
                        <label for="defendant_name">प्रतिवादीको नाम</label>
                        <input type="text" class="form-control" id="defendant_name" name="defendant_name" required>
                    </div>
                    <div class="form-group">
                        <label for="defendant_address">प्रतिवादीको ठेगाना</label>
                        <textarea class="form-control" id="defendant_address" name="defendant_address" rows="2"
                            required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="defendant_ward_number">प्रतिवादीको वडा नं. (संख्या प्रविष्ट गर्नुहोस्)</label>
                        <input type="number" min="1" class="form-control" id="defendant_ward_number"
                            name="defendant_ward_number" required>
                    </div>
                    <div class="form-group">
                        <label for="defendant_mobile">प्रतिवादीको मोबाइल</label>
                        <input type="number" pattern="\d{10}" class="form-control" id="defendant_mobile"
                            name="defendant_mobile" placeholder="मोबाइल नम्बर प्रविष्ट गर्नुहोस् (+977 बिना)" required>
                    </div>
                    <div class="form-group">
                        <label for="defendant_email">प्रतिवादीको इमेल</label>
                        <input type="email" class="form-control" id="defendant_email" name="defendant_email" required>
                    </div>
                    <div class="form-group">
                        <label for="defendant_citizenship_id">प्रतिवादीको नागरिकता प्रमाणपत्र नम्बर</label>
                        <input type="text" class="form-control" id="defendant_citizenship_id"
                            name="defendant_citizenship_id" required>
                    </div>
                    <div class="form-group">
                        <label for="defendant_father_name">प्रतिवादीको बाबुको नाम</label>
                        <input type="text" class="form-control" id="defendant_father_name" name="defendant_father_name"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="defendant_grandfather_name">प्रतिवादीको हजुरबुबाको नाम</label>
                        <input type="text" class="form-control" id="defendant_grandfather_name"
                            name="defendant_grandfather_name" required>
                    </div>
                    <!-- Defendant Citizenship Proof Upload -->
                    <div class="form-group">
                        <label for="defendant_citizenship">प्रतिवादीको नागरिकता प्रमाण</label>
                        <input type="file" class="form-control-file" id="defendant_citizenship"
                            name="defendant_citizenship" required>
                        <div id="defendant_citizenship_error" class="invalid-feedback d-block"></div>
                    </div>
                </div>
            </div>

            <!-- General File Upload (Multiple Files) -->
            <div class="form-group">
                <label for="file_upload">सामान्य फाइल अपलोड (ऐच्छिक)</label>
                <input type="file" class="form-control-file" id="file_upload" name="file_upload" multiple>
                <div class="invalid-feedback d-block" id="file_upload_error"></div>
            </div>
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary" id="#submitButton">पेश गर्नुहोस्</button>
        </form>
    </div>
    <br>

    <?php include '../../modules/footer.php'; ?>
    <?php include 'dataValidation.php'; ?>
    <?php include 'dataInsertion.php'; ?>
</body>

</html>