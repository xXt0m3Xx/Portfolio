<?php
include("zaglavlje.php");
$spoji = spajanjeNaBazu();

if (isset($_GET['stranica'])) {
    $stranica = $_GET['stranica'];
} else {
    $stranica = 1;
}

if (isset($_GET['medijska_kuca_id'])) {
    $medijska_kuca_id = $_GET['medijska_kuca_id'];
} else {
    $medijska_kuca_id = "";
}

if (isset($_GET['datum_kreiranja'])) {
    $datum_kreiranja = $_GET['datum_kreiranja'];
} else {
    $datum_kreiranja = "";
}

$offset = ($stranica - 1) * $vel_str;

$sql = "SELECT pjesma.korisnik_id AS korisnik_id, korisnik.korime AS korime, naziv, poveznica, opis, broj_svidanja, datum_vrijeme_kreiranja, pjesma.medijska_kuca_id
    FROM pjesma
    INNER JOIN korisnik ON pjesma.korisnik_id = korisnik.korisnik_id
    WHERE 1";

if ($aktivni_korisnik === 0) {
    $sql .= " AND pjesma.medijska_kuca_id IS NOT NULL";
}

if (!empty($medijska_kuca_id)) {
    $sql .= " AND pjesma.medijska_kuca_id = $medijska_kuca_id";
}

if (!empty($datum_kreiranja)) {
    $datum_kreiranja_formatted = date('Y-m-d', strtotime($datum_kreiranja));

    $sql .= " AND DATE_FORMAT(pjesma.datum_vrijeme_kreiranja, '%d.%m.%Y') >= '$datum_kreiranja_formatted'";
}

$sql .= " ORDER BY broj_svidanja
    LIMIT $vel_str
    OFFSET $offset";

$result = mysqli_query($spoji, $sql);

echo '<form method="GET" action="pjesme.php">';
echo '<label for="medijska_kuca_id">Filtriraj po ID-u medijske kuće </label>';
echo '<input type="text" id="medijska_kuca_id" name="medijska_kuca_id" value="'.$medijska_kuca_id.'">';
echo '<label for="datum_kreiranja"> Filtriraj od datuma kreiranja </label>';
echo '<input type="text" id="datum_kreiranja" name="datum_kreiranja" placeholder="dd:mm:yyyy" value="'.$datum_kreiranja.'">';
echo '<input type="submit" value="Filter">';
echo '</form>';

echo "<table>";
echo "<caption><h3>Popis pjesama kupljenih od medijskih kuća</h3></caption>";
echo "<thead><tr>
    <th>Kreairao</th>
    <th>Naziv pjesme</th>
    <th>Poveznica</th>
    <th>Opis pjesme</th>
    <th>Broj sviđanja</th>
    <th>Datum i vrijeme kreiranja</th>
    <th>ID medijske kuće</th>";
if ($aktivni_korisnik_tip == 1) {
    echo "<th>Akcija</th>";
}
echo "</tr></thead>";
echo "<tbody>";

while ($row = mysqli_fetch_assoc($result)) {
    $korisnikKorime = $row['korime'];
    $naziv = $row['naziv'];
    $poveznica = $row['poveznica'];
    $opis = $row['opis'];
    $broj_svidanja = $row['broj_svidanja'];
    $vrijeme_kreiranja = date('d.m.Y H:i:s', strtotime($row['datum_vrijeme_kreiranja']));
    $med_kuca = $row['medijska_kuca_id'];
    $bojareda = ($med_kuca === null && $aktivni_korisnik_tip == 1) ? 'boja-reda' : '';
    echo "<tr class='$bojareda'>";
    echo "<td>$korisnikKorime</td>";
    echo "<td>$naziv</td>";
    echo '<td>';
    echo '<audio controls>';
    echo '<source src="' . $poveznica . '" type="audio/mpeg">';
    echo '</audio>';
    echo '</td>';
    echo "<td>$opis</td>";
    echo "<td>$broj_svidanja</td>";
    echo "<td>$vrijeme_kreiranja</td>";
    echo "<td>$med_kuca</td>";
    if ($aktivni_korisnik_tip == 1 && $med_kuca == NULL) {
        echo "<td>";
        echo "<form method='POST' action='kupnja.php'>";
        echo "<input type='hidden' name='medijska_kuca_id' value='$med_kuca'>";
        echo "<button type='submit' name='kupi'>Kupi</button>";
        echo "</form>";
        echo "</td>";
    }
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";

$sql = "SELECT COUNT(*) FROM pjesma WHERE 1";

if ($aktivni_korisnik >= 1) {
    $sql .= " AND pjesma.medijska_kuca_id IS NOT NULL";
}

if (!empty($medijska_kuca_id)) {
    $sql .= " AND pjesma.medijska_kuca_id = $medijska_kuca_id";
}

if (!empty($datum_kreiranja)) {
    $datum_kreiranja_formatted = date('Y-m-d', strtotime($datum_kreiranja));
    $sql .= " AND DATE_FORMAT(pjesma.datum_vrijeme_kreiranja, '%d.%m.%Y') >= '$datum_kreiranja_formatted'";
}

$rs = izvrsiUpit($spoji, $sql);
$red = mysqli_fetch_array($rs);
$broj_redaka = $red[0];
$broj_stranica = ceil($broj_redaka / $vel_str);

echo '<div id="paginacija">';
for ($i = 1; $i <= $broj_stranica; $i++) {
    echo "<a class='link";
    if ($stranica == $i) {
        echo " aktivna";
    }
    echo "' href=\"pjesme.php?stranica=$i&medijska_kuca_id=$medijska_kuca_id&datum_kreiranja=$datum_kreiranja\">$i</a>";
}
echo '</div>';

zatvoriVezuNaBazu($spoji);
include("podnozje.php");
?>