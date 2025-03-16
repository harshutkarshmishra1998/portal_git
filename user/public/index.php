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
    return false;
}

$initPath = findInitPath();

if ($initPath) {
    require_once $initPath;
} else {
    echo "Error: init.php not found within the specified depth.";
    exit;
}

header("Location: " . $base_url . "user/public/homepage/index.php");
exit;
?>