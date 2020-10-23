<?php
  // Käivitan sessiooni
  session_start();
  require("../../../config.php");
  require("fnc_user.php");
  require("fnc_common.php");
  // $username = "Marilii Saar";
  
  $email = null;
  $formerror = null;
  $emailerror = null;
  $pwderror = null;
  
  $fulltimenow = date("d.m.Y H:i:s");
  $hournow = date("H");
  $partofday = "vaba aeg";
  $weekdaynameset = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
  $monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  // var_dump($weekdaynameset);
  $weekdaynow = date("N");
  $datenow = date("d");
  $monthnow = date("m");
  $yearnow = date("Y");
  $timenow = date("H:i:s");
  
  if(isset($_POST["usersubmit"])) {
	  if(empty($_POST["emailinput"])) {
        $emailerror = "Kasutajatunnus on sisestamata!";
      }
    else {
		$email = filter_var($_POST["emailinput"], FILTER_SANITIZE_EMAIL);
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$email = filter_var($email, FILTER_VALIDATE_EMAIL);
		} else {
		  $emailerror = "Palun sisesta õige kujuga e-postiaadress!";
		}
    }
	  
	  if(empty($_POST["passwordinput"])) {
        $pwderror = "Salasõna on sisestamata!";
      }
      if(!empty($_POST["passwordinput"]) and strlen($_POST["passwordinput"]) < 8) {
        $pwderror = "Liiga lühike salasõna! (" . strlen($_POST["passwordinput"]) . " märki 8 asemel)";
      }

    if(empty($emailerror) and empty($pwderror)) {
      $result = signin($email, $_POST["passwordinput"]);
      // if($result == "OK") {
      //   $notice = "Sisse logimine õnnestus!";
      //   $email = "";
      // }
      // else {
        $formerror = $result;
      // }
  
    }
  }

  
  if($hournow >= 22 and $hournow < 6) {
      $partofday = "uneaeg";
  } // 22 - 6
  if($hournow >= 6 and $hournow < 7.5) {
    $partofday = "hommikuste ettevalmistuste aeg";
  } // 6 - 7.30
  if($hournow >= 7.5 and $hournow < 8) {
    $partofday = "ühistranspordi aeg";
  } // 7.30 - 8
  if($hournow >= 8 and $hournow <= 16) {
	  $partofday = "ülikoolis õppimise aeg";
  } // 8 - 16
  if($hournow >= 16 and $hournow < 16.5) {
    $partofday = "ühistranspordi aeg";
  } // 16 - 16.30

  
  // vaatame semestri kulgemist
  $semesterstart = new DateTime("2020-8-31");
  $semesterend = new DateTime("2020-12-13");
  // $semestertest = new DateTime("2020-12-14"); // Katsetamiseks - üks päev peale semestri lõppu
  // $semestertest2 = new DateTime("2020-8-30"); // Katsetamiseks - üks päev enne semestri algust
  $semesterduration = $semesterstart->diff($semesterend);
  $semesterdurationdays = $semesterduration->format("%r%a");
  $today = new DateTime("now");
  $semesterpassed = $semesterstart->diff($today)->format("%r%a");
  $todayday = $semesterpassed + 1;
  $semesterpercent = $semesterpassed * 100 / $semesterdurationdays;
  if($semesterpercent < 0) {
    $semesterpercent = 0;
  }
  if($semesterpercent > 100) {
    $semesterpercent = 100;
  }
  $semesterstatus = "Õppetöö käib, semestri algusest on möödunud " . $semesterpassed . " päeva - täna on semestri " . $todayday . ". päev.";
  if($semesterpassed == 0) { // === ei toimi!
    $semesterstatus = "Õppetöö käib, täna on semestri esimene päev.";
  }
  if($semesterpassed < 0) {
    $semesterstatus = "Semester pole veel alanud.";
  }
  if($semesterpassed == $semesterdurationdays) {
    $semesterstatus = "Õppetöö käib, semestri algusest on möödunud " . $semesterpassed . " päeva - täna on semestri viimane päev.";
  }
  if($semesterpassed > $semesterdurationdays) {
    $semesterstatus = "Semester on läbi!";
  }
  
  // annan ette lubatud pildivormingute loendi
  $picfiletypes = ["image/jpeg", "image/png"];
  // loeme piltide kataloogi sisu ja näitame pilte
  $allfiles = array_slice(scandir("../vp_pics/"), 2);
  
  $picfiles = [];
  
  foreach($allfiles as $file) {
	$fileinfo = getImagesize("../vp_pics/" . $file);
	
	if(in_array($fileinfo["mime"], $picfiletypes)) {
		array_push($picfiles, $file);
	}
  }
  
  //paneme suvalise pildi ekraanile
  $piccount = count($picfiles);

  $r = mt_rand(0, $piccount - 1);
  $imghtml = '<img src="../vp_pics/' . $picfiles[$r] . '" alt="Tallinna Ülikool">';
  
  require("header.php");
  
?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1>Veebiprogrammeerimine 2020</h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <hr />
  <p>Lehe avamise hetk: <?php echo $weekdaynameset[$weekdaynow - 1] .", " . $datenow . ". " . $monthnameset[$monthnow - 1] . " ". $yearnow . " " . $timenow; ?>.</p>
  <p><?php echo "Praegu on " . $partofday . "."; ?></p>
  <p><?php echo "2020 sügissemester on " . $semesterdurationdays . " päeva pikk."; ?></p>
  <p><?php echo $semesterstatus ?><br />
  <?php echo "(Läbitud on " . number_format($semesterpercent, 2) . "% semestrist)"; ?></p>
  <hr />
  <h1>Logi sisse:</h1>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="emailinput">Kasutajatunnus (email): </label><br />
	<input type="email" name="emailinput" id="emailinput" placeholder="Email" value="<?php echo $email; ?>"><span><?php echo $emailerror . "<br />"; ?></span>
	<label for="passwordinput">Salasõna: </label><br />
	<input type="password" name="passwordinput" id="passwordinput" placeholder="***"><span><?php echo $pwderror . "<br />"; ?></span>
	<span><?php echo $formerror . "<br />"; ?></span>
	<input type="submit" name="usersubmit" value="Logi sisse">
  </form>
  <br />
  Või <a href="adduser.php">loo kasutaja</a>
  <hr />
  <?php echo $imghtml; ?>

</body>
</html>