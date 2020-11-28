<?php
  $database = "if20_tammeoja_1";
  
  function readpersonsinfilm($sortby, $sortorder) {
	  $notice = "<p>Kahjuks filmitegelasi seoses filmidega ei leitud!</p> \n";
	  $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	  $SQLsentence = "SELECT first_name, last_name, role, title FROM person JOIN person_in_movie ON person.person_id = person_in_movie.person_id JOIN movie ON movie.movie_id = person_in_movie.movie_id";

	  if($sortby == 0 and $sortorder == 0) {
		  $stmt = $conn->prepare($SQLsentence);
	  }
	  if($sortby == 4) {
		  if($sortorder == 2) {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY title DESC"); 
		  }
		  else {
			  $stmt = $conn->prepare($SQLsentence ." ORDER BY title"); 
		  }
	  }
	  
	  echo $conn->error; // <-- ainult õppimise jaoks!
	  $stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb);
	  $stmt->execute();
	  $lines = "";
	  while($stmt->fetch()) {
		  $lines .= "<tr>\n\t\t<td>" .$firstnamefromdb ." " .$lastnamefromdb ."</td>\n";
		  if(!empty($rolefromdb)) {
			  $lines .= "\t\t<td>" .$rolefromdb ."</td>\n";
		  }
		  else {
			  $lines .= "\t\t<td> </td>\n";
		  }
		  $lines .= "\t\t<td>" .$titlefromdb ."</td>\n\t";
	  }
	  if(!empty($lines)) {
		  $notice = "<table>\n\t<tr>\n\t\t<th>Isiku Nimi</th>";
		  $notice .= "\n\t\t<th>Roll Filmis</th>";
		  $notice .= "\n\t\t" .'<th>Filmi Pealkiri &nbsp;<a href="?sortby=4&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=4&sortorder=2">&darr;</a></th>' ."\n\t";
		  $notice .= $lines ."</tr>\n</table>\n";
	  }
	  
	  $stmt->close();
	  $conn->close();
	  return $notice;
  } // readpersonsinfilm lõpeb

  function readfromdb($sortby, $sortorder) {
	$notice = "<p>Kahjuks filmitegelasi seoses filmidega ei leitud!</p> \n";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$SQLsentence = "SELECT first_name, last_name, role, title FROM person JOIN person_in_movie ON person.person_id = person_in_movie.person_id JOIN movie ON movie.movie_id = person_in_movie.movie_id";

	if($sortby == 0 and $sortorder == 0) {
		$stmt = $conn->prepare($SQLsentence);
	}
	if($sortby == 4) {
		if($sortorder == 2) {
		  $stmt = $conn->prepare($SQLsentence ." ORDER BY title DESC"); 
		}
		else {
			$stmt = $conn->prepare($SQLsentence ." ORDER BY title"); 
		}
	}
	
	echo $conn->error; // <-- ainult õppimise jaoks!
	$stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb);
	$stmt->execute();
	$lines = "";
	while($stmt->fetch()) {
		$lines .= "<tr>\n\t\t<td>" .$firstnamefromdb ." " .$lastnamefromdb ."</td>\n";
		if(!empty($rolefromdb)) {
			$lines .= "\t\t<td>" .$rolefromdb ."</td>\n";
		}
		else {
			$lines .= "\t\t<td> </td>\n";
		}
		$lines .= "\t\t<td>" .$titlefromdb ."</td>\n\t";
	}
	if(!empty($lines)) {
		$notice = "<table>\n\t<tr>\n\t\t<th>Isiku Nimi</th>";
		$notice .= "\n\t\t<th>Roll Filmis</th>";
		$notice .= "\n\t\t" .'<th>Filmi Pealkiri &nbsp;<a href="?sortby=4&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=4&sortorder=2">&darr;</a></th>' ."\n\t";
		$notice .= $lines ."</tr>\n</table>\n";
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
  } // readfromdb lõpeb