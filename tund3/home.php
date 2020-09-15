<?php
	$username = "Markus Tammeoja";
	$fulltimenow = date("d.m.Y - H:i:s");
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


	//KODUNETÖÖ https://github.com/Veebiprogrammeerimine-2020/ryhm-1
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
	//õppetöö protsent
	
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
	$imghtml = "";
	for($i = 0; $i < $piccount; $i ++) {
		$imghtml .= '<img src="../vp_pics/' .$picfiles[$i] .'" alt="Tallinna Ülikool">';
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $username; ?> vaatab ringi</title>
		<meta charset="UTF-8">
	</head>
	<body>
		<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise pilt">
		<h1>Võite vaadata vabalt ringi</h1><br>
		<p>Ring on <a href="https://areait.com.au/wp-content/uploads/2017/12/circle-png-circle-icon-1600.png">siin</a>!</p>
		<p>Lehe avamise hetk: <?php echo $weekdayNameset[$weekdaynow-1] .", " .$fulltimenow; ?>.</p>
		<p><?php echo "Kellaajaliselt, praegu oleks " .$partofday ."."; ?></p>
		<p><?php echo "Veebilehe looja on " .$username ."." ?><p>
		<h3><?php echo $semesterstatus; ?>.<h3>
		<p><?php $semesterdurationday; ?></p>
		<p><?php echo $imghtml; ?></p>
	</body>
</html>