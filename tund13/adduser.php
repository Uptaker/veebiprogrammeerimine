<?php
require("../../../config_vp2020.php");
require("header.php");
require("fnc_common.php");
require("fnc_user.php");

$monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];

//other errors
$notice = "";
$firstnameerror="";
$lastnameerror="";
$emailerror="";
$passworderror="";
$password2error="";
$gendererror ="";
//date errors
$birthdayerror = null;
$birthmontherror = null;
$birthyearerror = null;
$birthdateerror = null;

$gender = "";
$genderinput = "";
$lastname = "";
$password = "";
$password2 = "";
$firstname = "";
$email = "";
$birthday = null;
$birthyear = null;
$birthmonth = null;
// null is the same as $variable = "";
	//kui üks lahtritest on tühi
if(isset($_POST["usersubmit"])) {
	if(empty($_POST["firstnameinput"])) {
		$firstnameerror = "Eesnimi on sisestamata!";
	} else {
		$firstname = test_input($_POST["firstnameinput"]);
	}
	if(empty($_POST["lastnameinput"])) {
		$lastnameerror = "Perekonnanimi sisestamata!";
	} else {
		$lastname = test_input($_POST["lastnameinput"]);
	}
	if(empty($_POST["emailinput"])) {
		$emailerror = "E-mail sisestamata!";
	} else {
		$email = test_input($_POST["emailinput"]);
	}
	if(empty($_POST["passwordinput"])) {
		$passworderror = "Salasõna sisestamata!";
	}
	if(empty($_POST["passwordinput2"])) {
		$password2error = "Korduv salasõna sisestamata!";
		$password = test_input($_POST["passwordinput"]);
		$password2 = test_input($_POST["passwordinput2"]);
	}
	//kuupaev
	if(!empty($_POST["birthdayinput"])) {
		$birthday = intval($_POST["birthdayinput"]);
	} else {
		$birthdayerror = "Palun vali sünnikuupäev!";
	}
	//kuu
	if(!empty($_POST["birthmonthinput"])) {
		$birthmonth = intval($_POST["birthmonthinput"]);
	} else {
		$birthmontherror = "Palun vali sünnikuu!";
	}
	//aasta
	if(!empty($_POST["birthyearinput"])) {
		$birthyear = intval($_POST["birthyearinput"]);
	} else {
		$birthyearerror = "Palun vali sünniaasta!";
	}

	//kontrollime kuupäeva kehtivust
	if(!empty($birthday) and !empty($birthmonth) and !empty($birthyear)) {
		if(checkdate($birthmonth, $birthday, $birthyear)) {
			$tempdate = new DateTime($birthyear ."-" . $birthmonth ."-" .$birthday);
			$birthdate = $tempdate->format("Y-m-d");
		} else {
			$birthdateerror = "Kuupäev ei ole reaalne!";
		}
	}
	if(isset($_POST["genderinput"])){
		$gender = $_POST["genderinput"];
	  } else {
		  $gendererror = "Palun märgi sugu!";
	  }
	if(strlen($_POST["passwordinput"]) < 8){
	$passworderror .= "Liiga lühike salasõna (sisestasite ainult " .strlen($_POST["passwordinput"]) ." märki).";
	}
	if($_POST["passwordinput"] != $_POST["passwordinput2"]) {
		$password2error .= "Teine salasõna ei ole sama, mis esimene!";
	}
	//kui kõik on kontrollitud
	if(empty($passworderror) and empty($password2error) and empty($gendererror) and empty($birthdayerror) and empty($birthdateerror) and empty($birthmontherror) and empty($birthyearerror)) {
		$result = signup($firstname, $lastname, $email, $gender, $birthdate, $_POST["passwordinput"]);
		if($result == "ok") {
			$notice = "Kasutaja loodud!";
			$firstname = null;
			$lastname = null;
			$email = null;
			$gender = null;
			$birthdate = null;
			$birthyear = null;
			$birthmonth = null;
			$birthday = null;
		} else {
			$notice = "Tekkis tehniline tõrge: " .$result;
		}
		
	}


}

?>
<!DOCTYPE html>
<html lang="en">
	<body>



	<style>
			a:link{
		  color:black;
		}
		a:visited{
		  color:black;
		}
		a:hover{
		  color:red;
		  font-weight: bold;
		  
		}
		a:focus{
		  color:black;
		}
		a:active{
		  color:red;
		}
	</style>
		<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise pilt">
		<ul>
			<li><a href="page.php">Avaleht</a></li>
		</ul>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<p>Üldinfo:</p>
			<label for="firstnameinput">Eesnimi</label>
			<input type="text" name="firstnameinput" id="firstnameinput" value="<?php echo $firstname; ?>">
			<span><?php echo $firstnameerror; ?></span>
			<br>
			<label for="lastnameinput">Perekonnanimi</label>
			<input type="text" name="lastnameinput" id="lastnameinput" value="<?php echo $lastname; ?>">
			<span><?php echo $lastnameerror; ?></span>
			<br>
			<label for="gendermale">Mees</label>
			<input type="radio" name="genderinput" id="gendermale" value="1" <?php if($gender == "1") { echo "checked"; }?>>
			<label for="genderfemale">Naine</label>
			<input type="radio" name="genderinput" id="genderfemale" value="2" <?php if($gender == "2") { echo "checked"; }?>>
			<span><?php echo $gendererror; ?></span>
			<br>
			<br> 
			<label for="birthdayinput">Sünnipäev: </label>
		  <?php
			echo '<select name="birthdayinput" id="birthdayinput">' ."\n";
			echo '<option value="" selected disabled>päev</option>' ."\n";
			for ($i = 1; $i < 32; $i ++){
				echo '<option value="' .$i .'"';
				if ($i == $birthday){
					echo " selected ";
				}
				echo ">" .$i ."</option> \n";
			}
			echo "</select> \n";
		  ?>
	  <label for="birthmonthinput">Sünnikuu: </label>
	  <?php
	    echo '<select name="birthmonthinput" id="birthmonthinput">' ."\n";
		echo '<option value="" selected disabled>kuu</option>' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo '<option value="' .$i .'"';
			if ($i == $birthmonth){
				echo " selected ";
			}
			echo ">" .$monthnameset[$i - 1] ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <label for="birthyearinput">Sünniaasta: </label>
	  <?php
	    echo '<select name="birthyearinput" id="birthyearinput">' ."\n";
		echo '<option value="" selected disabled>aasta</option>' ."\n";
		for ($i = date("Y") - 15; $i >= date("Y") - 110; $i --){
			echo '<option value="' .$i .'"';
			if ($i == $birthyear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  <br>
	  <span><?php echo $birthdateerror ." " .$birthdayerror ." " .$birthmontherror ." " .$birthyearerror; ?></span>
	  <br>
			<p>Kasutajatunnused:</p>
			<label for="emailinput">E-mail</label>
			<input type="email" name="emailinput" id="emailinput" value="<?php echo $email; ?>">
			<span><?php echo $emailerror; ?></span>
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
			<span><?php echo $notice; ?></span>
		</form>
	</body>
</html>