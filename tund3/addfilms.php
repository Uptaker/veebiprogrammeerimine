<?php
require("../../../config.php");
require("header.php");
require("fnc_films.php");

$inputerror = "";
//kui klikkiti submit, siis.. 
if(isset($_POST["filmsubmit"])) {
	if(empty($_POST["titleinput"]) or empty($_POST["genreinput"]) or empty($_POST["studioinput"]) or empty($_POST["directorinput"])){
		$inputerror .= "Osa infot on sisestamata!";
	}
	if($_POST["yearinput"] > date("Y") or $_POST["yearinput"] < 1895) {
		$inputerror .= "Ebareaalne valmimisaasta!";
	}
	if(empty($inputerror)) {
		savefilm($_POST["titleinput"], $_POST["yearinput"], $_POST["durationinput"], $_POST["genreinput"], $_POST["studioinput"], $_POST["directorinput"]);
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
			<label for="titleinput">Filmi pealkiri</label>
			<input type="text" name="titleinput" id="titleinput" placeholder="Pealkiri">
			<br>
			<label for="yearinput">Filmi valmimisaasta</label>
			<input type="number" name="yearinput" id="yearinput" value="<?php echo date("Y")?>">
			<br>
			<label for="durationinput">Kestus</label>
			<input type="number" name="durationinput" id="durationinput" value="80">
			<br>
			<label for="genreinput">Žanr</label>
			<input type="text" name="genreinput" id="genreinput" placeholder="Žanr">
			<br>
			<label for="studioinput">Filmi tootja/stuudio</label>
			<input type="text" name="studioinput" id="studioinput" placeholder="Stuudio">
			<br>
			<label for="directorinput">Filmi lavastaja</label>
			<input type="text" name="directorinput" id="directorinput" placeholder="Lavastaja nimi">
			<br>
			<input type="submit" name="filmsubmit" value="Salvesta filmi info">
			<p><?php echo $inputerror?>
		</form>
	</body>
</html>