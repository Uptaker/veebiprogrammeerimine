<?php
	//var_dump ($_POST);
	require("usesession.php");
	require("../../../config_vp2020.php");
	require("fnc_user.php");
	$database = "if20_tammeoja_1";
	//kui on idee sisestatud ja nuppu vajutatud, salvestame selle andmebaasi.
	if(isset($_POST["ideasubmit"]) and !empty($_POST["ideasubmit"])) {
		$conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
		//valmistan ette sql käsu
		$stmt = $conn->prepare("INSERT INTO myideas (idea) VALUES(?)");
		echo $conn->error;
		//seome käsuga päris andmed
		//i - integer, d- decimal, s - string
		$stmt->bind_param("s", $_POST["ideainput"]);
		$stmt->execute();
		$stmt->close();
		$conn->close();
	}

	//working with cookies
	//setcokie - must be before <html> elementi
	//küpsise nimi, väärtus, aegumistähtaeg, failitee(domeeni piires), domeen
	setcookie("vpvisitorname", $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"], time() + (86400 * 8), "/~tammeoja/", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]), true);
	$lastvisitor = null;
	if (isset($_COOKIE["vpvisitorname"])) {
		$lastvisitor = "<p>Viimati külastas lehte: " .$_COOKIE["vpvisitorname"] .".<p> \n";
	} else {
		$lastvisitor = "<p>Küpsiseid ei leitud, viimane külastaja pole teada.<p>";
	}

	//küpsise kustutamine
	//kustutamiseks tuleb sama küpsis kirjutada minevikus aegumistähtajaga, näiteks time() - 3600
	
	//annan ette lubatud pildivormingute loendi
	$picfiletypes = ["image/jpeg", "image/png"];

	//loeme piltide kataloogi sisu ja näitame pilte
	//$allfiles = scandir("../vp_pics/");
	$allfiles = array_slice(scandir("../vp_pics/"), 2);
	// var_dump($allfiles);
	//$picfiles = array_slice($allfiles, 2);
	$picfiles = [];
	foreach($allfiles as $thing) {
		$fileinfo = getImagesize("../vp_pics/" .$thing);
		if(in_array($fileinfo["mime"], $picfiletypes) == true) {
			array_push($picfiles, $thing);
		}
	}

	//paneme kõik pildid ekraanile
	$piccount = count($picfiles);
	//$i + 1;
	//$i ++
	$imghtml = '<img src="../vp_pics/' .$picfiles[rand(0,3)] .'" alt="Tallinna Ülikool">';
	// for($i = 0; $i < $piccount; $i ++) {
	// 	$imghtml .= '<img src="../vp_pics/' .$picfiles[$i] .'" alt="Tallinna Ülikool">';
	// }
	require("header.php");
?>

<!DOCTYPE html>
<html lang="en">
	<body>
		<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise pilt">
		<p><a href="?logout=1"> Logi Välja</a></p>
		<br>
		<h2><a href="home.php">Avaleht</a></h2>

		<p>Peafunktsioonid</p>
		<ul>
			<li><a href="idearesults.php">Mõtted</a></li>
			<li><a href="userprofile.php">Kasutaja profiil</a></li>
		</ul>
			<p>Filmid</p>
		<ul>
			<li><a href="listfilms.php">Filmid (eraldi anbmebaas)</a></li>
			<li><a href="addfilms.php">Lisa film</a></li>
			<li><a href="addfilmgenre.php">Modifitseeri filmid</a></li>
			<li><a href="listfilmpersons.php">Filmi info</a></li>
			<li><a href="addfilminfo.php">Lisa filmi info</a></li>
		</ul>
		<p>Pildid</p>
		<ul>
			<li><a href="photoupload.php">Laadi ülesse pilt</a></li>
			<li><a href="photogallery_public.php">Avalike fotode galerii</a></li>
			<li><a href="photogallery_private.php">Minu isiklikud pildid</a></li>
		</ul>
		<p>Uudised</p>
		<ul>
			<li><a href="addnews.php">Lisa uudis</a></li>
			<li><a href="usernews.php">Minu uudised</a></li>
			<li><a href="news.php">Vaata kõik uudised</a></li>
		</ul>
		<hr>
		

		<h1>Võite vaadata vabalt ringi</h1><br>

		<p>Ring on siin</p><br><img src="https://charbase.com/images/glyph/9899" alt="ring">
		<h3>Lühitutvustus:</h3>
		<p><?php echo readuserdescription(); ?></p>
		<hr>
		<?php echo $imghtml; ?>
		<hr>
		<form method="POST">
			<label>Sisesta oma pähe tulnud mõte!</label>
			<input type="text" name="ideainput" placeholder="Kirjuta siia oma mõte!">
			<input type="submit" name="ideasubmit" value="Saada mõte ära!">
		</form>
		<p>Et näha kõik mõtted, vajutage <a href="idearesults.php">siia</a>!<p>
		<hr>
		<h3>Viimane külastaja sellest arvutist:</h3>
		<?php
			if(count($_COOKIE) > 0) {
				echo "<p>Küpsised on lubatud! Leiti: " .count($_COOKIE) ." küpsist.</p>";
			} else {
				echo "<p>Küpsised EI ole lubatud!</p>";
			}
			echo $lastvisitor;
		?>
	</body>
</html>