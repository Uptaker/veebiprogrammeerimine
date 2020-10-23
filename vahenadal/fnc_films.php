<?php
  $database = "if20_marilii_sa_1";
//   $database = "if20_inga_filmibaas_utf8";
  
  // Funktsioon, mis loeb kõikide filmide info
  function readfilms() {
	  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	  // $stmt = $conn->prepare("SELECT pealkiri, aasta, kestus, zanr, tootja, lavastaja FROM film");
	  $stmt = $conn->prepare("SELECT * FROM film");
	  echo $conn->error; // <-- ainult enda jaoks õppimise ajal
	  // seome tulemuse muutujaga
	  $stmt->bind_result($titlefromdb, $yearfromdb, $durationfromdb, $genrefromdb, $studiofromdb, $directorfromdb);
	  $stmt->execute();
	  $filmhtml = "\t<ol>\n";
	  while($stmt->fetch()) {
		  $filmhtml .= 
		  "\t\t<li>" . $titlefromdb . "
			<ul>
				<li>Valmimisaasta: " . $yearfromdb . "</li>
				<li>Kestus minutites: " . $durationfromdb . " minutit</li>
				<li>Žanr(id): " . $genrefromdb . "</li>
				<li>Tootja / stuudio: " . $studiofromdb . "</li>
				<li>Lavastaja: " . $directorfromdb . "</li>
			</ul>
		</li>\n";
	  }
	  $filmhtml .= "\t</ol>\n";
	  $stmt->close();
	  $conn->close();
	  return $filmhtml;
  } //readfilms lõpeb
  
  function savefilm($titleinput, $yearinput, $durationinput, $genreinput, $studioinput, $directorinput) {
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("INSERT INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES(?, ?, ?, ?, ?, ?)");
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_param("siisss", $titleinput, $yearinput, $durationinput, $genreinput, $studioinput, $directorinput);
	$stmt->execute();
	$stmt->close();
	$conn->close();
  } // savefilm lõpeb