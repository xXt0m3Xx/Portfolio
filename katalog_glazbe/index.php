<?php
include("zaglavlje.php");
$spoji=spajanjeNaBazu();
?>

<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Početna stranica</title>
</head>
<body>
    <p>Glazbeni katalog je web aplikacija koja omogućava korisnicima 
        pregled, pretraživanje i dodavanje glazbenih podataka kao što s
        u pjesme, izvođači, albumi i žanrovi. Korisnici mogu pretraživati 
        podatke, ocjenjivati pjesme, stvarati popise za reprodukciju i 
        dijeliti glazbene informacije s drugima. Cilj projekta je pružiti 
        intuitivan i funkcionalan sustav za organizaciju i upravljanje 
        glazbenim podacima te poboljšati korisničko iskustvo u otkrivanju 
        glazbe.</p>
</body>
</html>
<?php
zatvoriVezuNaBazu($spoji);
include("podnozje.php");
?>