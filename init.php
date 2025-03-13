<!-- Path Setup -->
<?php
// Security: Construct base URL correctly
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$base_url = $protocol.$_SERVER['HTTP_HOST']."/portal/"; //http://localhost/portal/
?>

<?php
// $uploadPath = __DIR__ . '/../../uploads/'; // C:\xampp\htdocs\portal\include/../../uploads/
// $basePath = __DIR__. '/../../';  //C:\xampp\htdocs\portal\include

// echo "<a href=$uploadPath>Click Here</a>"

// List files in the uploads directory
// $files = scandir($uploadPath);

// foreach ($files as $file) {
//     if ($file !== '.' && $file !== '..') {
//         echo "<a href='/uploads/$file' download>$file</a><br>";
//     }
// }
?>