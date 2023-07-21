<?php
	include("zaglavlje.php");
	$spoji=spajanjeNaBazu();
?>

<?php
	$sql="SELECT COUNT(*) FROM korisnik";
	$rs=izvrsiUpit($spoji,$sql);
	$red=mysqli_fetch_array($rs);
	$broj_redaka=$red[0];
	$broj_stranica=ceil($broj_redaka/$vel_str);

	$sql="SELECT * FROM korisnik ORDER BY korisnik_id LIMIT ".$vel_str;
	if(isset($_GET['stranica'])){
		$sql=$sql." OFFSET ".(($_GET['stranica']-1)*$vel_str);
		$aktivna=$_GET['stranica'];
	}
	else $aktivna = 1;

	$rs=izvrsiUpit($spoji,$sql);
	echo "<table>";
	echo "<caption><h3>Popis korisnika sustava</h3></caption>";
	echo "<thead><tr>
		<th>Korisničko ime</th>
		<th>Ime</th>
		<th>Prezime</th>
		<th>E-mail</th>
		<th>Lozinka</th>
		<th></th>";
	echo "</tr></thead>";
	echo "<tbody>";
	while(list($id,$tip,$med_kuca,$kor_ime,$ime,$prezime,$email,$lozinka)=mysqli_fetch_array($rs)){
		echo "<tr>
			<td>$kor_ime</td>
			<td>$ime</td>";
		echo "<td>".(empty($prezime)?"&nbsp;":"$prezime")."</td>
			<td>".(empty($email)?"&nbsp;":"$email")."</td>
			<td>$lozinka</td>
			";
			if($aktivni_korisnik_tip==0||$aktivni_korisnik_tip==1)echo "<td><a class='link' href='korisnik.php?korisnik=$id'>UREDI</a></td>";
			else if(isset($_SESSION["aktivni_korisnik_id"])&&$_SESSION["aktivni_korisnik_id"]==$id) echo '<td><a class="link" href="korisnik.php?korisnik='.$_SESSION["aktivni_korisnik_id"].'">UREDI</a></td>';
			else echo "<td></td>";
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";


	echo '<div id="paginacija">';
for ($i = 1; $i <= $broj_stranica; $i++) {
    echo "<a class='link";
    if ($stranica == $i) {
        echo " aktivna";
    }
    echo "' href=\"korisnici.php?stranica=$i\">$i</a>";
}
echo '</div>';
if($aktivni_korisnik_tip==0)echo '<a class="link" href="korisnik.php">DODAJ KORISNIKA </a>';
	if(isset($_SESSION["aktivni_korisnik_id"]))echo '<a class="link" href="korisnik.php?korisnik='.$_SESSION["aktivni_korisnik_id"].'"> UREDI MOJE PODATKE</a>';
	echo '</div>';
?>

<?php
	zatvoriVezuNaBazu($spoji);
	include("podnozje.php");
?>
