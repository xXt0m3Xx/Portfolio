<?php
include("zaglavlje.php");
$spoji = spajanjeNaBazu();

if (session_id() == "") session_start();

$naziv = $_GET['naziv'] ?? '';

if (isset($_POST['lajk'])) {
    $sqlSelect = "SELECT datum_vrijeme_kreiranja, broj_svidanja FROM pjesma WHERE naziv = '$naziv'";
    $resultSelect = mysqli_query($spoji, $sqlSelect);
    if (!$resultSelect) {
        die('Query error: ' . mysqli_error($spoji));
    }
    $row = mysqli_fetch_assoc($resultSelect);
    $datum_vrijeme_kreiranja = $row['datum_vrijeme_kreiranja'];
    $broj_svidanja = $row['broj_svidanja'];
    
    $sqlUpdate = "UPDATE pjesma SET broj_svidanja = IFNULL(broj_svidanja, 0) + 1, datum_vrijeme_kreiranja = '$datum_vrijeme_kreiranja' WHERE naziv = '$naziv'";
    $resultUpdate = mysqli_query($spoji, $sqlUpdate);
    if (!$resultUpdate) {
        die('Query error: ' . mysqli_error($spoji));
    }
}

$sql = "SELECT datum_vrijeme_kreiranja, broj_svidanja, opis FROM pjesma WHERE naziv = '$naziv'";
$result = mysqli_query($spoji, $sql);

if (!$result) {
    die('Query error: ' . mysqli_error($spoji));
}

$row = mysqli_fetch_assoc($result);
$datum_vrijeme_kreiranja = date('d.m.Y H:i:s', strtotime($row['datum_vrijeme_kreiranja']));
$broj_svidanja = $row['broj_svidanja'];
$opis = $row['opis'];

echo "<h2>Detalji pjesme: $naziv</h2>";
echo "<p>Datum i vrijeme kreiranja: $datum_vrijeme_kreiranja</p>";
echo "<p>Broj sviđanja: $broj_svidanja</p>";
echo "<p>Opis: $opis</p>";

echo "<form method='POST' action='detalji_pjesme.php?naziv=" . urlencode($naziv) . "'>";
echo "<input type='hidden' name='naziv' value='$naziv'>";
echo "<button type='submit' name='lajk'>Sviđa mi se</button>";
echo "</form>";

zatvoriVezuNaBazu($spoji);
include("podnozje.php");
?>