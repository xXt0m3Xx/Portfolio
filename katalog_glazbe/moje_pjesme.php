<?php
include("zaglavlje.php");
$spoji = spajanjeNaBazu();

if (session_id() == "") session_start();

$trenutna = basename($_SERVER["PHP_SELF"]);
$putanja = $_SERVER['REQUEST_URI'];

$aktivni_korisnik_id = $_SESSION["aktivni_korisnik_id"] ?? 0;
$stranica = $_GET['stranica'] ?? 1;
$offset = ($stranica - 1) * $vel_str;

$sql = "SELECT p.korisnik_id, k.korime, p.naziv, p.poveznica, p.opis, p.broj_svidanja, p.datum_vrijeme_kupnje, p.medijska_kuca_id, p.datum_vrijeme_kreiranja
    FROM pjesma p
    INNER JOIN korisnik k ON p.korisnik_id = k.korisnik_id
    WHERE p.korisnik_id = $aktivni_korisnik_id
    ORDER BY p.broj_svidanja
    LIMIT $offset, $vel_str";

$result = mysqli_query($spoji, $sql);

if (!$result) {
    die('Query error: ' . mysqli_error($spoji));
}

echo "<table>";
echo "<caption><h3>Moje pjesme</h3></caption>";
echo "<thead><tr>
    <th>Kreirao</th>
    <th>Naziv pjesme</th>
    <th>Poveznica</th>
    <th>Opis pjesme</th>
    <th>Broj sviđanja</th>
    <th>Datum i vrijeme kupnje</th>
    <th>Medijska kuća ID</th>
    <th>Datum i vrijeme kreiranja</th>
</tr></thead>";
echo "<tbody>";

while ($row = mysqli_fetch_assoc($result)) {
    $korisnikKorime = $row['korime'];
    $naziv = $row['naziv'];
    $poveznica = $row['poveznica'];
    $opis = $row['opis'];
    $broj_svidanja = $row['broj_svidanja'];
    $datum_vrijeme_kupnje = isset($row['datum_vrijeme_kupnje']) ? $row['datum_vrijeme_kupnje'] : '';
    $medijska_kuca_id = isset($row['medijska_kuca_id']) ? $row['medijska_kuca_id'] : '';
    $datum_vrijeme_kreiranja = date('d.m.Y H:i:s', strtotime($row['datum_vrijeme_kreiranja']));

    echo "<tr>";
    echo "<td>$korisnikKorime</td>";
    echo "<td><a class='link' href='detalji_pjesme.php?naziv=" . urlencode($naziv) . "'>$naziv</a></td>";    echo '<td>';
    echo '<audio controls>';
    echo '<source src="' . $poveznica . '" type="audio/mpeg">';
    echo '</audio>';
    echo '</td>';
    echo "<td>$opis</td>";
    echo "<td>$broj_svidanja</td>";

    echo "<td>$datum_vrijeme_kupnje</td>";
    echo "<td>$medijska_kuca_id</td>";
    echo "<td>$datum_vrijeme_kreiranja</td>";

    echo "</tr>";
}
echo "</tbody>";
echo "</table>";

$sql = "SELECT COUNT(*) FROM pjesma WHERE korisnik_id = $aktivni_korisnik_id";
$rs = izvrsiUpit($spoji, $sql);
$broj_redaka = mysqli_fetch_array($rs)[0];
$broj_stranica = ceil($broj_redaka / $vel_str);

echo '<div id="paginacija">';
for ($i = 1; $i <= $broj_stranica; $i++) {
    $activeClass = ($stranica == $i) ? " aktivna" : "";
    echo "<a class='link$activeClass' href=\"moje_pjesme.php?stranica=$i\">$i</a>";
}
echo '</div>';

zatvoriVezuNaBazu($spoji);
include("podnozje.php");
?>
