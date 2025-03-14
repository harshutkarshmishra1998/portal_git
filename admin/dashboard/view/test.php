<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Auto Translate with Loader</title>
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

<body>
    <!-- Loader overlay -->
    <div id="loader">
        <div class="loader-spinner"></div>
        <div class="loader-text">Loading...</div>
    </div>

    <!-- Main content container, initially hidden -->
    <div id="content" style="display: none;">
        <!-- Hidden container for the Google Translate widget -->
        <div id="google_translate_element" style="display:none;"></div>
        <h1>Welcome to My Website</h1>
        <p>This content should automatically be translated into Nepali on page load.</p>
    </div>

    <!-- Script to remove loader after LCP and translation of loader-text -->
    <script>
        // Function to hide loader and show content after an extra 500ms delay
        function hideLoaderAfterDelay() {
            setTimeout(() => {
                document.getElementById('loader').style.display = 'none';
                document.getElementById('content').style.display = 'block';
            }, 500); // extra 0.5 second delay
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
</body>

</html>