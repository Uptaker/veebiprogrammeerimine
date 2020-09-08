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
	#vaatame semestri kulgemist
	$semesterstart = new DateTime("2020-8-31");
	$semesterend = new DateTime("2020-12-13");
	$semesterduration = $semesterstart->diff($semesterend);
	$semesterdurationdays = $semesterduration->format("%r%a");
	$today = new DateTime("now");
	$semesterleft = $semesterstart->diff($today);
	$semesterleftdays = $semesterleft->format("%r%a");
	//percentages below
	$percentageelapsed = $semesterleftdays/$semesterdurationdays*100;
	$percentageleft = 100-$percentageelapsed;


	//KODUNETÖÖ https://github.com/Veebiprogrammeerimine-2020/ryhm-1
	if($today = $semesterduration) { //õppetöö staatus
		$semesterstatus = "1. semestri õppetöö on aktiivne ning on möödunud " .$semesterleftdays 
		." päeva. Läbi on " .$percentageelapsed ." protsenti. Jäänud on " .$percentageleft ." protsenti";
	}
	if($today > $semesterduration) {
		$semesterstatus = "1. semestri õppetöö on läbi";
	}
	if($today < $semesterduration) {
		$semesterstatus = "1. semestri õppetöö ei ole veel alanud";
	}
	//õppetöö protsent


?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $username; ?> vaatab ringi</title>
		<meta charset="UTF-8">
	</head>
	<body>
		<h1>Võite vaadata vabalt ringi</h1><br>
		<p>Ring on <a href="https://areait.com.au/wp-content/uploads/2017/12/circle-png-circle-icon-1600.png">siin</a>!</p>
		<p>Lehe avamise hetk: <?php echo $fulltimenow; ?>.</p>
		<p><?php echo "Kellaajaliselt, praegu oleks " .$partofday ."."; ?></p>
		<p><?php echo "Veebilehe looja on " .$username ."." ?><p>
		<h3><?php echo $semesterstatus; ?>.<h3>
		<p><?php $semesterdurationday; ?></p>
	</body>
</html>