<?php
//loen lehele kõik olemasolevad mõtted
require("../../../config_vp2020.php");
require("fnc_films.php");
require("usesession.php");
//$filmhtml = readfilms();
//readfilms();
require("header.php");

$sortby = 0;
$sortorder = 0;

$selectedfilm = "";
$selectedtype = "";

$filmnotice = "";
$typenotice = "";

function readCharacters($selected, $selectedfilm, $sortby, $sortorder) {
	$studionotice = "<p>Kahjuks filmitegelasi ei leitud!<p>\n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$SQLsentence = "SELECT role
					FROM person
					JOIN person_in_movie
					ON person.person_id = person_in_movie.person_id 
					JOIN movie 
					ON movie.movie_id = person_in_movie.movie_id
					WHERE person_in_movie.movie_id = ?";
	if($sortby == 0 and $sortorder == 0) {
		$stmt = $conn->prepare($SQLsentence); // fetching data UNSORTED
	}

	if($sortby == 2) { //ROLE
		if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY role DESC"); // fetching data SORTED BY role DESCENDING
		} else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY role");
		}
	}
	$conn->set_charset("utf8");
	echo $conn->error;
	$stmt->bind_param("i", $selectedfilm);
	$stmt->bind_result($rolefromdb);
	$stmt->execute();
	$lines = "";
	while($stmt->fetch()) {
		$lines .= "<tr> \n";
		$lines .= '<td>' .$rolefromdb .'</td>';
		$lines .= "</tr> \n";
	}
	if(!empty($lines)) {
		$notice = "<table> \n";
		$notice .= "<tr> \n";
		$notice .= '<th>Tegelane
		&nbsp;<a href="?sortby=2&sortorder=1">&uarr;</a>
		&nbsp;<a href="?sortby=2&sortorder=2">&darr;</a></th>' ."\n";
		$notice .= "</tr> \n";
		$notice .= $lines;
		$notice .= "</table> \n";
	} else {
		$notice = "Andmebaasis pole leitud ühtegi filmi tegelast!";
	}
	$stmt->close();
	$conn->close();
	return $notice;
}

function readActors($selected, $selectedfilm, $sortby, $sortorder) {
	$studionotice = "<p>Kahjuks filmitegelasi ei leitud!<p>\n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$SQLsentence = "SELECT first_name, last_name, role, birth_date
					FROM person 
					JOIN person_in_movie 
					ON person.person_id = person_in_movie.person_id 
					JOIN movie 
					ON movie.movie_id = person_in_movie.movie_id
					WHERE person_in_movie.movie_id = ?";
	if($sortby == 0 and $sortorder == 0) {
		$stmt = $conn->prepare($SQLsentence); // fetching data UNSORTED
	}

	if($sortby == 3) { //PERSON
		if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name DESC"); // fetching data SORTED BY person DESCENDING
		} else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY last_name");
		}
	}
	if($sortby == 2) { //ROLE
		if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY role DESC"); // fetching data SORTED BY role DESCENDING
		} else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY role");
		}
	}
	if($sortby == 4) { //BIRTH DATE
		if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY birth_date DESC"); // fetching data SORTED BY role DESCENDING
		} else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY birth_date");
		}
	}
	$conn->set_charset("utf8");
	echo $conn->error;
	$stmt->bind_param("i", $selectedfilm);
	$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $birthdatefromdb);
	$stmt->execute();
	$lines = "";
	while($stmt->fetch()) {
		$lines .= "<tr> \n";
		$lines .= "\t <td>".$firstnamefromdb ." " .$lastnamefromdb ."</td>";
		$lines .= '<td>' .$rolefromdb .'</td>';
		$lines .= '<td>' .$birthdatefromdb .'</td>';
		$lines .= "</tr> \n";
	}
	if(!empty($lines)) {
		$notice = "<table> \n";
		$notice .= "<tr> \n";
		$notice .= '<th>Isiku nimi
		&nbsp;<a href="?sortby=3&sortorder=1">&uarr;</a>
		&nbsp;<a href="?sortby=3&sortorder=2">&darr;</a></th>' ."\n";
		$notice .= '<th>Roll filmis
		&nbsp;<a href="?sortby=2&sortorder=1">&uarr;</a>
		&nbsp;<a href="?sortby=2&sortorder=2">&darr;</a></th>' ."\n";
		$notice .= '<th>Sünniaasta
		&nbsp;<a href="?sortby=4&sortorder=1">&uarr;</a>
		&nbsp;<a href="?sortby=4&sortorder=2">&darr;</a></th>' ."\n";
		$notice .= "</tr> \n";
		$notice .= $lines;
		$notice .= "</table> \n";
	} else {
		$notice = "Andmebaasis pole leitud filmi näitlejaid!";
	}
	$stmt->close();
	$conn->close();
	return $notice;
}

