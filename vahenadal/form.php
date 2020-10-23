<?php

// var_dump($_POST);
  require("usesession.php");
  require("../../../config.php");
  $database = "if20_marilii_sa_1";
  
  // kui on idee sisestatud ja nuppu vajutatud, salvestame selle andmebaasi
  if(isset($_POST["ideasubmit"]) and !empty($_POST["ideainput"])) {
	  $conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
	  // valmistan ette SQL käsu
	  $stmt = $conn->prepare("INSERT INTO myideas(idea) VALUES(?)");
	  echo $conn->error;
	  // seome käsuga päris andmed
	  // i - integer, d - decimal, s - string
	  $stmt->bind_param("s", $_POST["ideainput"]);
	  $stmt->execute();
	  echo $stmt->error;
	  $stmt->close();
	  $conn->close();
  }
  
  // $username = "Marilii Saar";

  require("header.php");
  
  ?>
  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  
  <ul>
	<li><a href="page.php">Esilehele</a></li>
  <li><a href="oldlinks.php">Vanad Failid</a></li>
	<li><a href="?logout=1">Logi välja!</a></li>
  </ul>
  <hr />
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Sisesta oma pähe tulnud mõte!</label>
	<input type="text" name="ideainput" placeholder="Kirjuta siia mõte!">
	<input type="submit" name ="ideasubmit" value="Saada mõte ära!">
  </form>

  <hr />

  </body>
</html>