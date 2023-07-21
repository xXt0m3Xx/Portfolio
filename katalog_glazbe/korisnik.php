<?php
	include("zaglavlje.php");
	$spoji=spajanjeNaBazu();
?>
<?php
	$greska="";
	if(isset($_POST['submit'])){
		foreach ($_POST as $key => $value)if(strlen($value)==0)$greska="Sva polja za unos su obavezna";
		if(empty($greska)){
			$id=$_POST['novi'];
			$tip=$_POST['tip'];
            $med_kuca=$_POST['med_kuca'];
			$korime=$_POST['korime'];
			$ime=$_POST['ime'];
			$prezime=$_POST['prezime'];
			$email=$_POST['email'];
			$lozinka=$_POST['lozinka'];

			if($id==0){
				$sql="INSERT INTO korisnik
				(tip_korisnika_id,medijska_kuca_id,korime,ime,prezime,email,lozinka)
				VALUES
				($tip,'$med_kuca','$korime','$ime','$prezime','$email','$lozinka');
				";
			}
			else{
				$sql="UPDATE korisnik SET
					tip_korisnika_id='$tip',
                    korime='$korime',
					ime='$ime',
					prezime='$prezime',
					email='$email',
					lozinka='$lozinka'
					WHERE korisnik_id='$id'
				";
			}
			izvrsiUpit($spoji,$sql);
			header("Location:korisnici.php");
		}
	}
	if(isset($_GET['korisnik'])){
		$id=$_GET['korisnik'];
		if($aktivni_korisnik_tip==2)$id=$_SESSION["aktivni_korisnik_id"]; 
		$sql="SELECT * FROM korisnik WHERE korisnik_id='$id'";
		$rs=izvrsiUpit($spoji,$sql);
		list($id,$tip,$med_kuca,$korime,$ime,$prezime,$email,$lozinka)=mysqli_fetch_array($rs);
	}
	else{
		$tip=2;
		$med_kuca="";
		$korime="";
		$ime="";
		$prezime="";
		$email="";
		$lozinka="";
	}
	if(isset($_POST['reset']))header("Location:korisnik.php");
?>
<form method="POST" action="<?php if(isset($_GET['korisnik']))echo "korisnik.php?korisnik=$id";else echo "korisnik.php";?>">
	<table>
		<caption>
			<?php
				if(isset($id)&&$aktivni_korisnik_id==$id)echo "<h3>Uredi moje podatke</h3>";
				else if(!empty($id))echo "<h3>Uredi korisnika</h3>";
				else echo "<h3>Dodaj korisnika</h3>";
			?>
		</caption>
		<tbody>
			<tr>
				<td colspan="2" >
					<input type="hidden" name="novi" value="<?php if(!empty($id))echo $id;else echo 0;?>"/>
				</td>
			</tr>

			<tr>
				<td class="lijevi">
					<label for="korime"><strong>Korisničko ime:</strong></label>
				</td>
				<td>
					<input type="text" name="korime" id="korime"
						<?php
							if(isset($id))echo "readonly='readonly'";
						?>
						value="<?php if(!isset($_POST['korime']))echo $korime; else echo $_POST['korime'];?>" size="120" minlength="5" maxlength="50"
						placeholder="Korisničko ime ne smije sadržavati praznine, treba uključiti minimalno 5 znakova i započeti malim početnim slovom"
						required="required"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="ime"><strong>Ime:</strong></label>
				</td>
				<td>
					<input type="text" name="ime" id="ime" value="<?php if(!isset($_POST['ime']))echo $ime; else echo $_POST['ime'];?>"
						size="120" minlength="1" maxlength="50" placeholder="Ime treba započeti velikim početnim slovom" required="required"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="prezime"><strong>Prezime:</strong></label>
				</td>
				<td>
					<input type="text" name="prezime" id="prezime" value="<?php if(!isset($_POST['prezime']))echo $prezime; else echo $_POST['prezime'];?>"
						size="120" minlength="1" maxlength="50" placeholder="Prezime treba započeti velikim početnim slovom" required="required"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="med_kuca"><strong>ID medijske kuce:</strong></label>
				</td>
				<td>
                <input <?php if(!empty($med_kuca))echo "type='number'"; else echo "type='number'";?>
						name="med_kuca" id="med_kuca" value="<?php if(!isset($_POST['med_kuca']))echo $med_kuca; else echo $_POST['med_kuca'];?>"
						size="120" minlength="8" maxlength="50"
						required="required"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="email"><strong>E-mail:</strong></label>
				</td>
				<td>
					<input type="email" name="email" id="email" value="<?php if(!isset($_POST['email']))echo $email; else echo $_POST['email'];?>"
						size="120" minlength="5" maxlength="50" placeholder="nesto@nesto.nesto" required="required"/>
				</td>
			</tr>
			<?php
				if($_SESSION['aktivni_korisnik_tip']==0){
			?>
			<tr>
				<td><label for="tip"><strong>Tip korisnika:</strong></label></td>
				<td>
					<select id="tip" name="tip">
						<?php
							if(isset($_POST['tip'])){
								echo '<option value="0"';if($_POST['tip']==0)echo " selected='selected'";echo'>Administrator</option>';
								echo '<option value="1"';if($_POST['tip']==1)echo " selected='selected'";echo'>Moderator</option>';
								echo '<option value="2"';if($_POST['tip']==2)echo " selected='selected'";echo'>Registrirani Korisnik</option>';
							}
							else{
								echo '<option value="0"';if($tip==0)echo " selected='selected'";echo'>Administrator</option>';
								echo '<option value="1"';if($tip==1)echo " selected='selected'";echo'>Moderator</option>';
								echo '<option value="2"';if($tip==2)echo " selected='selected'";echo'>Registrirani Korisnik</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<?php
				}
			?>
			<tr>
				<td>
					<label for="lozinka"><strong>Lozinka:</strong></label>
				</td>
				<td>
				<input <?php if(!empty($lozinka))echo "type='text'"; else echo "type='password'";?>
						name="lozinka" id="lozinka" value="<?php if(!isset($_POST['lozinka']))echo $lozinka; else echo $_POST['lozinka'];?>"
						size="120" minlength="2" maxlength="50"
						placeholder="Lozinka treba sadržati minimalno 2 znaka"
						required="required"/>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center;">
					<?php
						if(isset($id)&&$aktivni_korisnik_id==$id||!empty($id))echo '<input type="submit" name="submit" value="Pošalji"/>';
						else echo '<input type="submit" name="reset" value="Izbriši"/><input type="submit" name="submit" value="Pošalji"/>';
					?>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<?php
	zatvoriVezuNaBazu($spoji);
	include("podnozje.php");
?>
