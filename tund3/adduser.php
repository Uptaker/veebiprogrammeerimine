<?php
require("../../../config.php");
require("header.php");

$inputerror = "";
$firstnameerror="";
$lastnameerror="";
$emailerror="";
$passworderror="";
$password2error="";
$gendererror ="";

$genderinput = "";
$lastname = "";
$password = "";
$password2 = "";
$firstname = "";
$email = "";
	//kui üks lahtritest on tühi
if(isset($_POST["usersubmit"])) {
	if(empty($_POST["firstnameinput"]) or empty($_POST["lastnameinput"]) or empty($_POST["emailinput"]) or empty($_POST["passwordinput"]) or empty($_POST["passwordinput2"])) {
		$inputerror .= "Osa infost on sisestamata!";
		$lastname = $_POST["lastnameinput"];
		$password = $_POST["passwordinput"];
		$password2 = $_POST["passwordinput2"];
		$firstname = $_POST["firstnameinput"];
		$email = $_POST["emailinput"];
	}
	// if(empty($_POST["genderinput"])) {
	// 	$gendererror = "Sugu on sisestamata!";
	if(empty($_POST["gendermale"])) {
		$gendererror = "Sugu on sisestamata!";
	}
	if($_POST["genderfemale"] = 0) {
		$gendererror = "Sugu on sisestamata!";
	}
	if(strlen($_POST["passwordinput"] < 8)) {
		$passworderror .= "Salasõna peab sisaldama kahesa ühikut!";
		$lastname = $_POST["lastnameinput"];
		$password = $_POST["passwordinput"];
		$password2 = $_POST["passwordinput2"];
		$firstname = $_POST["firstnameinput"];
		$email = $_POST["emailinput"];
	}
	if($_POST["passwordinput"] != $_POST["passwordinput2"]) {
		$password2error .= "Teine salasõna ei ole sama, mis esimene!";
		$lastname = $_POST["lastnameinput"];
		$password = $_POST["passwordinput"];
		$password2 = $_POST["passwordinput2"];
		$firstname = $_POST["firstnameinput"];
		$email = $_POST["emailinput"];
	}
	if(empty($inputerror) and empty($passworderror) and empty($password2error)) {
		$inputerror = "ALL GOOD HERE!";
	}


}

?>
<!DOCTYPE html>
<html lang="en">
	<body>
		<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise pilt">
		<ul>
			<li><a href="home.php">Avaleht</a></li>
		</ul>
		<form method="POST">
			<p>Üldinfo:</p>
			<label for="firstnameinput">Eesnimi</label>
			<input type="text" name="firstnameinput" id="firstnameinput" value="<?php echo $firstname; ?>">
			<br>
			<label for="lastnameinput">Perekonnanimi</label>
			<input type="text" name="lastnameinput" id="lastnameinput" value="<?php echo $lastname; ?>">
			<br>
			<label for="gendermale">Mees</label>
			<input type="radio" name="genderinput" id="gendermale" value="1" <?php if($genderinput == "1") { echo "checked"; }?>>
			<label for="genderfemale">Naine</label>
			<input type="radio" name="genderinput" id="genderfemale" value="2" <?php if($genderinput == "2") { echo "checked"; }?>>
			<span><?php echo $gendererror; ?></span>
			<br>
			<p>Kasutajatunnused:</p>
			<label for="emailinput">E-mail</label>
			<input type="email" name="emailinput" id="emailinput" value="<?php echo $email; ?>">
			<br>
			<label for="passwordinput">Salasõna</label>
			<input type="password" name="passwordinput" id="passwordinput" value="<?php echo $password; ?>">
			<span><?php echo $passworderror; ?></span>
			<br>
			<label for="passwordinput2">Salasõna teist korda</label>
			<input type="password" name="passwordinput2" id="passwordinput2" value="<?php echo $password2; ?>">
			<span><?php echo $password2error; ?></span>
			<br>
			<input type="submit" name="usersubmit" value="Salvesta kasutaja info">
			<p><?php echo $inputerror; ?></p>
		</form>
	</body>
</html>