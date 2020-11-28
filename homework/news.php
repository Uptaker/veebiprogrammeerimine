<?php
  require("usesession.php");

  require("../../../config_vp2020.php");
  //require("fnc_photo.php");
  require("fnc_common.php");
  require("fnc_news.php");
  //javascript and css
  $tolink = '<link rel="stylesheet" type="text/css" href="style/news.css">' ."\n";
  $tolink .= '<link rel="stylesheet" type="text/css" href="style/opennews.css">' ."\n";
  $tolink .= '<script src="javascript/newspopup.js" defer></script>' ."\n";

  $newspagelimit = 10;
  $page = 1;
  $newsCount = countNews();
  $photodir = "../photoupload_news/";
  $photodir_thumb = "../photoupload_news/thumbnails/";

  if(!isset($_GET["page"]) or $_GET["page"] < 1){
	  $page = 1;
  } elseif(round($_GET["page"] - 1) * $newspagelimit >= $newsCount){
	  $page = ceil($newsCount / $newspagelimit);
  } else {
	  $page = $_GET["page"];
  }

  $darkbg = adjustBrightness($_SESSION["userbgcolor"], -25);
  $lightbg = adjustBrightness($_SESSION["userbgcolor"], 20);

  // pulling news content from database
  $readNewsPage = readNewsPage($newspagelimit, $page);
  $newshtml = $readNewsPage[0];





  require("header.php");
?>

<style><?php
    echo ".rightcontent1 { \n";
        echo "\t\t background-color: " .$darkbg .";\n";
      echo "} \n"; 
    echo ".rightcontent2 { \n";
      echo "\t\t background-color: " .$lightbg .";\n";
    echo "} \n";
    ?>
</style>


  <h1>Uudised</h1>
  </hr>
  
  <ul>
    <li><a href="?logout=1">Logi välja</a>!</li>
    <li><a href="home.php">Avaleht</a></li>
  </ul>
  
  <hr>

  <p>
  <?php
    echo '<div class="pagechange">' ."\n";
		if($page > 1){
			echo '<span><a href="?page=' .($page - 1) .'">Eelmine leht</a></span> |' ."\n";
		} else {
			echo '<span>Eelmine leht</span> |' ."\n";
		}
		if($page * $newspagelimit < $newsCount){
			echo '<span><a href="?page=' .($page + 1) .'">Järgmine leht</a></span>' ."\n";
		} else {
      echo '<span>Järgmine leht</span>' ."\n";
    }
    echo '</div>' ."\n";
	?>
  </p><hr>

  <!-- Täna postitati # uudist! -->
  <?php
   echo newsToday();
  ?>

  <!--Modaalaken uudiste jaoks-->
  <div id="modalarea" class="modalarea">
    <!--sulgemisnupp-->
    <span id="modalclose" class="modalclose">&times;</span>
    <!--pildikoht-->
    <div class="modalhorizontal">
      <div class="modalvertical">
        <h2><b id="modalcaption"></b></h2><br><br>
        <img id="modalimg" src="../img/empty.png" alt="uudis">
        <br><br>
        <p class ="modalcontentx" id="modalcontent"></p>
        <br>
        <i id="modalauthor"></i>
        <div class="dates">
          <p id="modalnewsadded"></p>
          <p id="modalnewsexpired"></p>
        </div>
      </div>
    </div>
  </div>

 <?php
  echo $newshtml;
  ?>
  
</body>
</html>