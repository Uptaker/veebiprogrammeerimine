<?php

  require("../../../config.php");
  require("fnc_common.php");
  require("fnc_user.php");
  $notice = "";
  $monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  
  $firstname = "";
  $lastname = "";
  $gender = "";
  $birthday = null;
  $birthmonth = null;
  $birthyear = null;
  $birthdate = null;
  $email = "";
  
  $firstnameerror = "";
  $lastnameerror = "";
  $gendererror = "";
  $birthdayerror = null;
  $birthmontherror = null;
  $birthyearerror = null;
  $birthdateerror = null;
  $emailerror = "";
  $passworderror = "";

  // on submit
  if(isset($_POST["usersubmit"])) {
      if(empty($_POST["firstnameinput"])) {
          $firstnameerror = "Eesnimi on sisestamata!";
      }
      else {
        $firstname = test_input($_POST["firstnameinput"]);
      }

      if(empty($_POST["lastnameinput"])) {
        $lastnameerror = "Perekonnanimi on sisestamata!";
      }
      else {
        $lastname = test_input($_POST["lastnameinput"]);
      }

      if(empty($_POST["genderinput"])) {
        $gendererror = "Sugu on valimata!";
      }
      else {
        $gender = intval($_POST["genderinput"]);
      }
	  
	  if(!empty($_POST["birthdayinput"])) {
		  $birthday = intval($_POST["birthdayinput"]);
	  }
	  else {
		  $birthdayerror = "Palun vali sünnikuupäev!";
	  }
	  
	  if(!empty($_POST["birthmonthinput"])) {
		  $birthmonth = intval($_POST["birthmonthinput"]);
	  }
	  else {
		  $birthmontherror = "Palun vali sünnikuu!";
	  }
	  
	  if(!empty($_POST["birthyearinput"])) {
		  $birthyear = intval($_POST["birthyearinput"]);
	  }
	  else {
		  $birthyearerror = "Palun vali sünniaasta!";
	  }
	  
	  // Kontrollime kuupäeva kehtivust
	  if(!empty($birthday) and !empty($birthmonth) and !empty($birthyear)) {
		  if(checkdate($birthmonth, $birthday, $birthyear)) {
			  $tempdate = new DateTime($birthyear ."-" .$birthmonth ."-" .$birthday);
			  $birthdate = $tempdate->format("Y-m-d");
		  }
		  else {
			  $birthdateerror = "Kuupäev ei ole reaalne!";
		  }
	  }

      if(empty($_POST["emailinput"])) {
        $emailerror = "E-maili aadress on sisestamata!";
      }
      else {
        $email = filter_var(test_input($_POST["emailinput"]), FILTER_VALIDATE_EMAIL);
      }

      if(strlen($_POST["passwordinput"]) < 8 or strlen($_POST["passwordsecondaryinput"]) < 8) {
        $passworderror = "Liiga lühike salasõna! (" . strlen($_POST["passwordinput"]) . " märki 8 asemel)";
      }

      if(empty($_POST["passwordsecondaryinput"])) {
        $passworderror = "Palun sisesta salasõna uuesti!";
      }

      if(empty($_POST["passwordinput"])) {
        $passworderror = "Palun sisesta salasõna!";
      }

      if($_POST["passwordsecondaryinput"] !== $_POST["passwordinput"]) {
        $passworderror = "Salasõnad ei ole ühesugused!";
      }

      if(empty($firstnameerror) and empty($lastnameerror) and empty($gendererror) and empty($birthdayerror) and empty($birthmontherror) and empty($birthyearerror) and empty($birthdateerror) and empty($emailerror) and empty($passworderror)) {
        //  echo $firstname ." " .$lastname ." " .$gender ." " .$birthdate ." " .$email ." " .$_POST["passwordinput"];
        $result = signup($firstname, $lastname, $gender, $birthdate, $email, $_POST["passwordinput"]);
		if($result == "OK") {
			$notice = "Kõik korras, kasutaja loodud!";
			$firstname = "";
			$lastname = "";
			$gender = "";
			$birthday = null;
			$birthmonth = null;
			$birthyear = null;
			$birthdate = null;
			$email = "";
		}
		else {
			$notice = "Tekkis tehniline tõrge: " .$result;
		}
		
      }
  }

  $username = "Üliõpilane";
  require("header.php");
  
  ?>

  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse bänner">
  <h1>Loo Kasutaja</h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See leht on tehtud veebiprogrammeerimise kursusel 2020. aasta sügissemestril <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <ul>
	<li><a href="page.php">Avaleht</a></li>
  </ul>
  <hr />
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="firstnameinput">Eesnimi</label>
	<input type="text" name="firstnameinput" id="firstnameinput" placeholder="Eesnimi" value="<?php echo $firstname; ?>">
    <span><?php echo $firstnameerror; ?></span>
	<br />
    <label for="lastnameinput">Perekonnanimi</label>
	<input type="text" name="lastnameinput" id="lastnameinput" placeholder="Perekonnanimi" value="<?php echo $lastname; ?>">
    <span><?php echo $lastnameerror; ?></span>
	<br />
    <label for="genderinput">Sugu</label><br />
	<input type="radio" name="genderinput" id="gendermale" value="1" <?php if($gender == "1"){echo " checked";}?>>
    <label for="gendermale">Mees</label>
	<br />
    <input type="radio" name="genderinput" id="genderfemale" value="2" <?php if($gender == "2"){echo " checked";}?>>
    <label for="genderfemale">Naine</label>
	<br />
    <span><?php echo $gendererror . "<br />"; ?></span>
	<label for="birthdayinput">Sünnipäev: </label>
		  <?php
			echo '<select name="birthdayinput" id="birthdayinput">' ."\n";
			echo "\t\t" .'<option value="" selected disabled>päev</option>' ."\n";
			for ($i = 1; $i < 32; $i ++){
				echo "\t\t" .'<option value="' .$i .'"';
				if ($i == $birthday){
					echo " selected ";
				}
				echo ">" .$i ."</option> \n";
			}
			echo "\t </select> \n";
		  ?>
	  <label for="birthmonthinput">Sünnikuu: </label>
	  <?php
	    echo '<select name="birthmonthinput" id="birthmonthinput">' ."\n";
		echo "\t\t" .'<option value="" selected disabled>kuu</option>' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo "\t\t" .'<option value="' .$i .'"';
			if ($i == $birthmonth){
				echo " selected ";
			}
			echo ">" .$monthnameset[$i - 1] ."</option> \n";
		}
		echo "\t </select> \n";
	  ?>
	  <label for="birthyearinput">Sünniaasta: </label>
	  <?php
	    echo '<select name="birthyearinput" id="birthyearinput">' ."\n";
		echo "\t\t" .'<option value="" selected disabled>aasta</option>' ."\n";
		for ($i = date("Y") - 15; $i >= date("Y") - 110; $i --){
			echo "\t\t" .'<option value="' .$i .'"';
			if ($i == $birthyear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "\t </select> \n";
	  ?>
	  <br>
	  <span><?php echo $birthdateerror ." " .$birthdayerror ." " .$birthmontherror ." " .$birthyearerror; ?></span>
	  <br /><br />
    <label for="emailinput">E-mail (kasutajatunnus)</label>
	<input type="email" name="emailinput" id="emailinput" placeholder="Email" value="<?php echo $email; ?>">
    <span><?php echo $emailerror; ?></span>
	<br />
    <label for="passwordinput">Salasõna (min 8 tähemärki)</label>
	<input type="password" name="passwordinput" id="passwordinput" placeholder="***">
	<br />
    <label for="passwordsecondaryinput">Salasõna uuesti</label>
	<input type="password" name="passwordsecondaryinput" id="passwordsecondaryinput" placeholder="***">
	<br />
    <span><?php echo $passworderror . "<br />"; ?></span>
	<input type="submit" name="usersubmit" value="Loo kasutaja">
  </form>
  <span><?php echo $notice; ?></span>
</body>
</html>