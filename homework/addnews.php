<?php
  require("usesession.php");

  require("../../../config_vp2020.php");
//   require("fnc_photo.php");
  require("fnc_common.php");
  require("fnc_news.php");
  require("classes/Photoupload_class.php");
  
  $tolink = "\t" .'<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>' ."\n";
  $tolink .= "\t" .'<script>tinymce.init({
							  selector:"textarea#newsinput", plugins: "link", menubar: "edit",
					});</script>' ."\n";
    
  $inputerror = "";
  $notice = null;
  $newstitle = null;
  $news = null;

  //photo
  $filetype = "FILETYPE";
  $filesizelimit = 2097152*5;
  $photodir = "../photoupload_news/";
  $photodir_thumb = "../photoupload_news/thumbnails/";
  $filename = null;
  $photomaxwidth = 600;
  $photomaxheight = 400;
  $thumbsize = 100;
  $watermark = "../img/vp_logo_w100_overlay.png";
  $check = null;

  //kui klikiti submit, siis ...
  if(isset($_POST["newssubmit"])){
    // if(strlen($_POST["newstitleinput"] == 0)) {
    if(empty($_POST["newstitleinput"])) {
      $inputerror = "Uudise pealkiri on puudu! ";
    } else {
      $newstitle = test_input($_POST["newstitleinput"]);
    }
    // if(strlen($_POST["newsinput"] == 0)) {
    if(empty($_POST["newsinput"])) {
      $inputerror .= "Uudise sisu on puudu!";
    } else {
      $news = news_input($_POST["newsinput"]);
    }

    //pilt
    $check = getimagesize($_FILES["photoinput"]["tmp_name"]);
    if($check !== false){
      if($check["mime"] == "image/jpeg"){
        $filetype = "jpg";
      }
      if($check["mime"] == "image/png"){
        $filetype = "png";
      }
      if($check["mime"] == "image/gif"){
        $filetype = "gif";
      }
    } else {
      $inputerror .= "Valitud fail ei ole pilt! ";
    }

    //kas on sobiva failisuurusega
    if(empty($inputerror) and $_FILES["photoinput"]["size"] > $filesizelimit){
      $inputerror .= "Liiga suur fail! " ;
    }
    //loome uue failinime
    $timestamp = microtime(1) * 100;
    $filename = "news_" .$timestamp ."." .$filetype;
    
    //ega fail äkki olemas pole
    if(file_exists($photodir .$filename)){
      $timestamp = microtime(1) * 10000;
      $filename = "news_" .$timestamp ."." .$filetype;
    }
    
    //kui pildikontroll korras
    if(empty($inputerror)) {
          //võtame kasutusele klassi
      $myphoto = new Photoupload($_FILES["photoinput"], $filetype);
      //teeme pildi väiksemaks
      $myphoto->resizePhoto($photomaxwidth, $photomaxheight, true);
      //lisame vesimärgi
      $myphoto->addWatermark($watermark);
      //salvestame vähendatud pildi
      $result = $myphoto->saveimage($photodir .$filename);
      if($result == 1){
        $notice .= " Pildi salvestamine õnnestus! ";
      } else {
        $inputerror .= " Pildi salvestamisel tekkis tõrge! ";
      }
      
      //teeme pisipildi
      $myphoto->resizePhoto($thumbsize, $thumbsize);
      $result = $myphoto->saveimage($photodir_thumb .$filename);
      if($result == 1){
        $notice .= "Pisipildi salvestamine õnnestus! ";
      } else {
        $inputerror .= "Pisipildi salvestamisel tekkis tõrge! ";
      }

      //eemaldan klassi
      unset($myphoto);

      //salvestan kõik andmebaasi
      if(empty($inputerror)) {
        $notice = storeNews($newstitle, $news, $filename);
        $newstitle = null;
        $news = null;
      }
    }
  }
  

  require("header.php");
?>
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See konkreetne leht on loodud veebiprogrammeerimise kursusel aasta 2020 sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  
  <ul>
    <li><a href="?logout=1">Logi välja</a>!</li>
    <li><a href="home.php">Avaleht</a></li>
  </ul>
  
  <hr>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
  <label for="newstitleinput">Uudise pealkiri</label><br>
	<input id="newstitleinput" name="newstitleinput" type="text" value="<?php echo $newstitle; ?>" required>
  <br><br>
  <label for="photoinput">Uudise foto</label><br>
	<input id="photoinput" name="photoinput" type="file" required>
  <br><br>
	<label for="newsinput">Uudise sisu</label><br>
	<textarea id="newsinput" name="newsinput" type="textarea"><?php echo $news; ?></textarea>
	<br><br>
	<input type="submit" id="newssubmit" name="newssubmit" value="Lae uudis üles">
  </form>
  <p id="notice">
  <?php
	echo $inputerror;
	echo $notice;
  ?>
  </p>
  
</body>
</html>