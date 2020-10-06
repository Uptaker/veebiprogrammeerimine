<?php
//loen lehele kõik olemasolevad mõtted
require("usesession.php");
require("../../../config.php");
$database = "if20_tammeoja_1";
$conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
$stmt = $conn->prepare("SELECT idea FROM myideas");
echo $conn->error;
//seome tulemuse muutujaga
$stmt->bind_result($ideafromdb);
$stmt->execute();
$ideahtml = "";
while($stmt->fetch()) {
    $ideahtml .= "<p>" .$ideafromdb ."</p>";
}
$stmt->close();
$conn->close();

require("header.php")
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
		<h3> Siin on kõik olemasolevad mõtted!<h3>
		<?php echo $ideahtml; ?>
	</body>
</html>