<?php
  require("usesession.php");

  require("../../../config.php");
  require("fnc_photo.php");
    
  $notice = "";
  $filenameprefix = "vp_";
  $origphotodir = "../photoupload_orig/";
  $normalphotodir = "../photoupload/";
  $thumbphotodir = "../thumbnail/";
  $publicphotothumbsHTML = readPublicPhotoThumbs();
    
  

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
  <h2>Fotogalerii</h2>
<?php
	echo $publicphotothumbsHTML;
?>

  
  
</body>
</html>