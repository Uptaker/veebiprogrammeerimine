<?php
	//session_start();
	require("classes/SessionManager.php");
	SessionManager::sessionStart("vp", 0, "/~tammeoja/", "greeny.cs.tlu.ee");
	//kas on sisselogitud
	if(!isset($_SESSION["userid"])) {
		//jõuga suunatakse sisselogimise lehele
		header("Location: page.php");
		exit();
	}

	//logime välja
	if(isset($_GET["logout"])) {
		//lõpetame sessiooni
		session_destroy();
		//jõuga suunatakse sisselogimise lehele
		header("Location: page.php");
		exit();

	}