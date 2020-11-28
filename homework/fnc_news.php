<?php
	$database = "if20_tammeoja_1";

	function storeNews($title, $newsinput, $mainphoto) {
		$notice = null;
		$timeNow = new DateTime('now');
		$dateadded = $timeNow->format('Y-m-d');
		$expires = $timeNow->modify('+1 year')->format('Y-m-d');
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpnews (title, content, userid, added, expire, newsphoto) VALUES (?, ?, ?, ?, ?, ?)");
		$conn->set_charset("utf8");
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

	function countNews(){
		$newscount = 0;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(vpnews_id) FROM vpnews WHERE deleted IS NULL");
		echo $conn->error;
		$stmt->bind_result($result);
		$stmt->execute();
		if($stmt->fetch()){
			$newscount = $result;
		}
		$stmt->close();
		$conn->close();
		return $newscount;
	}

	function newsToday(){
		$newscount = 0;
		$date = new dateTime('now');
		$today = $date->format('Y-m-d');
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT COUNT(vpnews_id) FROM vpnews WHERE added = ? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param('s', $today);
		$stmt->bind_result($result);
		$stmt->execute();
		if($stmt->fetch()){
			$newscount = "<div class='newstoday'> Täna postitati " .$result ." uudist!</div>";
		} else {
			$newscount = "<div class='newstoday'>Tekkis tõrge! fnc_news</div>";
		}
		$stmt->close();
		$conn->close();
		return $newscount;
	}

	function readNewsPage($limit, $page = 1){
		$newshtml = null;
		$skip = ($page - 1) * $limit;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//$stmt = $conn->prepare("SELECT filename, alttext FROM vpphotos WHERE privacy >= ? AND deleted IS NULL ORDER BY vpphotos_id DESC LIMIT ?");
		//$stmt = $conn->prepare("SELECT vpphotos_id, filename, alttext FROM vpphotos WHERE privacy >= ? AND deleted IS NULL ORDER BY vpphotos_id DESC LIMIT ?,?");
		$stmt = $conn->prepare("SELECT vpnews.vpnews_id, vpusers.vpusers_id, vpusers.firstname, vpusers.lastname, vpnews.newsphoto, vpnews.added, vpnews.expire, vpnews.title, vpnews.content
		FROM vpnews
		JOIN vpusers ON vpnews.userid = vpusers.vpusers_id
		WHERE deleted IS NULL
		GROUP BY vpnews.vpnews_id DESC LIMIT ?, ?");
		echo $conn->error;
		$stmt->bind_param("ii", $skip, $limit);
		$stmt->bind_result($newsidfromdb, $useridfromdb, $firstnamefromdb, $lastnamefromdb, $filenamefromdb, $creationdate, $expirationdate, $titlefromdb, $contentfromdb);
		$stmt->execute();
		$temphtml = null;
		while($stmt->fetch()){
			//<div class="newsthumbgallery">
			//<h2>Pealkiri</h2>
			//<img src="failinimi.laiend" alt="alternatiivtekst" class="thumbs" data-fn="failinim.laiend" data-id="7">
			//<p>Eesnimi Perekonnanimi</p>
			//</div>
			$namefromdb = $firstnamefromdb ." " .$lastnamefromdb;
			$namefromdb = utf8_encode($namefromdb);
			$temphtml .= '<div  class="newslist">' ."\n";
			$temphtml .= '<img src="' .$GLOBALS["photodir_thumb"] .$filenamefromdb .'" alt="' .$titlefromdb .'"  class="thumbs" data-fn="' .$filenamefromdb .'" data-id="' .$newsidfromdb .'"
			data-title="' .$titlefromdb .'" data-author="' .$namefromdb .'"  data-content="' .$contentfromdb .'" data-added="' .$creationdate .'" data-expired="' .$expirationdate .'">' ."\n";
			if($newsidfromdb % 2 == 0) {
				$temphtml .= '<div class="rightcontent1"><h3>' .$titlefromdb ."</h3>\n";
			} else {
				$temphtml .= '<div class="rightcontent2"><h3>' .$titlefromdb ."</h3>\n";
			}
			$temphtml .= '<p><b>Autor:</b> ' .$namefromdb ." \n";
			$temphtml .= "</p></div> \n";
			$temphtml .= "</div> \n";
		}
		if(!empty($temphtml)){
			$newshtml = '<div id="newsarea" class="newsarea">' ."\n" .$temphtml . "\n </div> \n";
		} else {
			$newshtml = "<p>Kahjuks uudiseid! ei leitud!</p> \n";
		}
		$array = [$newshtml, $filenamefromdb, $creationdate, $expirationdate, $titlefromdb, $contentfromdb, $namefromdb];
		$stmt->close();
		$conn->close();
		return $array;
	}

	/**
 * Increases or decreases the brightness of a color by a percentage of the current brightness.
 *
 * @param   string  $hexCode        Supported formats: `#FFF`, `#FFFFFF`, `FFF`, `FFFFFF`
 * @param   float   $adjustPercent  A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
 *
 * @return  string
 */
function adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        $color   = hexdec($color); // Convert to decimal
        $color   = max(0,min(255,$color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }

    return $return;
}