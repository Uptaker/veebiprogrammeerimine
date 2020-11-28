<?php
	$database = "if20_tammeoja_1";
	
	function storePhotoData($filename, $alttext, $privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpphotos (userid, filename, alttext, privacy) VALUES (?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issi", $_SESSION["userid"], $filename, $alttext, $privacy);
		if($stmt->execute()){
			$notice = 1;
		} else {
			//echo $stmt->error;
			$notice = 0;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}

	function storeNews($title, $newsinput, $mainphoto) {
		$notice = null;
		$timeNow = new DateTime('now');
		$dateadded = $timeNow->format('Y-m-d');
		$expires = $timeNow->modify('+1 year')->format('Y-m-d');
		var_dump($dateadded, $expires);
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpnews (title, content, userid, added, expire, newsphoto) VALUES (?, ?, ?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("ssisss", $title, $newsinput, $_SESSION["userid"], $dateadded, $expires, $mainphoto);
		if($stmt->execute()){
			$notice = "Uudis salvestatud!";
		} else {
			//echo $stmt->error;
			$notice = "Tekkis tõrge!" .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}