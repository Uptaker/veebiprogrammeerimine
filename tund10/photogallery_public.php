<?php
  require("usesession.php");
  require("../../../config.php");
  require("fnc_photo.php");
  $tolink = '<link rel="stylesheet" type="text/css" href="style/gallery.css">' ."\n";
    
  $notice = "";
  $photouploaddir_orig = "../photoupload_orig/";
  $photouploaddir_normal = "../photoupload/";
  $photouploaddir_thumb = "../thumbnail/";
  $gallerypagelimit = 3;
  $page = 1;
  $photocount = countPublicPhotos(2);
  if(!isset($_GET["page"]) or $_GET["page"] < 1) {
    $page = 1;
  } elseif(round($_GET["page"] -1) * $gallerypagelimit >= $photocount) {
    $page = ceil($photocount / $gallerypagelimit);
  } else {
    $page = $_GET["page"];
  }
  $publicphotothumbsHTML = readPublicPhotoThumbsPage(2, $gallerypagelimit, $page);
    
  

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
  <p>
  <?php
    if ($page > 1) {
        echo '<span><a href="?page=' .($page - 1) .'"> Eelmine leht</a></span> |' ."\n";
    } else {
        echo '<span>Eelmine leht</span> |' ."\n";
    }
    if($page * $gallerypagelimit < $photocount) {
        echo '<span><a href="?page=' .($page + 1) .'"> Järgmine leht</a></span>' ."\n";
    } else {
        echo '<span>Järgmine leht</span>' ."\n";
    }
  ?>
  </p>


<?php
	echo $publicphotothumbsHTML;
?>

  
  
</body>
</html>