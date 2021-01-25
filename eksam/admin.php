<?php
require("../../../config_vp2020.php");
$database = "if20_tammeoja_1";

//functions
function readMembers(){
  $newshtml = null;
  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
  $stmt = $conn->prepare("SELECT member_id, studentcode, firstname, lastname, added, cancelled, payment
  FROM party
  GROUP BY member_id DESC");
  $conn->set_charset("utf8");
  echo $conn->error;
  $stmt->bind_result($idfromdb, $studentcode, $firstname, $lastname, $date, $cancelled, $payment);
  $stmt->execute();
  $temphtml = "<table class='table'>\n\t<tr><th>Registreerimisaeg</th>\n<th>Nimi</th>\n<th>Õpilaskood</th>\n<th>Maksestaatus</th>\n<th>Staatus</th>\n";
  while($stmt->fetch()){
    $name = $firstname ." " .$lastname; 
    if($payment == NULL){
      $payment = "<b class='redText'>MAKSMATA</b>";
    } else {
      $payment = "<b class='greenText'>MAKSTUD</b>";
    }
    if($cancelled == 1){
      $cancelled = "<b class='yellowText'>Tühistatud</b>";
    } else {
      $cancelled = "<b>Tuleb</b>";
    }
    $temphtml.= "\n\t<tr><td>" .$date ."</td>\n<td>" .$name ."</td>\n<td>" .$studentcode ."</td>\n<td>" .$payment ."</td>\n<td>" .$cancelled ."</td>\n</tr>";
    }

  if(!empty($temphtml)){
    $newshtml .= $temphtml . "\n </table> \n";
  } else {
    $newshtml = "<p>Keegi pole registreerinud!</p> \n";
  }
  $stmt->close();
  $conn->close();
  return $newshtml;
}

function readComingMembers(){
  $newshtml = null;
  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
  $stmt = $conn->prepare("SELECT member_id, studentcode, firstname, lastname, added, cancelled, payment
  FROM party WHERE cancelled = 0
  GROUP BY member_id DESC");
  $conn->set_charset("utf8");
  echo $conn->error;
  $stmt->bind_result($idfromdb, $studentcode, $firstname, $lastname, $date, $cancelled, $payment);
  $stmt->execute();
  $temphtml = "<table class='table'>\n\t<tr><th>Registreerimisaeg</th>\n<th>Nimi</th>\n<th>Õpilaskood</th>\n<th>Maksestaatus</th>\n<th>Staatus</th>\n";
  while($stmt->fetch()){
    $name = $firstname ." " .$lastname; 
    if($payment == NULL){
      $payment = "<b class='redText'>MAKSMATA</b>";
    } else {
      $payment = "<b class='greenText'>MAKSTUD</b>";
    }
    if($cancelled == 1){
      $cancelled = "<b class='yellowText'>Tühistatud</b>";
    } else {
      $cancelled = "<b>Tuleb</b>";
    }
    $temphtml.= "\n\t<tr><td>" .$date ."</td>\n<td>" .$name ."</td>\n<td>" .$studentcode ."</td>\n<td>" .$payment ."</td>\n<td>" .$cancelled ."</td>\n</tr>";
    }

  if(!empty($temphtml)){
    $newshtml .= $temphtml . "\n </table> \n";
  } else {
    $newshtml = "<p>Keegi pole registreerinud!</p> \n";
  }
  $stmt->close();
  $conn->close();
  return $newshtml;
}

function readUnpaidMembers(){
  $notice = "<p>Õnneks maksmata pidutsejaid ei leitud! :)</p> \n";
  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
  $stmt = $conn->prepare("SELECT member_id, studentcode, firstname, lastname FROM party WHERE payment IS NULL");
  $conn->set_charset("utf8");
  echo $conn->error;
  $stmt->bind_result($idfromdb, $studentcode, $firstname, $lastname);
  $stmt->execute();
  $html = "";
  while($stmt->fetch()){
    $name = $firstname ." " .$lastname; 
    $html .= '<option value="' .$idfromdb .'"';
    // if($idfromdb == $selectedstudio){
    //   $studios .= " selected";
    // }
    $html .= ">" .$studentcode ." | " .$name ."</option> \n";
  }
  if(!empty($html)){
    $notice = '<select name="memberselect">' ."\n";
    $notice .= '<option value="" selected disabled>Vali isik</option>' ."\n";
    $notice .= $html;
    $notice .= "</select> \n";
  }
  $stmt->close();
  $conn->close();
  return $notice;
}

function confirmMember($memberid) {
  $notice = null;
  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
  $stmt = $conn->prepare("UPDATE party SET payment = 1 WHERE member_id = ?");
  echo $conn->error;
  $stmt->bind_param("i", $memberid);
  if($stmt->execute()){
    $notice = "Makse edukalt salvestatud!";
  } else {
    //echo $stmt->error;
    $notice = "Tekkis tõrge!" .$stmt->error;
  }
  $stmt->close();
  $conn->close();
  return $notice;
}

//variables
$memberselecterror = "";
$memberid = "";
$paymentnotice = "";

$filter = "";
$readmembershtml = readMembers();
$filternotice = "";



if(isset($_POST["paymentsubmit"]))  {
  if(empty($_POST["memberselect"])){
    $memberselecterror = "Palun vali isiku!";
  }
  else {
      $memberid = $_POST["memberselect"];

  }
  // if OK
  if(empty($memberselecterror)){
    $paymentnotice = confirmMember($memberid);
    $memberid = null;
  }
}

if(isset($_POST["filtersubmit"]))  {
  if(!isset($_POST["filter"])){
    $filtererror = "Palun vali filtri!";
  }
  else {
    $filter = intval($_POST["filter"]);
  }
  // if OK
  if(empty($filtererror)){
    if($filter == 1) {
      $readmembershtml = readMembers();
      $filternotice = "Kuvan kõiki";
    } else {
      $readmembershtml = readComingMembers();
      $filternotice = "Kuvan ainult tulevaid";
    }
    $filer = null;
  }
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin - Eksam 2020</title>
	<meta charset="UTF-8">
  <style>
    .redText
    {
      color: red;
    }
    .greenText
    {
      color: green;
    }
    .yellowText
    {
      color: #ffff00;
    }
    html
    {
      background-color: #66b3ff;
      padding: 20px;
      font-family:verdana;
    }
    body
    {
      width: 70%;
      margin: 0 auto;
      text-align: center;
      background-color: #b3e7ff;
      padding: 20px;
      border: 1px solid;
      box-shadow: 5px 10px #005780;
    }
    table, th, td
    {
      background-color: #66b3ff;
      margin: 0 auto;
      text-align: center;
      border: 1px solid black;
    }
    table
    {
      width: 80%;
      border-collapse: collapse;
    }
  </style>
</head>



  <h1>Peoinimeste administreerimine</h1>
  </hr>
    <p><a href="user.php">Kasutaja leht</a></p>
  
  <hr>
  <h3>Märgi maksmata isikuid 'MAKSTUD' staatuseks:</h3>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php echo readUnpaidMembers();?>
    <input type="submit" name="paymentsubmit" value="Makstud">
  </form>
  <p><?php echo $paymentnotice; ?></p>

  <h3>Filtreeri nimekirja:</h3>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<input id="all" name="filter" type="radio" value="1" <?php if($filter == 1){echo " checked";} ?>>
	<label for="all">Kuva kõiki (vaikimisi)</label>
	<input id="coming" name="filter" type="radio" value="2" <?php if($filter == 2){echo " checked";} ?>>
	<label for="coming">Ainult need, kes tulevad</label>
    <input type="submit" name="filtersubmit" value="Filtreeri">
  </form>
  <p><?php echo $filternotice; ?></p>
  <hr>
  <h1>Nimekiri</h1>
 <?php
  echo $readmembershtml;
  ?>
  
</body>
</html>