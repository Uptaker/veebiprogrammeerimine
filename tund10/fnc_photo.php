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

	function readPublicPhotoThumbs($privacy){
		$photohtml = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy >= ? AND deleted IS NULL ORDER BY vpphotos_id DESC");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filenamefromdb, $alttextfromdb);
		$stmt->execute();
		$temphtml = null;
		while($stmt->fetch()){
			//<img src="failinimi.laiend" alt="alternatiivtekst">
			$temphtml .= '<img src="' .$GLOBALS["photouploaddir_thumb"] .$filenamefromdb .'" alt="' .$alttextfromdb .'">' ."\n";
		}
		if(!empty($temphtml)){
			$photohtml = "<div> \n" .$temphtml . "\n </div> \n";
		} else {
			$photohtml = "<p>Kahjuks galeriipilte ei leitud!</p> \n";
		}
		$stmt->close();
		$conn->close();
		return $photohtml;
	}

function readPublicPhotoThumbsPage($privacy, $limit, $page = 1){
	$photohtml = null;
	$skip = ($page - 1) * $limit;
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	//$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy >= ? AND deleted IS NULL ORDER BY vpphotos_id DESC LIMIT ?");
	$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy >= ? AND deleted IS NULL ORDER BY vpphotos_id DESC LIMIT ?,?");
	echo $conn->error;
	$stmt->bind_param("iii", $privacy, $skip, $limit);
	$stmt->bind_result($filenamefromdb, $alttextfromdb);
	$stmt->execute();
	$temphtml = null;
	while($stmt->fetch()){
		//<div class="thumbgallery">
		//<img src="failinimi.laiend" alt="alternatiivtekst">
		$temphtml .= '<div class="thumbgallery">' ."\n";
		$temphtml .= '<img src="' .$GLOBALS["photouploaddir_thumb"] .$filenamefromdb .'" alt="' .$alttextfromdb .' "class="thumbs">' ."\n";
		$temphtml .= "</div> \n";
	}
	if(!empty($temphtml)){
		$photohtml = '<div class="galleryarea">' ."\n" .$temphtml . "\n </div> \n";
	} else {
		$photohtml = "<p>Kahjuks galeriipilte ei leitud!</p> \n";
	}
	$stmt->close();
	$conn->close();
	return $photohtml;
}

function countPublicPhotos($privacy) {
	$photocount = 0;
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT COUNT(vpphotos_id) FROM vpphotos WHERE privacy >= ? AND deleted IS NULL");
	echo $conn->error;
	$stmt->bind_param("i", $privacy);
	$stmt->bind_result($result);
	$stmt->execute();
	if($stmt->fetch()) {
		$photocount = $result;
	}
	$stmt->close();
	$conn->close();
	return $photocount;
}

function readPrivatePhotoThumbsPage($privacy, $limit, $page = 1, $userid){
	$photohtml = null;
	$skip = ($page - 1) * $limit;
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	//$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy >= ? AND deleted IS NULL ORDER BY vpphotos_id DESC LIMIT ?");
	$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy = ? AND deleted IS NULL AND userid = ? ORDER BY vpphotos_id DESC LIMIT ?,?");
	echo $conn->error;
	$stmt->bind_param("iiii", $privacy, $userid, $skip, $limit);
	$stmt->bind_result($filenamefromdb, $alttextfromdb);
	$stmt->execute();
	$temphtml = null;
	while($stmt->fetch()){
		//<div class="thumbgallery">
		//<img src="failinimi.laiend" alt="alternatiivtekst">
		$temphtml .= '<div class="thumbgallery">' ."\n";
		$temphtml .= '<img src="' .$GLOBALS["photouploaddir_thumb"] .$filenamefromdb .'" alt="' .$alttextfromdb .' "class="thumbs">' ."\n";
		$temphtml .= "</div> \n";
	}
	if(!empty($temphtml)){
		$photohtml = '<div class="galleryarea">' ."\n" .$temphtml . "\n </div> \n";
	} else {
		$photohtml = "<p>Kahjuks isiklike galeriipilte ei leitud!</p> \n";
	}
	$stmt->close();
	$conn->close();
	return $photohtml;
}

function countPrivatePhotos($privacy, $userid) {
	$photocount = 0;
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT COUNT(vpphotos_id) FROM vpphotos WHERE privacy = ? AND deleted IS NULL AND userid = ?");
	echo $conn->error;
	$stmt->bind_param("ii", $privacym, $userid);
	$stmt->bind_result($result);
	$stmt->execute();
	if($stmt->fetch()) {
		$photocount = $result;
	}
	$stmt->close();
	$conn->close();
	return $photocount;
}

function readLastPublicPhoto($privacy){
	$photouploaddir_thumb = "../thumbnail/";
	$photohtml = null;
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE vpphotos_id = (SELECT MAX(vpphotos_id) FROM vpphotos WHERE privacy = ? AND deleted IS NULL)");
	echo $conn->error;
	$stmt->bind_param("i", $privacy);
	$stmt->bind_result($filenamefromdb, $alttextfromdb);
	$stmt->execute();
	$temphtml = null;
	while($stmt->fetch()){
		//<img src="failinimi.laiend" alt="alternatiivtekst">
		$temphtml .= '<img src="' .$photouploaddir_thumb .$filenamefromdb .'" alt="' .$alttextfromdb .'">' ."\n";
	}
	if(!empty($temphtml)){
		$photohtml = "<div> \n" .$temphtml . "\n </div> \n";
	} else {
		$photohtml = "<p>Kahjuks galeriipilte ei leitud!</p> \n";
	}
	$stmt->close();
	$conn->close();
	return $photohtml;
}