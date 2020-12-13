<head>
	<title>Veebiprogrammeerimine 2020</title>
	<meta charset="UTF-8">

	<?php
		if(isset($tolink)) {
			echo $tolink;
		}
	?>

	<style>
	
	a:link{
		color: <?php echo $_SESSION["usertxtcolor"]; ?> ;
	}
	a:visited{
		color: <?php echo $_SESSION["usertxtcolor"]; ?> ;
	}
	a:hover{
		color: <?php echo $_SESSION["usertxtcolor"]; ?> ;
		font-weight: bold;
		
	}
	a:focus{
		color: <?php echo $_SESSION["usertxtcolor"]; ?> ;
	}
	a:active{
		color: <?php echo $_SESSION["usertxtcolor"]; ?> ;
	}

	<?php
		echo "body { \n";
		if(isset($_SESSION["userbgcolor"])) {
			echo "\t\t background-color: " .$_SESSION["userbgcolor"] .";\n";
		} else {
			echo "\t\t background-color: #FFFFFF;" ."\n";
		}
		if(isset($_SESSION["usertxtcolor"])) {
			echo "\t\t color: " .$_SESSION["usertxtcolor"] .";\n";
		} else {
			echo "\t\t color: #000000;" ."\n";
		}
		echo "}";
?>
	</style>
</head>