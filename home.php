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
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $username; ?> vaatab ringi</title>
		<meta charset="UTF-8">
	</head>
	<body>
		<h3>Võite vaadata vabalt ringi</h3><br>
		<p>Ring on <a href="https://areait.com.au/wp-content/uploads/2017/12/circle-png-circle-icon-1600.png">siin</a>!</p>
		<p>Lehe avamise hetk: <?php echo $fulltimenow; ?>.</p>
		<p><?php echo "Praegu on " .$partofday ."."; ?></p>
		<p><?php echo "Veebilehe looja on " .$username ."." ?><p>
	</body>
</html>