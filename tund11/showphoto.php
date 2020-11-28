<?php
	require("usesession.php");
	
	header("Content-type: image/jpeg");
	readfile("../photoupload/" .$_REQUEST["photo"]);