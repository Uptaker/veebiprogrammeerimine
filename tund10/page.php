<?php
	//algatame sessiooni
	//session_start();
	//var_dump ($_POST);
	require("classes/SessionManager.php");
	SessionManager::sessionStart("vp", 0, "/~tammeoja/", "greeny.cs.tlu.ee");
	require("fnc_user.php");
	require("../../../config.php");
	require("fnc_common.php");
	require("fnc_photo.php");
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

	$fulltimenow = date("H:i:s");
	$hournow = date("H");
	$partofday = "lihtsalt aeg";
	if($hournow < 6) {
		$partofday = "uneaeg";
	} // enne 6
	if($hournow >= 8 and $hournow <= 18) {
		$partofday = "õppimise aeg";
	}
  
	$weekdayNameset = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	$monthNameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	$weekdaynow = date("N");
	$dayofmonth = date("d. ");
	$monthnow = date("m");

	#vaatame semestri kulgemist
	$semesterstart = new DateTime("2020-8-31");
	$semesterend = new DateTime("2020-12-13");
	$semesterduration = $semesterstart->diff($semesterend);
	$semesterdurationdays = $semesterduration->format("%r%a");
	$today = new DateTime("now");
	$semesterleft = $semesterstart->diff($today);
	$semesterleftdays = $semesterleft->format("%r%a");
	//percentages below
	$percentageelapsed = round($semesterleftdays/$semesterdurationdays*100,2);
	$percentageleft = 100-$percentageelapsed;

	//õppetöö protsent
	if($today = $semesterduration) { //õppetöö aktiivne
		$semesterstatus = "1. semestri õppetöö on aktiivne ning on möödunud " .$semesterleftdays 
		." päeva. Läbi on " .$percentageelapsed ." protsenti. Jäänud on " .$percentageleft ." protsenti";
	}
	if($today > $semesterduration) { // läbi
		$semesterstatus = "1. semestri õppetöö on läbi. Läbi on 100%.";
	}
	if($today < $semesterduration) { //pole alanud
		$semesterstatus = "1. semestri õppetöö ei ole veel alanud. Läbi on 0%.";
	}
	
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

	//log-in code

	$emailerror = null;
	$passworderror = null;
	
	$emailinput = null;
	$passwordinput = null;
	$notice = null;

	$email = null;
	$password= null;

	if(isset($_POST["loginsubmit"])) {
		if(empty($_POST["emailinput"])) {
			$emailerror = "E-mail sisestamata!";
		} else {
			$email = test_input($_POST["emailinput"]);
		}

		if(empty($_POST["passwordinput"])) {
			$passworderror = "Salasõna sisestamata!";
		}
		if(!empty($_POST["passwordinput"])) {

		}

		//Kõik korras ja kontrollitud
		if(empty($passworderror) and empty($emailerror)) {
			$result = signin($email, $_POST["passwordinput"]);
			if($result == "ok") {
				$email = null;
			} else {
				$notice = "Tekkis tehniline tõrge: " .$result;
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Tere tulemast!</title>
		<meta charset="UTF-8">
	</head>

	<body>
		<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise pilt">
		<ul>
			<li><a href="adduser.php">Registreerida</a></li>
		</ul>

		<hr>
		<p>Kasutaja juba olemas? Logige sisse!</p>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="emailinput">E-mail</label><br>
		<input type="email" name="emailinput" id="emailinput" value="<?php echo $email; ?>">
		<span><?php echo $emailerror; ?></span>
		<br>
		<label for="passwordinput">Salasõna</label><br>
		<input type="password" name="passwordinput" id="passwordinput">
		<span><?php echo $passworderror; ?></span>
		<br><br>
		<input type="submit" name="loginsubmit" value="Logi sisse">
		<span><?php echo $notice; ?></span>
		</form>
		<hr>

		<p>Lehe avamise hetk: <?php echo $dayofmonth .$monthNameset[$monthnow-1] .", " .$weekdayNameset[$weekdaynow-1] .", " .$fulltimenow; ?>.</p>
		<p><?php echo "Kellaajaliselt, praegu oleks " .$partofday ."."; ?></p>
		<h3><?php echo $semesterstatus; ?>.<h3>
		<hr>
		<p>Viimati lisatud avalik pilt:</p>
		<?php echo readLastPublicPhoto(3); ?>
		<hr>
		<p>Tallinna Ülikool:</p>
		<?php echo $imghtml; ?>
		<hr>
		<form method="POST">
			<label>Sisesta oma pähe tulnud mõte!</label>
			<input type="text" name="ideainput" placeholder="Kirjuta siia oma mõte!">
			<input type="submit" name="ideasubmit" value="Saada mõte ära!">
		</form>
		<p>Et näha kõik mõtted, peate logima sisse!<p>
	</body>
</html>