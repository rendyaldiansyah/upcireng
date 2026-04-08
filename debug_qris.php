<?php
// Temp debug file - DELETE AFTER USE

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$qrisImage = \App\Models\Setting::getSetting('qris_image', '');

echo "=== DEBUG QRIS ===\n";
echo "1. QRIS from DB: " . ($qrisImage ? $qrisImage : 'EMPTY/NULL') . "\n";

if ($qrisImage) {
    $exists = \Illuminate\Support\Facades\Storage::disk('public')->exists($qrisImage);
    echo "2. File exists check: " . ($exists ? 'YES ✓' : 'NO ✗') . "\n";
    echo "3. Asset URL: " . asset('storage/' . $qrisImage) . "\n";
    echo "4. Full path: storage/app/public/" . $qrisImage . "\n";
} else {
    echo "ERROR: QRIS not found in database!\n";
}
