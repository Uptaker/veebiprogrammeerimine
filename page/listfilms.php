<?php
//loen lehele kõik olemasolevad mõtted
require("../../../config.php");
require("fnc_films.php");
//$filmhtml = readfilms();
//readfilms();
require("header.php");
?>
<!DOCTYPE html>
<html lang="en">
	<body>
		<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise pilt">
		<ul>
			<li><a href="home.php">Avaleht</a></li>
		</ul>
		<h3> FILMIIIID</h3>
		<?php echo readfilms(); ?>
	</body>
</html>