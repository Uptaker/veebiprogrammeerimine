<?php
  require("usesession.php");

  require("../../../config.php");
  require("fnc_photo.php");
  require("fnc_common.php");
  require("classes/photoupload_class.php");
    
  $notice = "";
  $filetype = "";
  $error = null;
  $filenameprefix = "vp_";
  $origphotodir = "../photoupload_orig/";
  $normalphotodir = "../photoupload/";
  $thumbphotodir = "../thumbnail/";
  $watermarkimage = "../img/vp_logo_w100_overlay.png";
  $maxphotowidth = 600;
  $maxphotoheight = 400;
  $thumbsize = 100;
  $filename = null;
  $privacy = 1;
  $alttext = null;
  $maxfilesize = 1048576;
    
  //kui klikiti submit, siis ...
  if(isset($_POST["photosubmit"])){
	$privacy = intval($_POST["privinput"]);
	$alttext = test_input($_POST["altinput"]);
	//var_dump($_POST);
	//var_dump($_FILES);
	//kas on üldse pilt
	if(isset($_FILES["photoinput"]["tmp_name"])){
		//võtan klassi kasutusele
		$myphoto = new Photoupload($_FILES["photoinput"], $filetype);

		//kontrollin faili: funktsioon annab tagasi array($error, $filename, $filetype)
		$checkphoto = $myphoto->checkPhoto($filenameprefix, $origphotodir, $maxfilesize);
		$error = $checkphoto[0];
		$filename = $checkphoto[1];
		$filetype = $checkphoto[2];

		unset($myphoto);
		}

		if(empty($error)){
			$myphoto = new Photoupload($_FILES["photoinput"], $filetype);

			
			//muudame pildi suurust
			//$mynewimage = resizePhoto($mytempimage, $maxphotowidth, $maxphotoheight, true);
			$myphoto->resizePhoto($maxphotowidth, $maxphotoheight, true);
			
			//lisan vesimärgi
			$myphoto->addWatermark($watermarkimage);
			
			//salvestan vähendatud foto
			//$result = savePhotoFile($mynewimage, $filetype, $normalphotodir .$filename);
			$result = $myphoto->savePhotoFile($normalphotodir .$filename);
			if($result == 1){
				$notice .= "Vähendatud pildi salvestamine õnnestus!";
			} else {
				$error .= "Vähendatud pildi salvestamisel tekkis tõrge!";
			}
			
			//imagedestroy($mynewimage);
			
			//teeme pisipildi
			//$mynewimage = resizePhoto($mytempimage, $thumbsize, $thumbsize);
			$myphoto->resizePhoto($thumbsize, $thumbsize);
			
			//$result = savePhotoFile($mynewimage, $filetype, $thumbphotodir .$filename);
			$result = $myphoto->savePhotoFile($thumbphotodir .$filename);
			if($result == 1){
				$notice .= "Pisipildi salvestamine õnnestus!";
			} else {
				$error .= "Pisipildi salvestamisel tekkis tõrge!";
			}
			
			if(empty($error)){
				$result = $myphoto->saveOriginalPhoto($origphotodir .$filename);
				if($result == 1){
					$notice .= " Originaalfaili üleslaadimine õnnestus!";
				} else {
					$error .= " Originaalfaili üleslaadimisel tekkis tõrge!";
				}
			}
			
			if(empty($error)){
				$result = storePhotoData($filename, $alttext, $privacy);
				if($result == 1){
					$notice .= " Pildi info lisati andmebaasi!";
				} else {
					$error .= "Pildi info andmebaasi salvestamisel tekkis tõrge!";
				}
			} else {
				$error .= " Tekkinud vigade tõttu pildi andmeid ei salvestatud!";
			}
			//imagedestroy($mytempimage);
			unset($myphoto);
		}
	}
  

  require("header.php");
?>
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See konkreetne leht on loodud veebiprogrammeerimise kursusel aasta 2020 sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  
  <ul>
    <li><a href="?logout=1">Logi välja</a>!</li>
    <li><a href="home.php">Avaleht</a></li>
  </ul>
  
  <hr>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
    <label for="photoinput">Vali pildifail!</label>
	<input id="photoinput" name="photoinput" type="file" required>
	<br>
	<label for="altinput">Lisa pildi lühikirjeldus (alternatiivtekst)</label>
	<input id="altinput" name="altinput" type="text" value="<?php echo $alttext; ?>">
	<br>
	<label>Privaatsustase</label>
	<br>
	<input id="privinput1" name="privinput" type="radio" value="1" <?php if($privacy == 1){echo " checked";} ?>>
	<label for="privinput1">Privaatne (ainult ise näen)</label>
	<input id="privinput2" name="privinput" type="radio" value="2" <?php if($privacy == 2){echo " checked";} ?>>
	<label for="privinput2">Klubi liikmetele (sisseloginud kasutajad näevad)</label>
	<input id="privinput3" name="privinput" type="radio" value="3" <?php if($privacy == 3){echo " checked";} ?>>
	<label for="privinput3">Avalik (kõik näevad)</label>
	<br>	
	<input type="submit" name="photosubmit" value="Lae foto üles">
  </form>
  <p>
  <?php
	echo $error;
	echo $notice;
  ?>
  </p>
  
</body>
</html>