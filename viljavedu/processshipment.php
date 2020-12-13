<?php

// var_dump($_POST);
  require("../../../config_vp2020.php");
  $database = "if20_tammeoja_1";

// Sanitizes input
  function test_input($data) {
	  $data = filter_var($data, FILTER_SANITIZE_STRING);
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
  }

  function saveShipment($numberplate, $entrymass) {
		$notice = null;
		// $timeNow = new DateTime('now');
		// $dateadded = $timeNow->format('Y-m-d');
		// $expires = $timeNow->modify('+1 year')->format('Y-m-d');
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO shipments (numberplate, entrymass) VALUES (?, ?)");
		$conn->set_charset("utf8");
		echo $conn->error;
		$stmt->bind_param("si", $numberplate, $entrymass);
		if($stmt->execute()){
			$notice = "Viljavedu salvestatud!";
		} else {
			//echo $stmt->error;
			$notice = "Tekkis tõrge!" .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
  }
  
  function finishShipment($shipmentid, $emptymass) {
		$notice = null;
		// $timeNow = new DateTime('now');
		// $dateadded = $timeNow->format('Y-m-d');
		// $expires = $timeNow->modify('+1 year')->format('Y-m-d');
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("UPDATE shipments SET emptymass = ? WHERE shipment_id = ?"); //INSERT INTO table2 (column1, column2, column3, ...)
		// $conn->set_charset("utf8");
		echo $conn->error;
		$stmt->bind_param("ii", $emptymass, $shipmentid);
		if($stmt->execute()){
			$notice = "Viljavedu lõpuni viidud!";
		} else {
			//echo $stmt->error;
			$notice = "Tekkis tõrge!" .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

// reads all unprocessed shipments
  function readUnprocessedShipments(){
    $notice = "<p>Kahjuks stuudioid ei leitud!</p> \n";
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT shipment_id, numberplate FROM shipments WHERE emptymass IS NULL"); //WHERE emptymass IS NULL
    $conn->set_charset("utf8");
    echo $conn->error;
    $stmt->bind_result($idfromdb, $shipmentfromdb);
    $stmt->execute();
    $html = "";
    while($stmt->fetch()){
      $html .= '<option value="' .$idfromdb .'"';
      // if($idfromdb == $selectedstudio){
      //   $studios .= " selected";
      // }
      $html .= ">" .$shipmentfromdb ."</option> \n";
    }
    if(!empty($html)){
      $notice = '<select name="carinput">' ."\n";
      $notice .= '<option value="" selected disabled>Vali auto</option>' ."\n";
      $notice .= $html;
      $notice .= "</select> \n";
    }
    $stmt->close();
    $conn->close();
    return $notice;
  }

  // reads all shipments
  function readShipments(){
    $notice = "<p>Kahjuks stuudioid ei leitud!</p> \n";
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT shipment_id, numberplate FROM shipments");
    $conn->set_charset("utf8");
    echo $conn->error;
    $stmt->bind_result($idfromdb, $shipmentfromdb);
    $stmt->execute();
    $studios = "";
    while($stmt->fetch()){
      $studios .= '<option value="' .$idfromdb .'"';
      if($idfromdb == $selectedstudio){
        $studios .= " selected";
      }
      $studios .= ">" .$shipmentfromdb ."</option> \n";
    }
    if(!empty($studios)){
      $notice = '<select name="studioinput">' ."\n";
      $notice .= '<option value="" selected disabled>Vali stuudio</option>' ."\n";
      $notice .= $studios;
      $notice .= "</select> \n";
    }
    
    $stmt->close();
    $conn->close();
    return $notice;
  }

  // variables
  $plateerror = "";
  $emptymasserror = "";
  $entrymasserror = "";
  $entrynotice = "";
  $emptynotice = "";

  $numberplate = "";
  $entrymass = "";
  $emptymass = "";
  $shipmentid = "";

  $plateselecthtml = readUnprocessedShipments();


  if(isset($_POST["entrysubmit"]))  {
    if(empty($_POST["plateinput"])){
      $plateerror = "Palun sisesta numbrimärgi!";
    }
    else {
        $numberplate = test_input($_POST["plateinput"]);
        
    }
    if(empty($_POST["entrymass"])){
      $entrymasserror = "Palun sisesta auto sisenemismassi!";
    } else {
      $entrymass = intval(test_input($_POST["entrymass"]));
        
    }
    // if OK
    if(empty($plateerror) and empty($entrymasserror)){
      $entrynotice = saveShipment($numberplate, $entrymass);
      $numberplate = "";
      $entrymass = "";
    }
  }


   if(isset($_POST["emptysubmit"]))  {
    if(empty($_POST["carinput"])){
      $plateselecterror = "Palun vali numbrimärgi!";
    } else {
      $shipmentid = intval($_POST["carinput"]);
    }
    if(empty($_POST["emptymass"])){
      $emptymasserror = "Palun sisesta auto väljumismassi!";
    } else {
      $emptymass = intval(test_input($_POST["emptymass"]));
        
    }
    // if OK
    if(empty($plateselecterror) and empty($emptymasserror)){
      $emptynotice = finishShipment($shipmentid, $emptymass);
      $emptymass = "";
    }
  }
  ?>
<!DOCTYPE html>
<html>
<head>
	<title>Veebiprogrammeerimine 2020</title>
	<meta charset="UTF-8">

  <style>
    html, body {
        height: 100%;
    }

    html {
        display: table;
        margin: auto;
    }

    body {
        display: table-cell;
        vertical-align: middle;
    }
  </style>

</head>

<body>
  <h1>Viljaveo haldamise leht</h1>

  <a href="viewshipments.php">Vaata viljaveode kokkuvõted</a>

  <hr/>

  <h2>Lisa viljavedu</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="plateinput">Numbrimärk</label>
    <input type="text" name="plateinput" id="plateinput" placeholder="NT: ABC123" value="<?php echo $numberplate; ?>">
    <span><?php echo $plateerror; ?></span>
    <br/>
    <label for="entrymass">Kui palju kaalus lattu sisenemisel (kg)</label>
    <input type="number" name="entrymass" id="entrymass" placeholder="NT: 3500" value="<?php echo $entrymass; ?>">
    <span><?php echo $entrymasserror; ?></span>
    <br/>
    <input type="submit" name="entrysubmit" value="Võta vastu">
  </form>
  <p><?php echo $entrynotice; ?></p>

  <h2>Tühjenda viljavedu</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php
		echo $plateselecthtml;
	  ?>
    <span><?php $plateselecterror; ?></span>
    <label for="emptymass">Tühimass (kg)</label>
    <input type="number" name="emptymass" id="emptymass" placeholder="NT: 1500" value="<?php echo $emptymass; ?>">
    <span><?php echo $emptymasserror; ?></span>
	<input type="submit" name="emptysubmit" value="Vii viljavedu lõpuni"><span><?php echo $emptynotice; ?></span>
  </form>


</body>
</html>