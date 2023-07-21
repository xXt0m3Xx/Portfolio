<?php
include("zaglavlje.php");
$spoji = spajanjeNaBazu();

if (session_id() == "") session_start();

$trenutna = basename($_SERVER["PHP_SELF"]);
$putanja = $_SERVER['REQUEST_URI'];
$aktivni_korisnik = 0;
$aktivni_korisnik_tip = -1;

if (isset($_SESSION['aktivni_korisnik'])) {
    $aktivni_korisnik = $_SESSION['aktivni_korisnik'];
    $aktivni_korisnik_ime = $_SESSION['aktivni_korisnik_ime'];
    $aktivni_korisnik_tip = $_SESSION['aktivni_korisnik_tip'];
    $aktivni_korisnik_id = $_SESSION["aktivni_korisnik_id"];
}

if (isset($_POST['submit'])) {
    $greska = '';

    foreach ($_POST as $key => $value) {
        if (strlen($value) == 0) {
            $greska = "Sva polja za unos su obavezna";
            break;
        }
    }
    if (empty($greska)) {
        $naziv = $_POST['naziv'];
        $poveznica = $_POST['poveznica'];
        $opis = $_POST['opis'];

        if (isset($_POST['datum'])) {
            date_default_timezone_set("Europe/Zagreb");
            $date = $_POST['datum'];

            $formattedDate = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $date)));

            $sql = "INSERT INTO pjesma (naziv, poveznica, opis, pjesma_id, korisnik_id, medijska_kuca_id, datum_vrijeme_kreiranja, datum_vrijeme_kupnje, broj_svidanja) 
            VALUES ('$naziv', '$poveznica', '$opis', NULL, '$aktivni_korisnik_id', NULL, '$formattedDate', NULL, NULL)";

            if (mysqli_query($spoji, $sql)) {
                echo 'Pjesma je uspješno kreirana';
            } else {
                echo 'Error: ' . mysqli_error($spoji);
            }
        } else {
            echo 'Datum nije postavljen.';
        }
    } else {
        echo $greska;
    }
}

zatvoriVezuNaBazu($spoji);
include("podnozje.php");
?>

<body>
    <h2>Kreirajte novu pjesmu</h2>
    <?php
        switch (true) {
            case $trenutna:
                switch ($aktivni_korisnik_tip >= 0) {
                    case true:
    ?>
                        <form method="POST" action="kreiranje_pjesme.php" id="kreiranje">
                            <div>
                                <label for="naziv">Naziv:</label>
                                <input type="text" name="naziv" id="naziv" size="50" required>
                            </div>

                            <div>
                                <label for="poveznica">Poveznica:</label>
                                <input type="text" name="poveznica" id="poveznica" size="50" required>
                            </div>
                            
                            <div>
                                <label for="opis">Opis:</label>
                                <input type="text" name="opis" id="opis" size="50" required>
                            </div>
                            
                            <div>
                                <label for="datum">Datum kreiranja</label>
                                <input type="text" name="datum" id="datum" size="20" value="<?php echo date('d.m.Y H:i:s'); ?>" required>
                            </div>
                            
                            <div>
                                <input type="submit" name="submit" value="Kreiraj">
                            </div>
                        </form>
    <?php
                        break;

                    default:
                        echo "<p>Samo registrirani korisnici mogu kreirati pjesme.</p>";
                        break;
                }
            default:
                break;
        }
    ?>
</body>