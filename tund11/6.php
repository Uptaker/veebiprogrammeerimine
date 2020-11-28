<?php
//loen lehele kõik olemasolevad mõtted
require("usesession.php");
require("../../../config.php");


$titlefromdb = "";
$genreinput = "";
$notice = "";

$selectedstudio = "";
$studionotice - "";

$database = "if20_tammeoja_1";
function loadmovie() {
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT title, movie_id FROM movie"); // fetching data
    echo $conn->error;
    $stmt->bind_result($titlefromdb, $filmValue);
    $stmt->execute();
    $filmhtml = "";
    while($stmt->fetch()) {
        $filmhtml .= '<option value="' .$filmValue .'" >' .$titlefromdb .'</option>\n';
    }
    $stmt->close();
    $conn->close();
    return $filmhtml;
}

function loadstudio() {
    $filmnotice = "<p>Kahjuks stuudioid ei leitud!<p>\n";
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT production_company_id, company_name FROM production_company"); // fetching data
    echo $conn->error();
    $stmt->bind_result($studioidfromdb, $companyfromdb);
    $stmt->close();
    $conn->close();
    $studiohtml = "";
    while($stmt->fetch()) {
        $filmhtml .= '<option value="' .$studioidfromdb;
        if($idfromdb == $selectedstudio) {
            $studios .= " selected";
        }
    }
        $studios .= '" >' .$companyfromdb .'</option>\n';
    if(!empty($studios)) {
        $notice = '<select name="studioinput" id="studioinput"> ."\n"';
        $notice .= "<option value="" selected disabled>Vali filmistuudio/tootja</option>";
        $notice .= $studios;
        $notice .= "</select> \n"
    }
}

function loadgenre() {
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT genre_name, genre_id FROM genre"); // fetching data
    echo $conn->error;
    $stmt->bind_result($genrefromdb, $genreValue);
    $stmt->execute();
    $genrehtml = "";
    while($stmt->fetch()) {
        $genrehtml .= '<option value="' .$genreValue .'" >' .$genrefromdb .'</option>\n';
    }
    $stmt->close();
    $conn->close();
    return $genrehtml;
}

//kontrolltingimused teen hiljem, kui see kood lõpuks töötab
if(isset($_POST["genresubmit"])) {
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT movie_genre_id FROM movie_genre WHERE movie_id = ? AND genre_id = ?");
	echo $conn->error;
	$stmt->bind_param("ii", $_POST["filminput"], $_POST["genreinput"]);
	$stmt->bind_result($idfromdb);
	$stmt->execute();
    if ($stmt->fetch()) {
        $notice .= "Seos on juba olemas! ";
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO movie_genre (genre_id, movie_id) VALUES (?,?)"); // updating data
        echo $conn->error;
        $stmt->bind_param("ii", $_POST["genreinput"], $_POST["filminput"]);
        $stmt->execute();
        $notice .= "Žanr salvestatud! ";

    }
    $notice .= $_POST["filminput"] ."on nüüd" .$_POST["genreinput"];
    $stmt->close();
    $conn->close();
}

if(isset($_POST["studiosubmit"])) {

}


require("header.php");
?>
<!DOCTYPE html>
<html lang="en">
	<body>
		<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise pilt">
		<p><a href="?logout=1"> Logi Välja</a></p>
		<ul>
			<li><a href="home.php">Avaleht</a></li>
			<li><a href="idearesults.php">Mõtted</a></li>
			<li><a href="listfilms.php">Filmid</a></li>
		</ul>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="filminput">Film: </label> <br>
            <select name="filminput" id="filminput">
                <option value="" selected disabled>Vali film</option>
                <?php echo loadmovie(); ?>
            </select>
            
            <br>
            <label for="genreinput">Žanr:</label><br>
            <select name="genreinput" id="genreinput">
                <option value="" selected disabled>Vali zanr</option>
                <?php echo loadgenre(); ?>
            </select>
            <br>
            <input type="submit" name="genresubmit" id="genresubmit" value="Salvesta filmi žanri">
            <br> <?php echo $notice; ?>
        </form>

        <h2> Filmistuudio: </h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

            <br>
            <label for="studioinput">Žanr:</label><br>
            <select name="studioinput" id="studioinput">
                <option value="" selected disabled>Vali filmistuudio/tootja</option>
                <?php echo loadstudio(); ?>
            </select>
            <br>
            <input type="submit" name="studiosubmit" id="gstudiosubmit" value="Salvesta filmi tootja">
            <br> <?php echo $notice; ?>
        </form>

	</body>
</html>