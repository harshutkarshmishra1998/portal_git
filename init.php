<?php
// Security: Construct base URL correctly
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$base_url = $protocol.$_SERVER['HTTP_HOST']."/portal/"; //http://localhost/portal/
$uploadDir = '../../../../uploads/'; //For {admin,resolved}/{application, case, resolved}/file/
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/portal/');
?>