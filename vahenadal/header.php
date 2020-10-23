<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">

  <title>Veebiprogrammeerimine 2020</title>
  <style>
	<?php
		echo "body { \n";
		if(isset($_SESSION["userbgcolor"])) {
			echo "\t background-color: " .$_SESSION["userbgcolor"] .";\n";
		} 
		else {
			echo "\t background-color: #ffffff;\n";
		}
		if(isset($_SESSION["usertxtcolor"])) {
			echo "\t color: " .$_SESSION["usertxtcolor"] .";\n";
		} 
		else {
			echo "\t color: #000000;\n";
		}
		echo "\t}\n";
	?>
  </style>

</head>
<body>