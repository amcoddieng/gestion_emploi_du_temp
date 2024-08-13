<?php
header('Content-Type: image/png');

// Vos données sont stockées dans la variable $r
$r = [
    ['classe_nom' => 'a', 'module_nom' => 'FFF', 'id_module' => 1, 'volume_horaire_faite' => 12],
    ['classe_nom' => 'b', 'module_nom' => 'FFF', 'id_module' => 2, 'volume_horaire_faite' => 134]
];

// Création de l'image
$width = 600;
$height = 400;
$image = imagecreate($width, $height);

// Couleurs
$background = imagecolorallocate($image, 255, 255, 255);
$barColor1 = imagecolorallocate($image, 75, 192, 192);
$barColor2 = imagecolorallocate($image, 153, 102, 255);
$borderColor1 = imagecolorallocate($image, 0, 0, 0);
$borderColor2 = imagecolorallocate($image, 255, 0, 0);
$textColor = imagecolorallocate($image, 0, 0, 0);

$barHeight = 20;
$padding = 40;
$spacing = 40;
$maxValue = max(array_column($r, 'volume_horaire_faite'));
$scale = ($width - 2 * $padding) / $maxValue;

// Génération des barres pour chaque entrée
foreach ($r as $index => $entry) {
    // Exemple de données pour le volume horaire prévu
    $volume_horaire_prevu = 100; // Remplacez par $mod->getVolumeHoraireById() si nécessaire

    // Volume horaire fait
    $y1 = $padding + ($index * $spacing);
    $x1 = $padding;
    $x2 = $padding + ($entry['volume_horaire_faite'] * $scale);
    $y2 = $y1 + $barHeight;
    imagefilledrectangle($image, $x1, $y1, $x2, $y2, $barColor1);
    imagerectangle($image, $x1, $y1, $x2, $y2, $borderColor1);
    imagestring($image, 3, 10, $y1 + 5, $entry['classe_nom'] . ' / ' . $entry['module_nom'], $textColor);
    
    // Volume horaire prévu
    $y1 = $padding + ($index * $spacing) + $barHeight + 5;
    $x1 = $padding;
    $x2 = $padding + ($volume_horaire_prevu * $scale);
    $y2 = $y1 + $barHeight;
    imagefilledrectangle($image, $x1, $y1, $x2, $y2, $barColor2);
    imagerectangle($image, $x1, $y1, $x2, $y2, $borderColor2);
}

imagepng($image);
imagedestroy($image);
?>
