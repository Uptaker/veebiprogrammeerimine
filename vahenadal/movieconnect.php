<?php

// var_dump($_POST);
  require("usesession.php");
  require("../../../config.php");
  require("fnc_common.php");
  require("fnc_filmrelations.php");

  $personnotice = "";
  $studionotice = "";
  $genrenotice = "";
  
  $selectedperson = "";
  $selectedfilm = "";
  $selectedposition = "";
  $selectedstudio = "";
  $selectedgenre = "";
  $insertedrole = "";
  
  if(isset($_POST["genrefilmsubmit"])) {
	  if(!empty($_POST["filminput"])){
		$selectedfilm = intval($_POST["filminput"]);
	} else {
		$genrenotice .= " Vali film!";
	}
	if(!empty($_POST["genreinput"])){
		$selectedgenre = intval($_POST["genreinput"]);
	} else {
		$genrenotice .= " Vali žanr!";
	}
	if(!empty($selectedfilm) and !empty($selectedgenre)){
		$genrenotice = storenewgenrerelation($selectedfilm, $selectedgenre);
	}
  }
  
  if(isset($_POST["studiofilmsubmit"])) {
	  if(!empty($_POST["filminput"])){
		$selectedfilm = intval($_POST["filminput"]);
	} else {
		$studionotice .= " Vali film!";
	}
	if(!empty($_POST["studioinput"])){
		$selectedstudio = intval($_POST["studioinput"]);
	} else {
		$studionotice .= " Vali stuudio!";
	}
	if(!empty($selectedfilm) and !empty($selectedstudio)){
		$studionotice = storenewstudiorelation($selectedfilm, $selectedstudio);
	}
  }
  
  if(isset($_POST["personfilmsubmit"])) {
	if(!empty($_POST["filminput"])){
		$selectedfilm = intval($_POST["filminput"]);
	} else {
		$personnotice .= " Vali film!";
	}
	if(!empty($_POST["personinput"])){
		$selectedperson = intval($_POST["personinput"]);
	} else {
		$personnotice .= " Vali isik!";
	}
	if(!empty($_POST["positioninput"])){
		$selectedposition = intval($_POST["positioninput"]);
		if ($selectedposition == 1) {
			if(!empty($_POST["roleinput"])) {
				$insertedrole = test_input($_POST["roleinput"]);
			}
			else {
				$personnotice .= " Sisesta roll!";
			}
		} else {
			$selectedposition = intval($_POST["positioninput"]);
			$insertedrole = test_input($_POST["roleinput"]);
			$personnotice .= " Režissööril ei ole rolli!";
		}
	} else {
		$personnotice .= " Vali amet!";
	}
    if(!empty($selectedperson) and !empty($selectedfilm) and !empty($selectedposition)) {
		if ($selectedposition == 1 and !empty($insertedrole)) {
			$personnotice = storenewpersonrelation($selectedperson, $selectedfilm, $selectedposition, $insertedrole);
		}
		else if($selectedposition != 1 and empty($insertedrole)) {
			$personnotice = storenewpersonrelation($selectedperson, $selectedfilm, $selectedposition, $insertedrole);
		}
    }
  }
  
  $personselect = readpersontoselect($selectedperson);
  $filmselect = readmovietoselect($selectedfilm);
  $genreselect = readgenretoselect($selectedgenre);
  $positionselect = readpositiontoselect($selectedposition);
  $filmstudioselect = readstudiotoselect($selectedstudio);

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
  <h2>Määrame filmi žanri</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<?php
		echo $filmselect;
		echo $genreselect;
	?>  
  <input type="submit" name="genrefilmsubmit" value="Salvesta seos žanriga"><span><?php echo $genrenotice; ?></span>
  </form>
  
  <h2>Määrame filmi stuudio / tootja</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<?php
		echo $filmselect;
		echo $filmstudioselect;
	?>  
  <input type="submit" name="studiofilmsubmit" value="Salvesta seos stuudioga"><span><?php echo $studionotice; ?></span>
  </form>
  
  <h2>Määrame filmile isiku</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="movieinput">Film: </label>
      <?php echo $filmselect; ?>
	<label for="personinput">Isik: </label>
      <?php echo $personselect; ?>
    <label for="positioninput">Amet: </label>
      <?php echo $positionselect; ?>
	<label for="roleinput">Roll: </label>
    <input type="text" name="roleinput" id="roleinput" value="<?php echo $insertedrole; ?>">
	<input type="submit" name="personfilmsubmit" value="Salvesta seos isikuga"><span><?php echo $personnotice; ?></span>
  </form>
</body>
</html>