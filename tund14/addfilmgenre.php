<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
  require("usesession.php");
  //loeme andmebaasi login ifo muutujad
  require("../../../config_vp2020.php");
  require("fnc_filmrelations.php");
  
  $genrenotice = "";
  $selectedfilm = "";
  $selectedgenre = "";
  $studionotice = "";
  $selectedstudio = "";
  $selectedperson = "";
  $personnotice = "";
  $selectedposition = "";
  $positionnotice = "";
  $quotenotice = "";
  $selectedrole = "";
  $rolesearchnotice = "";
  $rolefilm = "";
  $roles = "";
  $quotes = "";
  $quoteinput = "";
  $roletitle = "";

  $filmselecthtml = readmovietoselect($selectedfilm);
  $filmgenreselecthtml = readgenretoselect($selectedgenre);
  $filmstudioselecthtml = readstudiotoselect($selectedstudio);
  $filmpersonselecthtml = readpersontoselect($selectedperson);
  $filmpositionselecthtml = readpositiontoselect($selectedposition);

  
  if(isset($_POST["filmstudiorelationsubmit"])){
	  if(!empty($_POST["filminput"])){
		$selectedfilm = intval($_POST["filminput"]);
	} else {
		$studionotice = " Vali film!";
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
  
  if(isset($_POST["filmgenrerelationsubmit"])){
	//$selectedfilm = $_POST["filminput"];
	if(!empty($_POST["filminput"])){
		$selectedfilm = intval($_POST["filminput"]);
	} else {
		$genrenotice = " Vali film!";
	}
	if(!empty($_POST["filmgenreinput"])){
		$selectedgenre = intval($_POST["filmgenreinput"]);
	} else {
		$genrenotice .= " Vali žanr!";
	}
	if(!empty($selectedfilm) and !empty($selectedgenre)){
		$genrenotice = storenewgenrerelation($selectedfilm, $selectedgenre);
	}
  }

  if(isset($_POST["filmpersonrelationsubmit"])){
	//$selectedfilm = $_POST["filminput"];
	if(!empty($_POST["filminput"])){
		$selectedfilm = intval($_POST["filminput"]);
	} else {
		$genrenotice = " Vali film!";
	}
	if(!empty($_POST["personinput"])){
		$selectedperson = intval($_POST["personinput"]);
	} else {
		$personnotice .= " Vali näitleja!";
	}
	if(!empty($_POST["positioninput"])){
		$selectedposition = intval($_POST["positioninput"]);
	} else {
		$personnotice .= " Vali rolli tüüp!";
	}
	if(!empty($selectedfilm) and !empty($selectedperson) and !empty($selectedposition)){
		$personnotice = storenewpersonrelation($selectedfilm, $selectedperson, $selectedposition);
	}
  }


if(isset($_POST["searchrolesubmit"])){
	$roles = listRoles($selectedrole, $_POST["filminput"]);
 	if(!empty($_POST["filminput"])){
		  $selectedfilm = intval($_POST["filminput"]);
		  if(empty($roles)) {
			$rolesearchnotice = " Filmil pole määratud rolle!";
		  }
  	} else {
  		$rolesearchnotice = " Vali film!";
	}
	if(empty($rolesearchnotice)) {
	$quotes = '<br><label for="quoteinput">Valige roll ja sisesta tsitaat:</label><br>' .$roles[0]
	.'<br><textarea rows="5" cols="80" name="quoteinput" id="quoteinput" placeholder="Tsitaat.."></textarea><br>
	<input type="submit" name="quotesubmit" value="Salvesta tsitaat"><span>' .$quotenotice .'</span';
	}
}
if(isset($_POST["quotesubmit"])) {
	// if(!empty($_POST["quoteinput"])){
		$quoteinput = $_POST["quoteinput"];
	// } else {
	// 	$quotenotice = " Tsitaat on tühi!"; 
	// }
	// if(!empty($_POST["filmroleinput"])){
		$selectedrole = intval($_POST["filmroleinput"]);
	// } else {
	// 	$quotenotice = " Vali roll!";
	// }
	//if(!empty($quoteinput) and !empty($selectedrole)){
	$roletitle = readRole($selectedrole);
	if(empty($quotenotice)) {
		$rolesearchnotice = storeQuote($quoteinput, $selectedrole, $roletitle[0]);
	} else {
		$rolesearchnotice = "Miski on pekkis";
	}
}

  require("header.php");
?>

  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?> programmeerib veebi</h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>Leht on loodud veebiprogrammeerimise kursusel <a href="http://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
    
  <ul>
    <li><a href="home.php">Avalehele</a></li>
	<li><a href="?logout=1">Logi välja</a>!</li>
  </ul>
  <h2>Määrame filmile stuudio/tootja</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php
		echo readmovietoselect($selectedfilm);
		echo $filmstudioselecthtml;
	?>
    <input type="submit" name="filmstudiorelationsubmit" value="Salvesta seos stuudioga"><span><?php echo $studionotice; ?></span>
  </form>
    
  <hr>
  <h2>Määrame filmile žanri</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php
		echo $filmselecthtml;
		echo $filmgenreselecthtml;
	?>
	
	<input type="submit" name="filmgenrerelationsubmit" value="Salvesta seos žanriga"><span><?php echo $genrenotice; ?></span>
  </form>

  <hr>
  <h2>Määrame filmile näitleja</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php
		echo $filmselecthtml;
		echo $filmpersonselecthtml;
		echo $filmpositionselecthtml;
	?>
	<input type="submit" name="filmpersonrelationsubmit" value="Salvesta seos näitlejaga"><span><?php echo $personnotice; ?></span>
  </form>
  
  <hr>
  <h2>Määrame tsitaat</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php
		echo $filmselecthtml;
	?>
	<input type="submit" name="searchrolesubmit" value="Otsi filmi rolle"><span><?php echo $rolesearchnotice; ?></span><br>
	<?php echo $quotes; ?>
  </form>
</body>
</html>