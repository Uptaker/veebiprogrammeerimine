<?php

// var_dump($_POST);
  require("usesession.php");
  require("../../../config.php");
  require("fnc_common.php");
  require("fnc_filminfo.php");
  $monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  
  $studionotice = "";
  $personnotice = "";
  $filmgenrenotice = "";
  $filmnotice = "";
  $positionnotice = "";

  $firstname = "";
  $lastname = "";
  $birthday = null;
  $birthmonth = null;
  $birthyear = null;
  $birthdate = null;

  $title = "";
  $productionyear = null;
  $duration = null;
  $filmdescription = "";

  $filmgenre = "";
  $genredescription = "";

  $studioname = "";
  $studioaddress = "";

  $position = "";
  $positiondescription = "";

  $studioerror = "";
  $firstnameerror = "";
  $lastnameerror = "";
  $birthdayerror = "";
  $birthmontherror = "";
  $birthyearerror = "";
  $birthdateerror = "";
  $filmgenreerror = "";
  $titleerror = "";
  $productionyearerror = "";
  $durationerror = "";
  $positionerror = "";

  // Kui klikiti isiku submit, siis ...
  if(isset($_POST["personsubmit"])) {
    if(empty($_POST["firstnameinput"])) {
        $firstnameerror = "Eesnimi on sisestamata!";
    }
    else {
      $firstname = test_input($_POST["firstnameinput"]);
    }

    if(empty($_POST["lastnameinput"])) {
      $lastnameerror = "Perekonnanimi on sisestamata!";
    }
    else {
      $lastname = test_input($_POST["lastnameinput"]);
    }
  
    if(!empty($_POST["birthdayinput"])) {
      $birthday = intval($_POST["birthdayinput"]);
    }
    else {
      $birthdayerror = "Palun vali sünnikuupäev!";
    }
    
    if(!empty($_POST["birthmonthinput"])) {
      $birthmonth = intval($_POST["birthmonthinput"]);
    }
    else {
      $birthmontherror = "Palun vali sünnikuu!";
    }
    
    if(!empty($_POST["birthyearinput"])) {
      $birthyear = intval($_POST["birthyearinput"]);
    }
    else {
      $birthyearerror = "Palun vali sünniaasta!";
    }
    
    // Kontrollime kuupäeva kehtivust
    if(!empty($birthday) and !empty($birthmonth) and !empty($birthyear)) {
      if(checkdate($birthmonth, $birthday, $birthyear)) {
        $tempdate = new DateTime($birthyear ."-" .$birthmonth ."-" .$birthday);
        $birthdate = $tempdate->format("Y-m-d");
      }
      else {
        $birthdateerror = "Kuupäev ei ole reaalne!";
      }
    }

    if(empty($firstnameerror) and empty($lastnameerror) and empty($birthdayerror) and empty($birthmontherror) and empty($birthyearerror) and empty($birthdateerror)) {
      $result = saveperson($firstname, $lastname, $birthdate);
      if($result == "OK") {
        $personnotice = "Isik salvestatud!";
        $firstname = "";
        $lastname = "";
        $birthday = null;
        $birthmonth = null;
        $birthyear = null;
        $birthdate = null;
      }
      else {
        $personnotice = $result;
      }    
    }
  }

  // Kui klikiti film submit, siis ...
  if(isset($_POST["filmsubmit"]))  {
    $filmdescription = test_input($_POST["filmdescriptioninput"]);
    if(empty($_POST["titleinput"])){
        $titleerror = "Filmi pealkiri sisestamata!";
    }
    else {
        $title = test_input($_POST["titleinput"]);
        
    }
    if(empty($_POST["productionyearinput"])){
      $productionyearerror = "Filmi tootmisaasta sisestamata!";
    }
    else {
        $productionyear = test_input($_POST["productionyearinput"]);
        
    }
    if(empty($_POST["durationinput"])){
      $durationerror = "Filmi kestus sisestamata!";
    }
    else {
        $duration = test_input($_POST["durationinput"]);
        
    }
    if(empty($titleerror) and empty($productionyearerror) and empty($durationerror)){
        $result = savefilm($title, $productionyear, $duration, $filmdescription);
        if($result == "OK") {
          $filmnotice = "Film salvestatud!";
          $title = "";
          $productionyear = null;
          $duration = null;
          $filmdescription = "";
        }
        else {
          $filmnotice = $result;
        }
    }
  }

  // Kui klikiti genre submit, siis ...
  if(isset($_POST["filmgenresubmit"]))  {
    $genredescription = test_input($_POST["genredescriptioninput"]);
    if(empty($_POST["filmgenreinput"])){
        $filmgenreerror = "Filmižanr sisestamata!";
    }
    else {
        $filmgenre = test_input($_POST["filmgenreinput"]);
    }
    if(empty($filmgenreerror)){
        $result = savefilmgenre($filmgenre, $genredescription);
        if($result == "OK") {
          $filmgenrenotice = "Žanr salvestatud!";
          $filmgenre = "";
          $genredescription = "";
        }
        else {
          $filmgenrenotice = $result;
        }
    }
  }

  // Kui klikiti stuudio submit, siis ...
  if(isset($_POST["studiosubmit"])) {
    $studioaddress = test_input($_POST["studioaddressinput"]);
    if(empty($_POST["studionameinput"])) {
      $studioerror = "Stuudio nimi on sisestamata!";
    }
    else {
      $studioname = test_input($_POST["studionameinput"]);
    }

    if(empty($studioerror)) {
      $result = savestudio($studioname, $studioaddress);
      if($result == "OK") {
        $studionotice = "Stuudio salvestatud!";
        $studioname = "";
        $studioaddress = "";
      }
      else {
        $studionotice = $result;
      }
    }
  }

  // Kui klikiti position submit, siis ...
  if(isset($_POST["positionsubmit"]))  {
    $positiondescription = test_input($_POST["positiondescriptioninput"]);
    if(empty($_POST["positioninput"])){
        $positionerror = "Ameti nimetus sisestamata!";
    }
    else {
        $position = test_input($_POST["positioninput"]);
    }
    if(empty($positionerror)){
        $result = saveposition($position, $positiondescription);
        if($result == "OK") {
          $positionnotice = "Ametikoht salvestatud!";
          $position = "";
          $positiondescription = "";
        }
        else {
          $positionnotice = $result;
        }
    }
  }
  

  require("header.php");
  
  ?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <ul>
	<li><a href="home.php">Esilehele</a></li>
	<li><a href="?logout=1">Logi välja!</a></li>
  </ul>
  <hr />
  <h2>Lisa isik</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="firstnameinput">Isiku eesnimi</label>
	<input type="text" name="firstnameinput" id="firstnameinput" placeholder="Eesnimi" value="<?php echo $firstname; ?>">
  <span><?php echo $firstnameerror; ?></span>
	<br /><br />
  <label for="lastnameinput">Isiku perkonnanimi</label>
	<input type="text" name="lastnameinput" id="lastnameinput" placeholder="Perekonnanimi" value="<?php echo $lastname; ?>">
  <span><?php echo $lastnameerror; ?></span>
	<br /><br />
  <label for="birthdayinput">Sünnipäev: </label>
  <?php
  echo '<select name="birthdayinput" id="birthdayinput">' ."\n";
  echo "\t\t" .'<option value="" selected disabled>päev</option>' ."\n";
  for ($i = 1; $i < 32; $i ++){
    echo "\t\t" .'<option value="' .$i .'"';
    if ($i == $birthday){
      echo " selected ";
    }
    echo ">" .$i ."</option> \n";
  }
  echo "\t </select> \n";
  ?>
  <label for="birthmonthinput">Sünnikuu: </label>
  <?php
    echo '<select name="birthmonthinput" id="birthmonthinput">' ."\n";
  echo "\t\t" .'<option value="" selected disabled>kuu</option>' ."\n";
  for ($i = 1; $i < 13; $i ++){
    echo "\t\t" .'<option value="' .$i .'"';
    if ($i == $birthmonth){
      echo " selected ";
    }
    echo ">" .$monthnameset[$i - 1] ."</option> \n";
  }
  echo "\t </select> \n";
  ?>
  <label for="birthyearinput">Sünniaasta: </label>
  <?php
    echo '<select name="birthyearinput" id="birthyearinput">' ."\n";
  echo "\t\t" .'<option value="" selected disabled>aasta</option>' ."\n";
  for ($i = date("Y"); $i >= date("Y") - 200; $i --){
    echo "\t\t" .'<option value="' .$i .'"';
    if ($i == $birthyear){
      echo " selected ";
    }
    echo ">" .$i ."</option> \n";
  }
  echo "\t </select> \n";
  ?>
  <br />
  <span><?php echo $birthdateerror ." " .$birthdayerror ." " .$birthmontherror ." " .$birthyearerror; ?></span>
	<br />
	<input type="submit" name="personsubmit" value="Salvesta isiku info">
  </form>
  <p><?php echo $personnotice; ?></p>

  <h2>Lisa film</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="titleinput">Filmi pealkiri</label>
    <input type="text" name="titleinput" id="titleinput" placeholder="Filmi pealkiri" value="<?php echo $title; ?>">
    <span><?php echo $titleerror; ?></span>
    <br />
    <label for="productionyearinput">Tootmisaasta</label>
    <input type="number" name="productionyearinput" id="productionyearinput" placeholder="<?php echo date("Y"); ?>" value="<?php echo $productionyear; ?>">
    <span><?php echo $productionyearerror; ?></span>
    <br />
    <label for="durationinput">Filmi kestus</label>
    <input type="number" name="durationinput" id="durationinput" placeholder="90" value="<?php echo $duration; ?>">
    <span><?php echo $durationerror; ?></span>
    <br />
    <label for="filmdescriptioninput">Filmi lühikirjeldus</label>
    <br />
    <textarea rows="5" cols="30" name="filmdescriptioninput" id="filmdescriptioninput" placeholder="Filmi lühitutvustus"><?php echo $filmdescription; ?></textarea>
    <br />
    <input type="submit" name="filmsubmit" value="Salvesta filmi info">
  </form>
  <p><?php echo $filmnotice; ?></p>

  <h2>Lisa žanr</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="filmgenreinput">Žanri nimi</label>
    <input type="text" name="filmgenreinput" id="filmgenreinput" placeholder="Žanri nimi" value="<?php echo $filmgenre; ?>">
    <span><?php echo $filmgenreerror; ?></span>
    <br />
    <label for="genredescriptioninput">Žanri lühitutvustus</label>
    <br />
    <textarea rows="5" cols="30" name="genredescriptioninput" id="genredescriptioninput" placeholder="Žanri lühitutvustus"><?php echo $genredescription; ?></textarea>
    <br />
    <input type="submit" name="filmgenresubmit" value="Salvesta filmižanri info">
  </form>
  <p><?php echo $filmgenrenotice; ?></p>

  <h2>Lisa filmistuudio</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="studionameinput">Stuudio nimi</label>
	<input type="text" name="studionameinput" id="studionameinput" placeholder="Stuudio nimi">
  <span><?php echo $studioerror; ?></span>
	<br />
	<label for="studioaddressinput">Stuudio aadress</label>
	<br />
	<textarea rows="5" cols="30" name="studioaddressinput" id="studioaddressinput" placeholder="Aadress: "><?php echo $studioaddress; ?></textarea>
	<br />
	<input type="submit" name="studiosubmit" value="Salvesta filmistuudio info">
  </form>
  <p><?php echo $studionotice; ?></p>

  <h2>Lisa ametikoht</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="positioninput">Ameti nimi</label>
    <input type="text" name="positioninput" id="positioninput" placeholder="Ameti nimi" value="<?php echo $filmgenre; ?>">
    <span><?php echo $positionerror; ?></span>
    <br />
    <label for="positiondescriptioninput">Ameti lühikirjeldus</label>
    <br />
    <textarea rows="5" cols="30" name="positiondescriptioninput" id="positiondescriptioninput" placeholder="Ameti lühitutvustus"><?php echo $positiondescription; ?></textarea>
    <br />
    <input type="submit" name="positionsubmit" value="Salvesta ameti info">
  </form>
  <p><?php echo $positionnotice; ?></p>
</body>
</html>