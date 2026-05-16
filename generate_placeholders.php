<?php
/**
 * Generate SVG placeholder images for UniTrade seed data.
 * Then convert references in DB from .jpg to .svg
 * Run once: php generate_placeholders.php
 */

$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$placeholders = [
    'default-laptop'     => ['#1E3A8A', '#3B82F6', 'Laptop', '💻'],
    'default-phone'      => ['#7C3AED', '#A78BFA', 'Phone', '📱'],
    'default-stationery' => ['#059669', '#34D399', 'Stationery', '📚'],
    'default-clothes'    => ['#DC2626', '#F87171', 'Clothes', '👕'],
    'default-shoes'      => ['#DB2777', '#F472B6', 'Shoes', '👟'],
    'default-dorm'       => ['#2563EB', '#60A5FA', 'Dorm', '🏠'],
    'default-food'       => ['#D97706', '#FBBF24', 'Food', '🍔'],
];

foreach ($placeholders as $name => $config) {
    [$c1, $c2, $label, $emoji] = $config;
    
    // Create both .jpg name (SVG content) and .svg
    // We'll create SVG files but name them .jpg so existing DB refs work
    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300">
  <defs>
    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$c1}"/>
      <stop offset="100%" style="stop-color:{$c2}"/>
    </linearGradient>
  </defs>
  <rect width="400" height="300" fill="url(#grad)" rx="0"/>
  <text x="200" y="140" text-anchor="middle" font-size="64" fill="white" opacity="0.9">{$emoji}</text>
  <text x="200" y="200" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="28" font-weight="600" fill="white" opacity="0.95">{$label}</text>
  <text x="200" y="235" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="14" fill="white" opacity="0.6">UniTrade Placeholder</text>
</svg>
SVG;

    // Save as .svg
    $svgPath = $uploadDir . $name . '.svg';
    file_put_contents($svgPath, $svg);
    echo "CREATED: {$name}.svg\n";
}

echo "\nNow updating database references...\n";

// Update DB to use .svg instead of .jpg for default images
require_once __DIR__ . '/includes/db.php';

$updates = [
    'default-laptop.jpg'     => 'default-laptop.svg',
    'default-phone.jpg'      => 'default-phone.svg',
    'default-stationery.jpg' => 'default-stationery.svg',
    'default-clothes.jpg'    => 'default-clothes.svg',
    'default-shoes.jpg'      => 'default-shoes.svg',
    'default-dorm.jpg'       => 'default-dorm.svg',
    'default-food.jpg'       => 'default-food.svg',
];

foreach ($updates as $old => $new) {
    $stmt = $pdo->prepare("UPDATE items SET image = ? WHERE image = ?");
    $stmt->execute([$new, $old]);
    $count = $stmt->rowCount();
    echo "Updated $count items: $old → $new\n";
}

echo "\nDone! All placeholder images created and DB updated.\n";
?>
