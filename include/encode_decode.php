<?php
function encodeNepaliText($text) {
    return base64_encode($text); // Encoding in Base64
}

function decodeNepaliText($encodedText) {
    return base64_decode($encodedText); // Decoding back to original
}

// Example Nepali text
// $nepaliText = 1753910400; //31 July
// $nepaliText = 1742515200; //21 March 

// $nepaliText = "4KSn4KSo4KSq4KS+4KSy4KSl4KS+4KSoIOCkl+CkvuCkieCkgeCkquCkvuCksuCkv+CkleCkvg==";
// $decoded = decodeNepaliText($nepaliText);

// $encoded = encodeNepaliText($nepaliText);
// $decoded = decodeNepaliText($encoded);

echo "Original Text: " . $nepaliText . "\n";
// echo "Encoded Text: " . $encoded . "\n";
echo "Decoded Text: " . $decoded . "\n";
?>
