<?php
function findInitPath($maxDepth = 6) {
    for ($depth = 0; $depth <= $maxDepth; $depth++) {
        $path = __DIR__;
        for ($i = 0; $i < $depth; $i++) {
            $path .= '/..';
        }
        $path .= '/init.php';

        if (file_exists(realpath($path))) {
            return realpath($path);
        }
    }
    return false; // init.php not found within maxDepth
}

$initPath = findInitPath();

if ($initPath) {
    require_once $initPath;
    echo "init.php found and included successfully.";
} else {
    echo "Error: init.php not found within the specified depth.";
}
?>

<?php
// --- Start the session ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If any condition fails, redirect to login page
header("Location: " . $base_url . "user/public/homepage/index.php");
// exit;
?>