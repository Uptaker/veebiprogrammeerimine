  <?php
  session_start();
  
  // Kas on sisse loginud
  if(!isset($_SESSION["userid"])) {
	  // Jõuga suunatakse sisselogimise lehele
	  header("Location: page.php");
	  exit();
  }
  
  // Logime välja
  if(isset($_GET["logout"])) {
	  // Lõpetame sessiooni
	  session_destroy();
	  // Jõuga suunatakse sisselogimise lehele
	  header("Location: page.php");
	  exit();
  }