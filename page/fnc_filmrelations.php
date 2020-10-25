<?php
$database = "if20_tammeoja_1";

//STUDIO RELATIONS ---------------------------------------------------------------------------------------------------

function readstudiotoselect($selectedstudio){
	$notice = "<p>Kahjuks stuudioid ei leitud!</p> \n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT production_company_id, company_name FROM production_company");
	$conn->set_charset("utf8");
	echo $conn->error;
	$stmt->bind_result($idfromdb, $companyfromdb);
	$stmt->execute();
	$studios = "";
	while($stmt->fetch()){
		$studios .= '<option value="' .$idfromdb .'"';
		if($idfromdb == $selectedstudio){
			$studios .= " selected";
		}
		$studios .= ">" .$companyfromdb ."</option> \n";
	}
	if(!empty($studios)){
		$notice = '<select name="studioinput">' ."\n";
		$notice .= '<option value="" selected disabled>Vali stuudio</option>' ."\n";
		$notice .= $studios;
		$notice .= "</select> \n";
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}

function storenewstudiorelation($selectedfilm, $selectedstudio){
	$notice = "";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT movie_by_production_company_id FROM movie_by_production_company WHERE movie_movie_id = ? AND production_company_id = ?");
	echo $conn->error;
	$stmt->bind_param("ii", $selectedfilm, $selectedstudio);
	$stmt->bind_result($idfromdb);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = "Selline seos on juba olemas!";
	} else {
		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO movie_by_production_company (movie_movie_id, production_company_id) VALUES(?,?)");
		echo $conn->error;
		$stmt->bind_param("ii", $selectedfilm, $selectedstudio);
		if($stmt->execute()){
			$notice = "Uus seos edukalt salvestatud!";
		} else {
			$notice = "Seose salvestamisel tekkis tehniline tõrge: " .$stmt->error;
		}
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}

//GENRE RELATIONS ------------------------------------------------------------------------------------------------------------------------------
function readmovietoselect($selected){
	$notice = "<p>Kahjuks filme ei leitud!</p> \n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT movie_id, title FROM movie");
	$conn->set_charset("utf8");
	echo $conn->error;
	$stmt->bind_result($idfromdb, $titlefromdb);
	$stmt->execute();
	$films = "";
	while($stmt->fetch()){
		$films .= '<option value="' .$idfromdb .'"';
		if(intval($idfromdb) == $selected){
			$films .=" selected";
		}
		$films .= ">" .$titlefromdb ."</option> \n";
	}
	if(!empty($films)){
		$notice = '<select name="filminput">' ."\n";
		$notice .= '<option value="" selected disabled>Vali film</option>' ."\n";
		$notice .= $films;
		$notice .= "</select> \n";
	}
	$stmt->close();
	$conn->close();
	return $notice;
}

function readgenretoselect($selected){
	$notice = "<p>Kahjuks žanre ei leitud!</p> \n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT genre_id, genre_name FROM genre");
	$conn->set_charset("utf8");
	echo $conn->error;
	$stmt->bind_result($idfromdb, $genrefromdb);
	$stmt->execute();
	$genres = "";
	while($stmt->fetch()){
		$genres .= '<option value="' .$idfromdb .'"';
		if(intval($idfromdb) == $selected){
			$genres .=" selected";
		}
		$genres .= ">" .$genrefromdb ."</option> \n";
	}
	if(!empty($genres)){
		$notice = '<select name="filmgenreinput">' ."\n";
		$notice .= '<option value="" selected disabled>Vali žanr</option>' ."\n";
		$notice .= $genres;
		$notice .= "</select> \n";
	}
	$stmt->close();
	$conn->close();
	return $notice;
}

function storenewgenrerelation($selectedfilm, $selectedgenre){
	$notice = "";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT movie_genre_id FROM movie_genre WHERE movie_id = ? AND genre_id = ?");
	echo $conn->error;
	$stmt->bind_param("ii", $selectedfilm, $selectedgenre);
	$stmt->bind_result($idfromdb);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = "Selline seos on juba olemas!";
	} else {
		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO movie_genre (movie_id, genre_id) VALUES(?,?)");
		echo $conn->error;
		$stmt->bind_param("ii", $selectedfilm, $selectedgenre);
		if($stmt->execute()){
			$notice = "Uus seos edukalt salvestatud!";
		} else {
			$notice = "Seose salvestamisel tekkis tehniline tõrge: " .$stmt->error;
		}
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}

// PERSON RELATIONS------------------------------------------------------------------------------------------------------------------------------------------------------------------------

