<?php
require("../../../config_vp2020.php");
$database = "if20_tammeoja_1";

function test_input($data) {
  $data = filter_var($data, FILTER_SANITIZE_STRING);
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function readShipments(){
  $newshtml = null;
  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
  //$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy >= ? AND deleted IS NULL ORDER BY vpphotos_id DESC LIMIT ?");
  //$stmt = $conn->prepare("SELECT vpphotos_id, filename, alttext FROM vpphotos WHERE privacy >= ? AND deleted IS NULL ORDER BY vpphotos_id DESC LIMIT ?,?");
  $stmt = $conn->prepare("SELECT shipment_id, numberplate, entrymass, emptymass, process_date
  FROM shipments
  GROUP BY shipment_id DESC");
  echo $conn->error;
  $stmt->bind_result($idfromdb, $plate, $entrymass, $emptymass, $date);
  $stmt->execute();
  $temphtml = "<table>\n\t<tr><th>Vastuvõtuaeg</th>\n<th>Numbriplaat</th>\n<th>Sisenemismass</th>\n<th>Tühimass</th>\n</tr>";
  while($stmt->fetch()){
  //   <table style="width:100%">
  //   <tr>
  //     <th>Kuupäev</th>
  //     <th>Sisenemismass</th>
  //     <th>Tühimass</th>
  //   </tr>
  //   <tr>
  //     <td>Jill</td>
  //     <td>Smith</td>
  //     <td>50</td>
  //   </tr>
  //   <tr>
  //     <td>Eve</td>
  //     <td>Jackson</td>
  //     <td>94</td>
  //   </tr>
  // </table>
    if($emptymass == NULL){
      $emptymass = "<b>Töötlemata</b>";
    }
    $temphtml.= "\n\t<tr><th>" .$date ."</th>\n<th>" .$plate ."</th>\n<th>" .$entrymass ."</th>\n<th>" .$emptymass ."</th>\n</tr>";
    }
    $temphtml .= "</table> \n";

  if(!empty($temphtml)){
    $newshtml = '<div id="newsarea" class="newsarea">' ."\n" .$temphtml . "\n </div> \n";
  } else {
    $newshtml = "<p>Kahjuks uudiseid! ei leitud!</p> \n";
  }
  $stmt->close();
  $conn->close();
  return $newshtml;
}

function readShipmentsFilter($numberplate){
  $newshtml = null;
  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
  //$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy >= ? AND deleted IS NULL ORDER BY vpphotos_id DESC LIMIT ?");
  //$stmt = $conn->prepare("SELECT vpphotos_id, filename, alttext FROM vpphotos WHERE privacy >= ? AND deleted IS NULL ORDER BY vpphotos_id DESC LIMIT ?,?");
  $stmt = $conn->prepare("SELECT shipment_id, numberplate, entrymass, emptymass, process_date
  FROM shipments
  WHERE numberplate = ?
  GROUP BY shipment_id DESC");
  echo $conn->error;
  $stmt->bind_param("s",$numberplate);
  $stmt->bind_result($idfromdb, $plate, $entrymass, $emptymass, $date);
  $stmt->execute();
  $temphtml = "<table>\n\t<tr><th>Vastuvõtuaeg</th>\n<th>Numbriplaat</th>\n<th>Sisenemismass</th>\n<th>Tühimass</th>\n</tr>";
  while($stmt->fetch()){
  //   <table style="width:100%">
  //   <tr>
  //     <th>Kuupäev</th>
  //     <th>Sisenemismass</th>
  //     <th>Tühimass</th>
  //   </tr>
  //   <tr>
  //     <td>Jill</td>
  //     <td>Smith</td>
  //     <td>50</td>
  //   </tr>
  //   <tr>
  //     <td>Eve</td>
  //     <td>Jackson</td>
  //     <td>94</td>
  //   </tr>
  // </table>
    if($emptymass == NULL){
      $emptymass = "<b>Töötlemata</b>";
    }
    $temphtml.= "\n\t<tr><th>" .$date ."</th>\n<th>" .$plate ."</th>\n<th>" .$entrymass ."</th>\n<th>" .$emptymass ."</th>\n</tr>";
    }
    $temphtml .= "</table> \n";

  if(!empty($temphtml)){
    $newshtml = '<div id="newsarea" class="newsarea">' ."\n" .$temphtml . "\n </div> \n";
  } else {
    $newshtml = "<p>Kahjuks uudiseid! ei leitud!</p> \n";
  }
  $stmt->close();
  $conn->close();
  return $newshtml;
}
  $platenotice = "";
  $plateerror = "";
  $numberplate = "";
  $notice = "<h1>Näitan kõik viljaveod</h1>";
  // pulling news content from database
  $readShipmentsPage = readShipments();
  $shipmenthtml = $readShipmentsPage;

  if(isset($_POST["entrysubmit"]))  {
    if(empty($_POST["plateinput"])){
      $plateerror = "Palun sisesta numbrimärgi!";
    }
    else {
        $numberplate = test_input($_POST["plateinput"]);

    }
    // if OK
    if(empty($plateerror) and empty($entrymasserror)){
      $shipmenthtml = readShipmentsFilter($numberplate);
      $notice = "Vaatan tulemusi numbrimärgi järgi";
      $numberplate = "";
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



  <h1>Viljavedu kokkuvõte</h1>
  </hr>
  
  <ul>
    <li><a href="?logout=1">Logi välja</a>!</li>
    <li><a href="processshipment.php">Kauba vastuvõtt/tühjendamine</a></li>
  </ul>
  
  <hr>
  <h2>Otsi numbrimärgi järgi</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="plateinput">Numbrimärk</label>
    <input type="text" name="plateinput" id="plateinput" placeholder="NT: ABC123" value="<?php echo $numberplate; ?>">
    <span><?php echo $plateerror; ?></span>
    <br/>
    <input type="submit" name="entrysubmit" value="Otsi">
  </form>
  <p><?php echo $platenotice; ?></p>

  <hr>

 <?php
  echo $notice;
  echo $shipmenthtml;
  ?>
  
</body>
</html>