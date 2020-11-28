<head>
	<title>Veebiprogrammeerimine 2020</title>
	<meta charset="UTF-8">

	<?php
		if(isset($tolink)) {
			echo $tolink;
		}
	?>

	<style>
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
	
		a:link{
		  color:white;
		}
		a:visited{
		  color:white;
		}
		a:hover{
		  color:red;
		  font-weight: bold;
		  
		}
		a:focus{
		  color:white;
		}
		a:active{
		  color:red;
		}
	</style>
</head>