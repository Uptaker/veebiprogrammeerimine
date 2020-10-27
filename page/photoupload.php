<?php
require("../../../config.php");
require("fnc_films.php");
require("usesession.php");

$photouploaddir_orig = "../photoupload_orig/";
$photouploaddir = "../photoupload/";
$filenameprefix = "vp_";
$filename = null;
$inputerror = "";
$filesizelimit = 1048576;
//$filesizelimit = 5120000;
$notice = null;

$photoMaxWidth = 600;
$photoMaxHeight = 400;

//kui klikkiti submit, siis.. 
if(isset($_POST["photosubmit"])) {
	var_dump($_POST);
	var_dump($_FILES);
	$check = getimagesize($_FILES["photoinput"]["tmp_name"]);
	if($check !== false) {
		if($check["mime"] == "image/jpeg") {
			$filetype = "jpg";
		}
		if($check["mime"] == "image/png") {
			$filetype = "png";
		}
		if($check["mime"] == "image/gif") {
			$filetype = "gif";
		}
	} else {
		$inputerror = "Valitud fail ei ole pilt! ";
	}
	//kas on sobiva failisuurusega
	if(empty($inputerror) and $_FILES["photoinput"]["size"] > $filesizelimit) {
		$inputerror = "Liiga suur fail!";
	}

	//loome uue faili nime
	$timestamp = microtime(1) * 10000;
	$filename = $filenameprefix .$timestamp ."." .$filetype;

	//kas fail on olemas
	if(file_exists($photouploaddir_orig .$filename)) {
		$inputerror = "Selle nimega fail on olemas!";
	}

	if(empty($inputerror)) {
		$target = $photouploaddir .$filename;
		//muudame suurust..
		//loome pikslikogumi, pildi objekti
		if($filetype == "jpg") {
			$mytempimage = imagecreatefromjpeg($_FILES["photoinput"]["tmp_name"]);
		}
		if($filetype == "png") {
			$mytempimage = imagecreatefrompng($_FILES["photoinput"]["tmp_name"]);
		}
		if($filetype == "gif") {
			$mytempimage = imagecreatefromgif($_FILES["photoinput"]["tmp_name"]);
		}

		//TEEME KINDLAKS ORIGINAALSUURUSE
		$imagew = imagesx($mytempimage);
		$imageh = imagesy($mytempimage);

		if($imagew > $photoMaxWidth or $imageh > $photoMaxHeight) {
			if($imagew / $photoMaxWidth > $imageh / $photoMaxHeight) {
				$photosizeratio = $imagew / $photoMaxWidth;
			} else {
				$photosizeratio = $imageh / $photoMaxHeight;
			}
			//ARVUTAME UUED MÕÕDUD
			$neww = round($imagew / $photosizeratio);
			$newh = round($imageh / $photosizeratio);

			//teeme uue pikslikogumi
			$mynewtempimage = imagecreatetruecolor($neww, $newh);
			//kirjutame järelejäävad pikslid uuele pildile
			imagecopyresampled($mynewtempimage, $mytempimage, 0, 0, 0, 0, $neww, $newh, $imagew, $imageh);
			//salvestame
			$notice = saveImage($mynewtempimage, $filetype, $target);
		} else {
			$notice = saveImage($mytempimage, $filetype, $target);
		}

		if(move_uploaded_file($_FILES["photoinput"]["tmp_name"], $photouploaddir_orig .$filename)) {
			$notice .= " Originaalpildi salvestamine õnnestus!";
		} else {
			$notice .= " Originaalpildi salvestamisel tekkis tõrge"; 
		}
	}
}


function saveImage($mynewtempimage, $filetype, $target) {
	$notice = null;
	//salvestame faili
	if($filetype == "jpg") {
		if(imagejpeg($mynewtempimage, $target, 100 )) {
			$notice = "Vähendatud pildi salvestamine õnnestus!";
		} else {
			$notice = "Vähendatud pildi salvestamisel tekkis tõrge!";
		}
	}
	if($filetype == "png") {
		if(imagepng($mynewtempimage, $target, 7 )) {
			$notice = "Vähendatud pildi salvestamine õnnestus!";
		} else {
			$notice = "Vähendatud pildi salvestamisel tekkis tõrge!";
		}
	}
	if($filetype == "gif") {
		if(imagegif($mynewtempimage, $target)) {
			$notice = "Vähendatud pildi salvestamine õnnestus!";
		} else {
			$notice = "Vähendatud pildi salvestamisel tekkis tõrge!";
		}
	}
	imagedestroy($mynewtempimage);
	return $notice;
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
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
			<label for="photoinput">Vali pilt</label><br>
			<input id="photoinput" name="photoinput" type="file" required><br><br>
			<label for="altinput">Lisa pildi lühikirjeldus (alternatiivtekst)</label><br>
			<input id="altinput" name="altinput" type="text"><br><br>
			<label>Privaatsusaste</label><br>
			<input id="privinput1" name="privinput" type="radio" value="1">
			<label for="privinput1">Privaatne<label>
			<input id="privinput2" name="privinput" type="radio" value="2">
			<label for="privinput2">Sisseloginud<label>
			<input id="privinput3" name="privinput" type="radio" value="3">
			<label for="privinput3">Avalik<label><br><br>
			<input type="submit" name="photosubmit" value="Salvesta pilt">
			<p><?php echo $inputerror; echo $notice; ?>
		</form>
	</body>
</html>