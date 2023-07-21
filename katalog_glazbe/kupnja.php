<?php
include("zaglavlje.php");
$spoji = spajanjeNaBazu();

echo "<p>uspješno ste zatražili kupnju pjesme</p>";

zatvoriVezuNaBazu($spoji);
include("podnozje.php");
?>