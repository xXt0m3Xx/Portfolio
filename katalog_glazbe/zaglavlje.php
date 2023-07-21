
<?php
    include("baza.php");
    $spoji=spajanjeNaBazu();
    if(session_id()=="")session_start();

    $trenutna=basename($_SERVER["PHP_SELF"]);
	$putanja=$_SERVER['REQUEST_URI'];
	$aktivni_korisnik=0;
	$aktivni_korisnik_tip=-1;
	$vel_str=5; 
	$vel_str_video=20; 
	
    
	if(isset($_SESSION['aktivni_korisnik'])){
		$aktivni_korisnik=$_SESSION['aktivni_korisnik'];
		$aktivni_korisnik_ime=$_SESSION['aktivni_korisnik_ime'];
		$aktivni_korisnik_tip=$_SESSION['aktivni_korisnik_tip'];
		$aktivni_korisnik_id=$_SESSION["aktivni_korisnik_id"];
	}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="autor" content="Tome Trnjanac">
	<meta name="datum" content="28.6.2023.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="stil.css" rel="stylesheet" type="text/css">
    <title>Glazbeni katalog</title>
</head>
<body>
<header>
			<div>
				<h1>Glazbeni katalog</h1>
				<br/>
				<?php
					
					if($aktivni_korisnik===0){
						echo "<div><strong>Status: </strong>Neprijavljeni korisnik</div><br/>";
						echo "<a class='link' href='login.php'>prijava</a>";
					}
					else{
						echo "<div><strong>Status: </strong>Dobrodošli, $aktivni_korisnik_ime</div><br/>";
                        if($aktivni_korisnik_tip==0){
                            echo "<div> <strong>Administrator</strong></div>";
                        }elseif($aktivni_korisnik_tip==1){
                            echo "<div><strong>Moderator</strong></div>";
                        }else{
                            echo "<div><strong>Registrirani Korisnik</strong></div>";
                        }
						echo "<a class='link' href='login.php?logout=1'>odjava</a>";
					}
				?>
			</div>
		</header>
        <nav id="navigacija" class="meni">
			<?php
				switch(true){
					case $trenutna:
						switch($aktivni_korisnik_tip>=0) {
							case 'true':
								echo '<a href="index.php"';
								if($trenutna=="index.php")echo ' class="trenutno"';
								echo ">POČETNA</a>";
								echo '<a href="pjesme.php"';
								if($trenutna=="pjesme.php")echo ' class="trenutno"';
								echo ">POPIS PJESAMA</a>";
								echo '<a href="moje_pjesme.php"';
								if($trenutna=="moje_pjesme.php")echo ' class="trenutno"';
								echo ">MOJE PJESME</a>";
								echo '<a href="kreiranje_pjesme.php"';
								if($trenutna=="kreiranje_pjesme.php")echo ' class="trenutno"';
								echo ">KREIRAJ PJESMU</a>";
								echo '<a href="korisnici.php"';
								if($trenutna=="korisnici.php")echo ' class="trenutno"';
								echo ">KORISNICI</a>";
								break;

							default:
								echo '<a href="index.php"';
								if($trenutna=="index.php")echo ' class="trenutno"';
								echo ">POČETAK</a>";
								echo '<a href="pjesme.php"';
								if($trenutna=="pjesme.php")echo ' class="trenutno"';
								echo ">POPIS PJESAMA</a>";
                                echo '<a href="o_autoru.php"';
								if($trenutna=="o_autoru.php")echo ' class="trenutno"';
								echo ">O AUTORU</a>";
								break;
						}
					default:
						break;
				}
			?>
		</nav>
</body>
