<?php
// Security Headers
header("X-Frame-Options: DENY"); // Prevents clickjacking
header("X-XSS-Protection: 1; mode=block"); // Enables XSS protection in older browsers
header("X-Content-Type-Options: nosniff"); // Prevents MIME-type sniffing
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload"); // Enforces HTTPS for a year
header("Referrer-Policy: strict-origin-when-cross-origin"); // Limits referrer data exposure
header("Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()"); // Blocks unnecessary browser permissions
?>

<?php
// 1. Start the session if it's not already running
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function generateCSRFToken()
{
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 2. Define constant for one day in seconds
define('ONE_DAY_IN_SECONDS', 86400);

// 3. Construct the base URL correctly
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
    // echo "init.php found and included successfully.";
} else {
    echo "Error: init.php not found within the specified depth.";
    exit;
}

// 4. If any one of the required session variables is not set, logout
if (
    !isset($_SESSION['name']) ||
    !isset($_SESSION['email']) ||
    !isset($_SESSION['mobile']) ||
    !isset($_SESSION['login_timestamp']) ||
    !isset($_SESSION['ip_address'])
) {
    session_destroy();
    header("Location: " . $base_url . "admin/loginLogout/login/login.php");
    exit;
}

// 5. Verify that the session IP matches the user's current IP 
// and that the login timestamp is not older than one day; if not, logout.
$loginTime = $_SESSION['login_timestamp'];
$currentTime = time();
$userIP = $_SERVER['REMOTE_ADDR'];

if (($currentTime - $loginTime) >= ONE_DAY_IN_SECONDS || $_SESSION['ip_address'] !== $userIP) {
    session_destroy();
    header("Location: " . $base_url . "admin/loginLogout/login/login.php");
    exit;
}

// 6. Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Dashboard with Table Filters</title>
    <link rel="shortcut icon" href="#">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- For File Uploads Only -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" />
    <!-- DataTables Buttons CSS -->
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet" />
    <!-- (Optional) Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Frontend CSS File -->
    <?php require_once "frontendStyles.css.php"; ?>

    <!-- Set the Google Translate cookie to force translation from English to Nepali -->
    <script type="text/javascript">
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }
        setCookie("googtrans", "/en/ne", 1);
    </script>

    <!-- Initialize Google Translate Element -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                autoDisplay: false  // Don't display the widget UI
            }, 'google_translate_element');
        }
    </script>

    <!-- Load the Google Translate library -->
    <script type="text/javascript"
        src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</head>

<!-- Loader overlay -->
<div id="loader">
    <div class="loader-spinner"></div>
    <div class="loader-text">Loading...</div>
</div>

<!-- Main content container, initially hidden -->
<div id="content" style="display: none;">
    <!-- Hidden container for the Google Translate widget -->
    <div id="google_translate_element" style="display:none;"></div>
</div>

<!-- Script to remove loader after LCP and translation of loader-text -->
<script>
    // Function to hide loader and show content after an extra 500ms delay
    function hideLoaderAfterDelay() {
        setTimeout(() => {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('content').style.display = 'block';
        }, 100); // extra 0.5 second delay
    }

    if ('PerformanceObserver' in window) {
        const observer = new PerformanceObserver((entryList) => {
            const entries = entryList.getEntries();
            if (entries.length > 0) {
                console.log('Largest Contentful Paint:', entries[entries.length - 1].renderTime || entries[entries.length - 1].loadTime);
                observer.disconnect();

                // After LCP, wait for the loader text to be translated.
                const loaderTextEl = document.querySelector('.loader-text');
                if (loaderTextEl) {
                    const textObserver = new MutationObserver((mutations, textObserver) => {
                        // If the loader text is no longer "Loading...", assume it's translated.
                        if (loaderTextEl.textContent.trim() !== "Loading...") {
                            textObserver.disconnect();
                            hideLoaderAfterDelay();
                        }
                    });
                    textObserver.observe(loaderTextEl, { childList: true, subtree: true });

                    // Fallback: if no translation occurs within 3 seconds, hide loader anyway.
                    setTimeout(() => {
                        if (loaderTextEl.textContent.trim() === "Loading...") {
                            textObserver.disconnect();
                            hideLoaderAfterDelay();
                        }
                    }, 3000);
                } else {
                    hideLoaderAfterDelay();
                }
            }
        });
        observer.observe({ type: 'largest-contentful-paint', buffered: true });
    } else {
        // Fallback if PerformanceObserver isn't supported: total 6-second delay
        setTimeout(() => {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('content').style.display = 'block';
        }, 6000);
    }
</script>

<script>
    // Run as early as possible, ideally in the head
    (function () {
        // Function to force an element's display to none with !important
        function forceHide(el) {
            if (el && el.style) {
                el.style.setProperty('display', 'none', 'important');
            }
        }

        // Callback for mutations
        function mutationCallback(mutations) {
            mutations.forEach(mutation => {
                // For new nodes added
                if (mutation.type === "childList") {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            // Check if the node matches our selectors
                            if (node.matches && node.matches('#goog-gt-original-text, #goog-gt-tt')) {
                                forceHide(node);
                            }
                            // Also check its descendants
                            node.querySelectorAll && node.querySelectorAll('#goog-gt-original-text, #goog-gt-tt').forEach(forceHide);
                        }
                    });
                }
                // For attribute changes on existing nodes
                if (mutation.type === "attributes" && mutation.attributeName === "style") {
                    const target = mutation.target;
                    if (target.matches && target.matches('#goog-gt-original-text, #goog-gt-tt')) {
                        forceHide(target);
                    }
                }
            });
        }

        // Create the observer on document.body (or document.documentElement if needed)
        const observer = new MutationObserver(mutationCallback);
        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['style']
        });

        // Also, check if the elements already exist on page load
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('#goog-gt-original-text, #goog-gt-tt').forEach(forceHide);
        });
    })();
</script>

<style>
    /* Hide any element with the skiptranslate class */
    .skiptranslate {
        display: none;
    }

    /* Loader styling: full-screen overlay */
    #loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #fff;
        /* Loader background color */
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    /* Spinner styling */
    .loader-spinner {
        border: 8px solid #f3f3f3;
        /* Light grey border */
        border-top: 8px solid #3498db;
        /* Blue color for the top */
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite;
    }

    /* Loader text styling */
    .loader-text {
        margin-top: 20px;
        font-size: 18px;
        color: #555;
        font-family: Arial, sans-serif;
    }

    /* Animation keyframes */
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>