<?php
	include("zaglavlje.php");
	$spoji=spajanjeNaBazu();
?>  
<?php
	if(isset($_GET['logout'])){
		unset($_SESSION["aktivni_korisnik"]);
		unset($_SESSION['aktivni_korisnik_ime']);
		unset($_SESSION["aktivni_korisnik_tip"]);
		unset($_SESSION["aktivni_korisnik_id"]);
		session_destroy();
		header("Location:index.php");
	}

	$greska= "";
	if(isset($_POST['submit'])){
		$kor_ime=mysqli_real_escape_string($spoji,$_POST['korisnicko_ime']);
		$lozinka=mysqli_real_escape_string($spoji,$_POST['lozinka']);

		if(!empty($kor_ime)&&!empty($lozinka)){
			$sql="SELECT korisnik_id,tip_korisnika_id,ime,prezime FROM korisnik WHERE korime='$kor_ime' AND lozinka='$lozinka'";
			$rs=izvrsiUpit($spoji,$sql);
			if(mysqli_num_rows($rs)==0)$greska="Ne postoji korisnik s navedenim korisničkim imenom i lozinkom";
			else{
				list($id,$tip,$ime,$prezime)=mysqli_fetch_array($rs);
				$_SESSION['aktivni_korisnik']=$kor_ime;
				$_SESSION['aktivni_korisnik_ime']=$ime." ".$prezime;
				$_SESSION["aktivni_korisnik_id"]=$id;
				$_SESSION['aktivni_korisnik_tip']=$tip;
				header("Location:index.php");
			}
		}
		else $greska = "Molim unesite korisničko ime i lozinku";
	}
?>
<form id="prijava" name="prijava" method="POST" action="login.php" onsubmit="return validacija();">
	<table>
		<caption>Prijava u sustav</caption>
		<tbody>
			<tr>
				<td>
					<input name="korisnicko_ime" id="korisnicko_ime" type="text" placeholder="Korisničko ime" size="70">
				</td>
			</tr>
			<tr>
				<td>
					<input name="lozinka" id="lozinka" type="password" placeholder="Lozinka" size="70">
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;">
					<input name="submit" type="submit" value="Prijavi se">
				</td>
			</tr>
		</tbody>
	</table>
</form>
<?php
	zatvoriVezuNaBazu($spoji);
	include("podnozje.php");
?>
