<?php
//loen lehele kõik olemasolevad mõtted
require("../../../config.php");
require("fnc_films.php");
require("usesession.php");
//$filmhtml = readfilms();
//readfilms();
require("header.php");

$sortby = 0;
$sortorder = 0;

function readpersonsinfilm($sortby, $sortorder) {
	$studionotice = "<p>Kahjuks filmitegelasi ei leitud!<p>\n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$SQLsentence = "SELECT first_name, last_name, role, title 
					FROM person 
					JOIN person_in_movie 
					ON person.person_id = person_in_movie.person_id 
					JOIN movie 
					ON movie.movie_id = person_in_movie.movie_id";
	if($sortby == 0 and $sortorder == 0) {
		$stmt = $conn->prepare($SQLsentence); // fetching data UNSORTED
	}

	if($sortby == 4) { //TITLE
		if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY title DESC"); // fetching data SORTED BY title DESCENDING
		} else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY title");
		}
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


	echo $conn->error;
	$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb);
	$stmt->execute();
	$lines = "";
	while($stmt->fetch()) {
		$lines .= "<tr> \n";
		$lines .= "\t <td>".$firstnamefromdb ." " .$lastnamefromdb ."</td>";
		$lines .= '<td>' .$rolefromdb .'</td>';
		$lines .= '<td>' .$titlefromdb ."</td>\n";
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
		$notice .= '<th>Film 
		&nbsp;<a href="?sortby=4&sortorder=1">&uarr;</a>
		&nbsp;<a href="?sortby=4&sortorder=2">&darr;</a> </th>' ."\n";
		$notice .= "</tr> \n";
		$notice .= $lines;
		$notice .= "</table> \n";
	}
	$stmt->close();
	$conn->close();
	return $notice;
}

//vana
function old_readpersonsinfilm() {
	$studionotice = "<p>Kahjuks filmitegelasi ei leitud!<p>\n";
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT first_name, last_name, role, title 
	FROM person 
	JOIN person_in_movie 
	ON person.person_id = person_in_movie.person_id 
	JOIN movie 
	ON movie.movie_id = person_in_movie.movie_id"); // fetching data
	echo $conn->error;
	$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb);
	$stmt->execute();
	$lines = "";
	while($stmt->fetch()) {
		$lines .= '<p>' .$firstnamefromdb .' ' .$lastnamefromdb;
		if(!empty($rolefromdb)) {
			$lines .= ' tegelane ' .$rolefromdb;
		}
		$lines .= ' on filmis "' .$titlefromdb .'".' ."\n";
	}
	if(!empty($lines)) {
		$notice = $lines;
	}
	$stmt->close();
	$conn->close();
	return $notice;
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
		<h3> FILMI TEGELASEEEED</h3>
		<?php 
		if(isset($_GET["sortby"]) and isset($_GET["sortorder"])) {
			if($_GET["sortby"] >=1 and $_GET["sortby"] <= 4) {
				$sortby = $_GET["sortby"];
			}
			if($_GET["sortorder"] == 1 or $_GET["sortorder"] == 2) {
				$sortorder = $_GET["sortorder"];
			}
		}
		echo readpersonsinfilm($sortby, $sortorder); ?>
	</body>
</html>