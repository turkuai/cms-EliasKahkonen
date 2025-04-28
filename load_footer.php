<?php
// Lue JSON-tiedosto
$footerData = file_get_contents("footer.json");

// Aseta Content-Type headeriksi JSON
header('Content-Type: application/json');

// Lähetä JSON-data selaimelle
echo $footerData;
?>
