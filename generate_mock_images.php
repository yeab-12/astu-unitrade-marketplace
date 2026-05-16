<?php
$uploadDir = __DIR__ . '/uploads/';

$images = [
    'default-laptop.jpg' => ['text' => 'Laptop', 'color' => [30, 58, 138]],
    'default-phone.jpg' => ['text' => 'Phone', 'color' => [245, 158, 11]],
    'default-stationery.jpg' => ['text' => 'Stationery', 'color' => [16, 185, 129]],
    'default-clothes.jpg' => ['text' => 'Clothes', 'color' => [236, 72, 153]],
    'default-shoes.jpg' => ['text' => 'Shoes', 'color' => [139, 92, 246]],
    'default-food.jpg' => ['text' => 'Food', 'color' => [239, 68, 68]],
    'default-dorm.jpg' => ['text' => 'Dorm', 'color' => [14, 165, 233]]
];

foreach ($images as $filename => $data) {
    $path = $uploadDir . $filename;
    
    // Create an 800x600 image
    $im = imagecreatetruecolor(800, 600);
    
    // Set background color
    $bg = imagecolorallocate($im, $data['color'][0], $data['color'][1], $data['color'][2]);
    imagefill($im, 0, 0, $bg);
    
    // Add text
    $textColor = imagecolorallocate($im, 255, 255, 255);
    $text = $data['text'];
    $font = 5; // Built-in font
    
    $textWidth = imagefontwidth($font) * strlen($text);
    $textHeight = imagefontheight($font);
    
    $x = (800 - $textWidth) / 2;
    $y = (600 - $textHeight) / 2;
    
    imagestring($im, $font, $x, $y, $text, $textColor);
    
    // Save image
    imagejpeg($im, $path);
    imagedestroy($im);
}
echo "Images generated successfully.\n";
?>
