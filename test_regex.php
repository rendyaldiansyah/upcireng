<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "=== REGEX PATTERN TEST ===\n";

// This is what's in OrderController.php line 129
$phonePattern = '/^[0-9+\-\s()]{9,}$/';

echo "Pattern string: " . $phonePattern . "\n";
echo "Pattern bytes: " . bin2hex($phonePattern) . "\n";

try {
    echo "\nTesting preg_match with '081234567':\n";
    $result = preg_match($phonePattern, '081234567');
    if ($result === false) {
        $error = preg_last_error_msg();
        echo "ERROR: " . $error . "\n";
    } else if ($result === 1) {
        echo "✓ MATCH\n";
    } else {
        echo "✗ NO MATCH\n";
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

echo "\n=== Understanding the issue ===\n";

// The problem with backslashes in PHP strings
echo "In single quotes:\n";
echo "'\\\s' = " . '\s' . " (literal backslash + s)\n";
echo "'\\\-' = " . '\-' . " (literal backslash + dash)\n";

echo "\nSo the full pattern becomes:\n";
echo "From PHP: " . $phonePattern . "\n";
echo "Hex: " . bin2hex($phonePattern) . "\n";

// Try with double quotes 
$phonePattern2 = "/^[0-9+\\-\\s()]{9,}$/";
echo "\n=== TRYING WITH DOUBLE QUOTES AND PROPER ESCAPING ===\n";
echo "Pattern2: " . $phonePattern2 . "\n";
$result2 = preg_match($phonePattern2, '081234567');
if ($result2 === false) {
    echo "ERROR: " . preg_last_error_msg() . "\n";
} else if ($result2 === 1) {
    echo "✓ MATCH\n";
} else {
    echo "✗ NO MATCH\n";
}

// Try simpler pattern
$phonePattern3 = "/^[0-9+\s().\-\+]{9,}$/";
echo "\n=== SIMPLER PATTERN ===\n";
echo "Pattern3: " . $phonePattern3 . "\n";
$result3 = preg_match($phonePattern3, '081234567');
if ($result3 === false) {
    echo "ERROR: " . preg_last_error_msg() . "\n";
} else if ($result3 === 1) {
    echo "✓ MATCH\n";
} else {
    echo "✗ NO MATCH\n";
}
