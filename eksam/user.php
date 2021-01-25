<?php
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
// functions
  function saveMember($firstname, $lastname, $studentcode, $payment) {
    $notice = null;
    if($payment == 1){

    } else {
      $payment = null;
    }
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO party (firstname, lastname, studentcode, payment) VALUES (?, ?, ?, ?)");
		$conn->set_charset("utf8");
		echo $conn->error;
		$stmt->bind_param("ssii", $firstname, $lastname, $studentcode, $payment);
		if($stmt->execute()){
			$notice = "People registreeritud!";
		} else {
			//echo $stmt->error;
			$notice = "Tekkis tõrge!" .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
  }
  
  function cancelMember($cancelcode) {
		$notice = null;
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT studentcode FROM party WHERE studentcode = ? AND cancelled = 0");
    echo $conn->error;
    $stmt->bind_param("i", $cancelcode);
    $stmt->execute();
    if($stmt->fetch()){
      $stmt->close();
      $stmt = $conn->prepare("UPDATE party SET cancelled = 1 WHERE studentcode = ?");
      echo $conn->error;
      $stmt->bind_param("i", $cancelcode);
      if($stmt->execute()){
        $notice = "Olete nimekirjast edulakt eemaldatud!";
      } else {
        //echo $stmt->error;
        $notice = "Tekkis tõrge!" .$stmt->error;
      }
    } else {
      $notice = "Teie ei ole registreerunute nimekirjas!";
    }
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function countMembers(){
		$count = 0;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(member_id) FROM party WHERE cancelled = 0");
		echo $conn->error;
		$stmt->bind_result($result);
		$stmt->execute();
		if($stmt->fetch()){
			$count = $result;
		}
		$stmt->close();
		$conn->close();
    return $count;
  }

  function countPaidMembers(){
		$count = 0;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(member_id) FROM party WHERE cancelled = 0 AND payment = 1");
		echo $conn->error;
		$stmt->bind_result($result);
		$stmt->execute();
		if($stmt->fetch()){
			$count = $result;
		}
		$stmt->close();
		$conn->close();
    return $count;
  }

  // variables
  $firstnameerror = "";
  $lastnameerror = "";
  $studentcodeerror = "";
  $registrationnotice = "";
  $cancelcodeerror = "";
  $cancelnotice = "";

  $cancelcode = "";
  $firstname = "";
  $lastname = "";
  $studentcode = "";
  $payment = null;
  $paymenterror = "";


  if(isset($_POST["register"]))  {
    if(empty($_POST["firstnameinput"])){
      $firstnameerror = "Palun sisesta eesnime!!";
    } else {
        $firstname = test_input($_POST["firstnameinput"]);    
    }
    if(empty($_POST["lastnameinput"])){
      $lastnameerror = "Palun sisesta perekonnanime!!";
    } else {
        $lastname = test_input($_POST["lastnameinput"]);
    }
    if(empty($_POST["studentcode"])){
      $studentcodeerror = "Palun sisesta õpilaskoodi! ";
    } else {
      $studentcode = intval(test_input($_POST["studentcode"]));
    }
    if(strlen($studentcode) == 6){
      $studentcode = intval(test_input($_POST["studentcode"]));
    } else {
      $studentcodeerror .= "Õpilaskood koosneb kuuest ühikust!";
      var_dump($_POST["studentcode"], strlen($_POST["studentcode"]));
    }
    if(empty($_POST["payment"])){
      $payment = 0;
    } else {
      $payment = 1;
    }
    // if OK
    if(empty($studentcodeerror) and empty($firstnameerror) and empty($lastnameerror)){
      $registrationnotice = saveMember($firstname, $lastname, $studentcode, $payment);
      $firstname = "";
      $lastname = "";
      $studentcode = "";
      $payment = null;
    }
  }


if(isset($_POST["cancelsubmit"])){
  if(empty($_POST["cancelcode"])){
    $cancelcodeerror = "Sisesta oma õpilaskoodi!";
  } else {
    $cancelcode = intval($_POST["cancelcode"]);
  }
  // if OK
  if(empty($cancelcodeerror)){
    $cancelnotice = cancelMember($cancelcode);
    $cancelcode = "";
  }
}
  ?>
<!DOCTYPE html>
<html>
<head>
	<title>Kasutaja - Eksam 2020</title>
	<meta charset="UTF-8">
  <style>
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
    .redText
    {
      color: red;
    }
  </style>
</head>

<body>
  <h1>Peole registreerimise vorm</h1>
  <a class='redText' href="admin.php">Admin vaade</a>
  <hr>
  <h2>Lisa osalemine</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="firstnameinput">Eesnimi</label><br>
    <input type="text" name="firstnameinput" id="firstnameinput" placeholder="NT: Markus" value="<?php echo $firstname; ?>">
    <br><span><?php echo $firstnameerror; ?></span>
    <br>
    <label for="lastnameinput">Perekonnanimi</label><br>
    <input type="text" name="lastnameinput" id="lastnameinput" placeholder="NT: Tammeoja" value="<?php echo $lastname; ?>">
    <br><span><?php echo $lastnameerror; ?></span>
    <br>
    <label for="studentcode">Õpilaskood</label><br>
    <input type="number" name="studentcode" id="studentcode" placeholder="NT: 123456" value="<?php echo $studentcode; ?>">
    <br><span><?php echo $studentcodeerror; ?></span>
    <br>
    <input id="paid" name="payment" type="radio" value="1" <?php if($payment == 1){echo " checked";} ?>>
	  <label for="all">Makstud</label>
    <br><span><?php echo $paymenterror; ?></span>
    <br>
    <input type="submit" name="register" value="Registreeru">
  </form>
  <p><?php echo $registrationnotice; ?></p>
  <hr>

  <h2>Eemalda enda registreerumist</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="cancelcode">Õpilaskood</label><br>
    <input type="number" name="cancelcode" id="cancelcode" placeholder="NT: 123456" value="<?php echo $cancelcode; ?>">
    <br><span><?php echo $cancelcodeerror; ?></span><br>
    <input type="submit" name="cancelsubmit" value="Eemalda peo nimekirjast">
    <br><span><?php echo $cancelnotice; ?></span>
  </form>

  <hr>
  <p> People on registreerinud <?php echo countMembers();?> isikut!</p>
  <p> Makstud on <?php echo countPaidMembers();?> isikul!</p>


</body>
</html>