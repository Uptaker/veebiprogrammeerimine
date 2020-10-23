<?php

// var_dump($_POST);
  require("usesession.php");
  require("../../../config.php");
  require("fnc_films.php");
  
  // $filmhtml = readfilms();
  // $username = "Marilii Saar";

  require("header.php");
  
  ?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <ul>
	<li><a href="home.php">Esilehele</a></li>
  <li><a href="oldlinks.php">Vanad Failid</a></li>
	<li><a href="?logout=1">Logi välja!</a></li>
  </ul>
  <hr />
  <?php echo readfilms(); ?>

</body>
</html>