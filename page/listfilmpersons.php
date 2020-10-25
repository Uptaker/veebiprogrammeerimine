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

$selectedfilm = "";
$selectedtype = "";

$filmnotice = "";
$typenotice = "";

function readCharacters($selected, $selectedfilm, $sortby, $sortorder) {
	$studionotice = "<p>Kahjuks filmitegelasi ei leitud!<p>\n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$SQLsentence = "SELECT first_name, last_name, role
					FROM person 
					JOIN person_in_movie 
					ON person.person_id = person_in_movie.person_id 
					JOIN movie 
					ON movie.movie_id = person_in_movie.movie_id";
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
	echo $conn->error;
	$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb);
	$stmt->execute();
	$lines = "";
	while($stmt->fetch()) {
		$lines .= "<tr> \n";
		$lines .= "\t <td>".$firstnamefromdb ." " .$lastnamefromdb ."</td>";
		$lines .= '<td>' .$rolefromdb .'</td>';
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
		$notice .= "</tr> \n";
		$notice .= $lines;
		$notice .= "</table> \n";
	}
	$stmt->close();
	$conn->close();
	return $notice;
}

if(isset($_POST["filmsubmit"])) {
	if(!empty($_POST["filminput"])) {
	$selectedfilm = intval($_POST["filminput"]);
 	} else {
		 $filmnotice = " Valige film!";
	 }
}

if(isset($_POST["typesubmit"])) {
	if(!empty($_POST["typeinput"])) {
		$selectedtype = intval($_POST["typeinput"]);
		if($selectedtype = 1) { //tegelased
			$typenotice = readCharacters($selectedtype, $selectedfilm, $sortby, $sortorder);
		}
		if($selectedtype = 2) { //näitlejad
			$typenotice = "Näitan kõik näitlejatega seotud infot..";
		}
		if($selectedtype = "3") { //tsitaadid
			$typenotice = "Näitan kõik tsitaatidega seotud infot..";
 	} else {
		$typenotice = " Valige otsingu tüüp!";
		}
	 }
	
}
var_dump($selectedtype, $selectedfilm);

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
			<input type="submit" name="filmsubmit" value="Otsi"><span><?php echo $filmnotice; ?></span>
		</form>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<select name="typeinput">
				<option value="" selected disabled>Vali tüüp</option>
				<option <?php if($selectedtype == 1 ) {echo "selected ";} ?>value="1">Tegelased</option>
				<option <?php if($selectedtype == 2 ) {echo "selected ";} ?>value="2">Näitlejad</option>
				<option <?php if($selectedtype == 3 ) {echo "selected ";} ?>value="3">Tsitaadid</option>
			</select>
			<input type="submit" name="typesubmit" value="Otsingu tüüp"><span><?php echo $typenotice; ?></span>
		</form>


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