function readpersontoselect($selectedperson){
	$notice = "<p>Kahjuks näitlejat ei leitud!</p> \n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT person_id, first_name, last_name FROM person");
	$conn->set_charset("utf8");
	echo $conn->error;
	$stmt->bind_result($idfromdb, $firstnamefromdb, $lastnamefromdb);
	$stmt->execute();
	$persons = "";
	while($stmt->fetch()){
		$persons .= '<option value="' .$idfromdb .'"';
		if($idfromdb == $selectedperson){
			$persons .= " selected";
		}
		$persons .= ">" .$firstnamefromdb ." " .$lastnamefromdb ."</option> \n";
	}
	if(!empty($persons)){
		$notice = '<select name="personinput">' ."\n";
		$notice .= '<option value="" selected disabled>Vali näitleja</option>' ."\n";
		$notice .= $persons;
		$notice .= "</select> \n";
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}
function readpositiontoselect($selectedposition){
	$notice = "<p>Kahjuks rolli tüüpi ei leitud!</p> \n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT position_id, position_name FROM position");
	$conn->set_charset("utf8");
	echo $conn->error;
	$stmt->bind_result($idfromdb, $positionfromdb);
	$stmt->execute();
	$positions = "";
	while($stmt->fetch()){
		$positions .= '<option value="' .$idfromdb .'"';
		if($idfromdb == $selectedposition){
			$positions .= " selected";
		}
		$positions .= ">" .$positionfromdb ."</option> \n";
	}
	if(!empty($positions)){
		$notice = '<select name="positioninput">' ."\n";
		$notice .= '<option value="" selected disabled>Vali rolli tüüp</option>' ."\n";
		$notice .= $positions;
		$notice .= "</select> \n";
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}

function storenewpersonrelation($selectedfilm, $selectedperson, $selectedposition){
	$notice = "";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT person_in_movie_id FROM person_in_movie WHERE movie_id = ? AND person_id = ?");
	echo $conn->error;
	$stmt->bind_param("ii", $selectedfilm, $selectedperson);
	$stmt->bind_result($idfromdb);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = "Selline seos on juba olemas!";
	} else {
		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO person_in_movie (movie_id, person_id, position_id) VALUES(?,?,?)");
		echo $conn->error;
		$stmt->bind_param("iii", $selectedfilm, $selectedperson, $selectedposition);
		if($stmt->execute()){
			$notice = "Uus seos edukalt salvestatud!";
		} else {
			$notice = "Seose salvestamisel tekkis tehniline tõrge: " .$stmt->error;
		}
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}
// ADD QUOTE FUNCTION --------------------------------------

function listRoles($selectedrole, $selectedfilm) {
	$rolenotice = "";
	$notice = "";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT person_in_movie_id, role FROM person_in_movie WHERE movie_id = ?");
	$conn->set_charset("utf8");
	echo $conn->error;
	$stmt->bind_param("i", $selectedfilm);
	$stmt->bind_result($idfromdb, $rolefromdb);
	$stmt->execute();
	$roles = "";
	while($stmt->fetch()){
		$roles .= '<option value="' .$idfromdb .'"';
		if($idfromdb == $selectedrole){
			$roles .=" selected";
		}
		$roles .= ">" .$rolefromdb ."</option> \n";
	}
	if(!empty($roles)){
		$rolenotice = '<select name="filmroleinput">' ."\n";
		$rolenotice .= '<option value="" selected disabled>Vali roll</option>' ."\n";
		$rolenotice .= $roles;
		$rolenotice .= "</select> \n";
	}
	$stmt->close();
	$conn->close();
	return array($rolenotice, $rolefromdb);
}

function readRole($selectedrole) {
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT movie_id FROM person_in_movie WHERE person_in_movie_id = ?");
	$conn->set_charset("utf8");
	echo $conn->error;
	$stmt->bind_param("i", $selectedrole);
	$stmt->bind_result($idfromdb);
	$stmt->execute();
	$stmt->fetch();
	$stmt->close();
	$stmt = $conn->prepare("SELECT role FROM person_in_movie WHERE movie_id = ? AND person_in_movie_id = ?");
	$stmt->bind_param("ii", $idfromdb, $selectedrole);
	$stmt->bind_result($rolefromdb);
	$stmt->execute();
	$stmt->fetch();
	$stmt->close();
	$conn->close();
	return array($rolefromdb, $idfromdb);
}

function storeQuote($quotetext, $selectedrole, $role) {
	$notice = "AAAA"; // just as a test xd
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT quote_id FROM person_in_movie
							JOIN quote ON person_in_movie.person_in_movie_id=quote.person_in_movie_id
							WHERE role = ? AND quote_text = ? AND person_in_movie.person_in_movie_id = ?"); //Why won't this fetch???
	echo $conn->error;
	$stmt->bind_param("ssi", $role, $quotetext, $selectedrole);
	$stmt->bind_result($idfromdb);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = " Selline tsitaat on juba olemas!";
	} else {
		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO quote (quote_text, person_in_movie_id) VALUES(?,?)");
		$stmt->bind_param("si", $quotetext, $selectedrole);
		if($stmt->execute()){
			$notice = " Uus tsitaat edukalt salvestatud!";
		} else {
			$notice = " Seose salvestamisel tekkis tehniline tõrge: " .$stmt->error;
		}
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}

// LIST FUNCTIONS -----------------------------------------------------------------------------------------------------------------------------------------------------------------------

function readpersonsinfilm(){
	$notice = "<p>Kahjuks filmitegelasi seoses filmidega ei leitud!</p> \n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT first_name, last_name, role, title FROM person JOIN person_in_movie ON person.person_id = person_in_movie.person_id JOIN movie ON movie.movie_id = person_in_movie.movie_id");
	echo $conn->error;
	$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb);
	$stmt->execute();
	$lines = "";
	while($stmt->fetch()){
		$lines.= "<p>" .$firstnamefromdb ." " .$lastnamefromdb;
		if(!empty($rolefromdb)){
			$lines .= " on tegelane " .$rolefromdb;
		}
		$lines .= ' filmis "' .$titlefromdb .'".' ."</p> \n";
	}
	if(!empty($lines)){
		$notice = $lines;
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}