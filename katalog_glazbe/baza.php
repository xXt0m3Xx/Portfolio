<?php
    define("POSLUZITELJ","localhost");
	define("BAZA","iwa_2021_vz_projekt");
	define("KORISNIK","iwa_2021");
	define("LOZINKA","foi2021");

    function spajanjeNaBazu(){
        $konekcija=mysqli_connect(POSLUZITELJ,KORISNIK,LOZINKA);
        if(!$konekcija) echo "Greška".mysqli_connect_error();
        mysqli_select_db($konekcija,BAZA);
        if(mysqli_error($konekcija)!=="")echo "Greška".mysqli_error($konekcija);
        mysqli_set_charset($konekcija,"utf8");
        if(mysqli_error($konekcija)!=="") echo "Greška".mysqli_error($konekcija);
        return $konekcija;
    }
    
    function izvrsiUpit($konekcija,$upit){
		$rezultat=mysqli_query($konekcija,$upit);
		if(mysqli_error($konekcija)!=="")echo "GREŠKA: Problem sa upitom: ".$upit." : u datoteci baza.php funkcija izvrsiUpit: ".mysqli_error($konekcija);
		return $rezultat;
	}

    

	function zatvoriVezuNaBazu($konekcija){
		mysqli_close($konekcija);
	}


?>