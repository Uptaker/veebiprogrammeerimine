<?php
  $database = "if20_marilii_sa_1";

// Salvesta isik
function saveperson($firstname, $lastname, $birthdate) {
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT person_id FROM person WHERE first_name = ? AND last_name = ? AND birth_date = ?");
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_param("sss", $firstname, $lastname, $birthdate);
	$stmt->bind_result($idfromdb);
	$stmt->execute();
	if($stmt->fetch()) {
		$notice = "Selline isik on juba olemas!";
	}
	else {
		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO person (first_name, last_name, birth_date) VALUES(?, ?, ?)");
		echo $conn->error; // <-- ainult enda jaoks õppimise ajal
		$stmt->bind_param("sss", $firstname, $lastname, $birthdate);
		if($stmt->execute()) {
			$notice = "OK";
		}
		else {
			$notice = "Isiku salvestamisel tekkis tehniline tõrge: " .$stmt->error;
		}
	}		
	$stmt->close();
	$conn->close();
	return $notice;
} // saveperson lõpeb

// Salvesta film
function savefilm($title, $productionyear, $duration, $filmdescription) {
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT movie_id FROM movie WHERE title = ? AND production_year = ? AND duration = ?");
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_param("sii", $title, $productionyear, $duration);
	$stmt->bind_result($idfromdb);
	$stmt->execute();
	if($stmt->fetch()) {
		$notice = "Selline film on juba olemas!";
	}
	else {
		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO movie (title, production_year, duration, description) VALUES(?, ?, ?, ?)");
		echo $conn->error; // <-- ainult enda jaoks õppimise ajal
		$stmt->bind_param("siis", $title, $productionyear, $duration, $filmdescription);
		if($stmt->execute()) {
			$notice = "OK";
		}
		else {
			$notice = "Filmi salvestamisel tekkis tehniline tõrge: " .$stmt->error;
		}
	}		
	$stmt->close();
	$conn->close();
	return $notice;
} // savefilm lõpeb

//Salvesta žanr
function savefilmgenre($filmgenreinput, $genredescriptioninput) {
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT genre_id FROM genre WHERE genre_name = ?");
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_param("s", $filmgenreinput);
	$stmt->bind_result($idfromdb);
	$stmt->execute();
	if($stmt->fetch()) {
		$notice = "Selline žanr on juba olemas!";
	}
	else {
		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO genre (genre_name, description) VALUES(?, ?)");
		echo $conn->error; // <-- ainult enda jaoks õppimise ajal
		$stmt->bind_param("ss", $filmgenreinput, $genredescriptioninput);
		if($stmt->execute()) {
			$notice = "OK";
		}
		else {
			$notice = "Žanri salvestamisel tekkis tehniline tõrge: " .$stmt->error;
		}
	}
	$stmt->close();
	$conn->close();
	return $notice;
} // savefilmgenre lõpeb

// Salvesta filmistuudio
	function savestudio($studionameinput, $studioaddressinput) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT company_id FROM production_company WHERE company_name = ?");
		echo $conn->error; // <-- ainult õppimise jaoks!
		$stmt->bind_param("s", $studionameinput);
		$stmt->bind_result($idfromdb);
		$stmt->execute();
		if($stmt->fetch()) {
			$notice = "Selline stuudio on juba olemas!";
		}
		else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO production_company (company_name, company_address) VALUES(?, ?)");
			echo $conn->error; // <-- ainult enda jaoks õppimise ajal
			$stmt->bind_param("ss", $studionameinput, $studioaddressinput);
			if($stmt->execute()) {
				$notice = "OK";
			}
			else {
				$notice = "Stuudio salvestamisel tekkis tehniline tõrge: " .$stmt->error;
			}
		}		
		$stmt->close();
		$conn->close();
		return $notice;
	} // savestudio lõpeb

	// Salvesta amet
	function saveposition($position, $positiondescription) {
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT position_id FROM position WHERE position_name = ?");
		echo $conn->error; // <-- ainult õppimise jaoks!
		$stmt->bind_param("s", $position);
		$stmt->bind_result($idfromdb);
		$stmt->execute();
		if($stmt->fetch()) {
			$notice = "Selline stuudio on juba olemas!";
		}
		else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO position (position_name, description) VALUES(?, ?)");
			echo $conn->error; // <-- ainult enda jaoks õppimise ajal
			$stmt->bind_param("ss", $position, $positiondescription);
			if($stmt->execute()) {
				$notice = "OK";
			}
			else {
				$notice = "Stuudio salvestamisel tekkis tehniline tõrge: " .$stmt->error;
			}
		}		
		$stmt->close();
		$conn->close();
		return $notice;
	} // saveposition lõpeb