function readQuotes($selected, $selectedfilm, $sortby, $sortorder) {
	$studionotice = "<p>Kahjuks filmitegelasi ei leitud!<p>\n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$SQLsentence = "SELECT role, quote_text
					FROM person_in_movie
					JOIN quote
					ON quote.person_in_movie_id = person_in_movie.person_in_movie_id 
					WHERE person_in_movie.movie_id = ?";
	if($sortby == 0 and $sortorder == 0) {
		$stmt = $conn->prepare($SQLsentence); // fetching data UNSORTED
	}

	if($sortby == 3) { //ROLE
		if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY role DESC"); // fetching data SORTED BY person DESCENDING
		} else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY role");
		}
	}
	if($sortby == 2) { //QUOTE
		if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY quote_text DESC"); // fetching data SORTED BY role DESCENDING
		} else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY quote");
		}
	}

	$conn->set_charset("utf8");
	echo $conn->error;
	$stmt->bind_param("i", $selectedfilm);
	$stmt->bind_result($rolefromdb, $quotefromdb);
	$stmt->execute();
	$lines = "";
	while($stmt->fetch()) {
		$lines .= "<tr> \n";
		$lines .= "\t<td>" .$rolefromdb ."</td>";
		$lines .= "\t<td>" .$quotefromdb ."</td>";
		$lines .= "</tr> \n";
	}
	if(!empty($lines)) {
		$notice = "<table> \n";
		$notice .= "<tr> \n";
		$notice .= '<th>Roll filmis
		&nbsp;<a href="?sortby=3&sortorder=1">&uarr;</a>
		&nbsp;<a href="?sortby=3&sortorder=2">&darr;</a></th>' ."\n";
		$notice .= '<th>Tsitaat
		&nbsp;<a href="?sortby=2&sortorder=1">&uarr;</a>
		&nbsp;<a href="?sortby=2&sortorder=2">&darr;</a></th>' ."\n";

		$notice .= "</tr> \n";
		$notice .= $lines;
		$notice .= "</table> \n";
	} else {
		$notice = "Andmebaasis pole leitud ühtegi tsitaati!";
	}
	$stmt->close();
	$conn->close();
	return $notice;
}

function readInfo($selected, $selectedfilm, $sortby, $sortorder) {
	$studionotice = "<p>Kahjuks filmitegelasi ei leitud!<p>\n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$SQLsentence = "SELECT production_company.company_name, genre.genre_name, movie.title, movie.description, movie.duration, movie.production_year
					FROM movie
					JOIN movie_genre
					ON movie.movie_id = movie_genre.movie_id
					JOIN movie_by_production_company
					ON movie_by_production_company.movie_movie_id = movie.movie_id
					JOIN production_company
					ON movie_by_production_company.production_company_id = production_company.production_company_id
					JOIN genre
					ON movie_genre.genre_id = genre.genre_id
					WHERE movie.movie_id = ?";
	$stmt = $conn->prepare($SQLsentence);
	$conn->set_charset("utf8");
	echo $conn->error;
	$stmt->bind_param("i", $selectedfilm);
	$stmt->bind_result($studiofromdb, $genrefromdb, $titlefromdb, $descriptionfromdb, $durationfromdb, $yearfromdb);
	$stmt->execute();
	if($stmt->fetch()) {
		$notice = "\n<br><b> Film:</b> " .$titlefromdb ."\n";
		$notice .= "\n<br><b> Stuudio:</b> " .$studiofromdb ."\n";
		$notice .= "\n<br> <b>Žanr:</b> " .$genrefromdb ."\n";
		$notice .= "\n<br> <b>Kestus:</b> " .$durationfromdb ." minutit" ."\n";
		$notice .= "\n<br> <b>Valmimisaasta:</b> " .$yearfromdb ."\n";
		$notice .= "\n<br> <b>Lühitutvustus:</b> " .$descriptionfromdb ."\n";
	} else {
		$notice = "Andmebaasis pole filmi kohta infot!";
	}
	$stmt->close();
	$conn->close();
	return $notice;
}

if(isset($_POST["typesubmit"])) {
	if(!empty($_POST["filminput"])) {
		$selectedfilm = intval($_POST["filminput"]);
	} else {
		$typenotice .= " Valige film!";
		 }
	if(!empty($_POST["typeinput"])) {
		$selectedtype = $_POST["typeinput"];
	} else {
		$typenotice .= " Valige otsingu tüüp!";
		}
	if(!empty($selectedfilm) and !empty($selectedtype)) {
		if($selectedtype == "tegelased") { //tegelased
			$typenotice = readCharacters($selectedtype, $selectedfilm, $sortby, $sortorder);
		}
		if($selectedtype == "naitlejad") { //näitlejad
			$typenotice = readActors($selectedtype, $selectedfilm, $sortby, $sortorder);
		}
		if($selectedtype == "tsitaadid") { //tsitaadid
			$typenotice = readQuotes($selectedtype, $selectedfilm, $sortby, $sortorder);
		}
		if($selectedtype == "info") { //tsitaadid
			$typenotice = readInfo($selectedtype, $selectedfilm, $sortby, $sortorder);
		}
	 }
	
}

?>
<!DOCTYPE html>
<html lang="en">
	<body>
		<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise pilt">
		<p><a href="?logout=1"> Logi Välja</a></p>
		<ul>
			<li><a href="home.php">Avaleht</a></li>
		</ul>
		<h3></h3>
		<h2>Mis filmi kohta kuvata infot?</h2>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<?php
				echo readmovietoselect($selectedfilm);
			?>
			<select name="typeinput">
				<option value="" selected disabled>Vali tüüp</option>
				<option value="tegelased" <?php if($selectedtype == "tegelased" ) {echo " selected ";} ?>>Tegelased</option>
				<option value="naitlejad" <?php if($selectedtype == "naitlejad" ) {echo " selected ";} ?>>Näitlejad</option>
				<option value="tsitaadid" <?php if($selectedtype == "tsitaadid" ) {echo " selected ";} ?>>Tsitaadid</option>
				<option value="info" <?php if($selectedtype == "info" ) {echo " selected ";} ?>>Üldinfo</option>
			</select>
			<input type="submit" name="typesubmit" value="Otsi"><span><?php echo $typenotice; ?></span>
		</form>

	</body>
</html>