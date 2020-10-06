<?php
require("../../../config.php");
require("fnc_user.php");
require("usesession.php");
require("fnc_common.php");

$notice = "";
$userdescription = "";
//kui klikkiti submit, siis.. 
if(isset($_POST["profilesubmit"])) {
	$userdescription = test_input($_POST["descriptioninput"]);
	
	$notice = storeuserprofile($userdescription, $_POST["bgcolorinput"], $_POST["txtcolorinput"]);
	$_SESSION["userbgcolor"] = $_POST["bgcolorinput"];
	$_SESSION["usertxtcolor"] = $_POST["txtcolorinput"];
}
require("header.php");
?>
<!DOCTYPE html>
<html lang="en">
	<body>
		<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise pilt">
		<p><a href="?logout=1"> Logi Välja</a></p>
		<ul>
			<li><a href="home.php">Avaleht</a></li>
		</ul>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<label for="descriptioninput">Minu lühikirjeldus</label> <br>
			<textarea rows="10" cols="80" name="descriptioninput" id="descriptioninput" placeholder="Minu lühikirjeldus..">
				<?php $userdescription; ?>
			</textarea>
			<br>
			<label for="bgcolorinput">Minu valitud taustavärv</label>
			<input type="color" name="bgcolorinput" id="bgcolorinput" value="<?php echo $_SESSION["userbgcolor"];?>">
			<br>
			<label for="txtcolorinput">Minu valitud tekstivärv</label>
			<input type="color" name="txtcolorinput" id="txtcolorinput" value="<?php echo $_SESSION["usertxtcolor"];?>">
			<br>
			<input type="submit" name="profilesubmit" value="Salvesta profiil">
			<p><?php echo $notice?>
		</form>
	</body>
</html>