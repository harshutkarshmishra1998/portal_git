<?php require_once '../../modules/header.php'; ?>
<link rel="stylesheet" href="index.css">
<?php require_once '../../../include/db.php'; ?>

<body>
    <?php require_once '../../modules/navbar.php'; ?>
    <?php require_once '../../modules/hero.php'; ?>

    <section class="about-nyayik-samiti position-relative py-5" id="members-nyayik-samiti">
        <div class="container-fluid px-4r">
            <div class="heading-container mx-auto mb-5 text-center">
                <h2 class="section-title m-0">मध्यस्थकर्ताहरू सूची</h2>
            </div>
            <div class="row">
                <?php
                try {
                    $stmt = $pdo->query("SELECT full_name, mobile_number, passport_size_photo FROM mediators WHERE approved = 1");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $imagePath = '/uploads_mediator/' . $row['passport_size_photo'];
                        $name = htmlspecialchars($row['full_name']);
                        $mobile = htmlspecialchars($row['mobile_number']);
                        echo "<div class='col-md-3 mb-4'>
                                <div class='card nyayik-card'>
                                    <img src='$imagePath' class='card-img-top' alt='$name'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>$name</h5>
                                        <p class='card-text'><small class='text-muted'>मोबाइल: $mobile</small></p>
                                    </div>
                                </div>
                            </div>";
                    }
                } catch (PDOException $e) {
                    echo "<p class='text-danger text-center'>Failed to load mediators. Please try again later.</p>";
                }
                ?>
            </div>
        </div>
    </section>
</body>

<?php require_once '../../modules/footer.php'; ?>

</html>