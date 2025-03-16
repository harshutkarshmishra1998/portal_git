<?php
function findInitPath($maxDepth = 6)
{
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
} else {
    echo "Error: init.php not found within the specified depth.";
}

require_once BASE_PATH . 'include/dataHasher.php';
require_once BASE_PATH . 'include/cipherSelection.php';

// Start secure session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cipher = selectRandomCipher();
$key = $_SESSION['user_ip'];
global $hasher;
$hasher = new DataHasher($key, $cipher);

function hashedReferenceID($id)
{
    global $hasher;
    $encryptedToken = $hasher->encrypt($id);
    return $encryptedToken;
}

function unhashedReferenceID($id)
{
    global $hasher;
    $decryptedToken = $hasher->decrypt($id);
    return $decryptedToken;
}
?>