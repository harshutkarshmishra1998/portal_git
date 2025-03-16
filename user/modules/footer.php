<!-- jQuery 3.6.0 (Required for AJAX + Form Handling) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5.3.3 (JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables (for Complaint List, Complaint Status, etc.) -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- jQuery Validation (Form Validation) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<footer class="jury-footer py-5">
    <div class="container-fluid px-4r">
        <div class="row">
            <!-- Left Side: Office and Contact Details -->
            <div class="col-md-6">
                <h5 class="jury-footer-title">न्यायिक समिति, धनपालथान गाउँपालिका</h5>
                <ul class="list-unstyled">
                    <li><strong>ठेगाना:</strong> वडा नं. ५, मुख्य क्षेत्र, धनपालथान, नेपाल</li>
                    <li><strong>फोन:</strong> +977-1234567890</li>
                    <li><strong>ईमेल:</strong> contact@municipality.gov.np</li>
                    <li><strong>कार्यालय समय:</strong> आइतबार देखि शुक्रबार (१० बजे देखि ५ बजे सम्म)</li>
                </ul>
                <div class="col-md-8">
                <p style="text-align: justify;">
                    यस कार्यालयले स्थानीय विवादहरूलाई छिटो, निष्पक्ष र पारदर्शी तरिकाले समाधान गर्ने काम गर्दछ। यहाँ
                    अनुभवी कानूनी विशेषज्ञहरू र समुदायका प्रतिनिधिहरू मिलेर न्याय सुनिश्चित गर्न, मध्यस्थता र कानूनी
                    सल्लाह प्रदान गर्न तथा समुदाय र औपचारिक न्याय प्रक्रियाबीचको दूरी घटाउन महत्वपूर्ण भूमिका निर्वाह
                    गर्छन्।
                </p>
                </div>
            </div>

            <!-- Right Side: Quick Navigation -->
            <div class="col-md-3">
                <h5 class="jury-footer-title">छिटो नेभिगेसन</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $base_url; ?>user/public/homepage/index.php"
                            class="jury-footer-link">गृहपृष्ठ</a></li>
                    <li><a href="<?php echo $base_url; ?>user/public/homepage/index.php#jury-about-samiti"
                            class="jury-footer-link">हाम्रो बारेमा</a></li>
                    <li><a href="<?php echo $base_url; ?>user/public/homepage/index.php#members-nyayik-samiti"
                            class="jury-footer-link">सदस्यहरू</a></li>
                    <li><a href="<?php echo $base_url; ?>user/public/homepage/index.php#dispute-categories"
                            class="jury-footer-link">विवादका प्रकारहरू</a></li>
                    <li><a href="<?php echo $base_url; ?>user/public/homepage/index.php#downloads"
                            class="jury-footer-link">डाउनलोड</a></li>
                    <li><a href="<?php echo $base_url; ?>user/public/homepage/index.php#faq"
                            class="jury-footer-link">बारम्बार सोधिने प्रश्नहरू</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5 class="jury-footer-title">छिटो लिंकहरू</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $base_url; ?>user/application/registerApplication/complaintRegistration.php"
                            class="jury-footer-link">गुनासो दर्ता गर्नुहोस्</a></li>
                    <li><a href="<?php echo $base_url; ?>user/application/trackApplication/complaintTracker.php"
                            class="jury-footer-link">गुनासो ट्र्याक गर्नुहोस्</a></li>
                    <li><a href="<?php echo $base_url; ?>user/public/rules/index.php" class="jury-footer-link">ऐन/नियम</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Bottom Bar -->
        <div class="jury-footer-bottom text-center mt-4">
            <p class="mb-0">&copy; 2025 न्यायिक समिति, धनपालथान गाउँपालिका। सबै अधिकार सुरक्षित।</p>
        </div>
    </div>
</footer>