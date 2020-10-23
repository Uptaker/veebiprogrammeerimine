<?php
  require("usesession.php");
  
  require("header.php");
  
?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <ul>
	<li><a href="page.php">Esilehele</a></li>
  <li><a href="filminfo.php">Loe filmiinfot</a></li>
	<li><a href="addfilminfo.php">Filmiinfo lisamine</a></li>
	<li><a href="movieconnect.php">Seoste lisamine</a></li>
	<li><a href="oldlinks.php">Vanad Failid</a></li>
	<li><a href="userprofile.php">Minu kasutajaprofiil</a></li>
  <li><a href="?logout=1">Logi välja!</a></li>
  </ul>
  <hr />

</body>
</html>