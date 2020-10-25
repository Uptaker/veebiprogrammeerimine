<?php
    $database = "if20_tammeoja_1";

    //funktsioon, mis loeb kõikide filmide info
    function readfilms() {
        $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("SELECT * FROM film");
        echo $conn->error;
        //seome tulemuse muutujaga
        $stmt->bind_result($titlefromdb, $yearfromdb, $durationfromdb, $genrefromdb, $studiofromdb, $directorfromdb);
        $stmt->execute();
        $filmhtml = "<ol>\n";
        while($stmt->fetch()) {
            $filmhtml .= "\t\t\t<li>" .$titlefromdb ."\n";
                $filmhtml .="\t\t\t\t<ul> \n";
                    $filmhtml .= "\t\t\t\t\t<li>Valmimisaasta: " .$yearfromdb ."</li>\n";
                    $filmhtml .= "\t\t\t\t\t<li>Kestus minutites: " .$durationfromdb ." minutit</li>\n";
                    $filmhtml .= "\t\t\t\t\t<li>Žanr: " .$genrefromdb ."</li>\n";
                    $filmhtml .= "\t\t\t\t\t<li>Tootja/stuudio: " .$studiofromdb ."</li>\n";
                    $filmhtml .= "\t\t\t\t\t<li>Lavastaja: " .$directorfromdb ."</li>\n";
                $filmhtml .="\t\t\t\t</ul> \n";
            $filmhtml .= "\t\t\t</li>";
        }
        $filmhtml .= "\t\t</ol>\n";
        $stmt->close();
        $conn->close();
        return $filmhtml;
    }

    //klikkides, lähevad andmed andmebaasi..
    function savefilm($titleinput, $yearinput, $durationinput, $genreinput, $studioinput, $directorinput) {
        $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("INSERT INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES(?,?,?,?,?,?)");
        echo $conn->error;
        $stmt->bind_param("siisss", $titleinput, $yearinput, $durationinput, $genreinput, $studioinput, $directorinput);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    function readpersonsinfilm($sortby, $sortorder) {
        $studionotice = "<p>Kahjuks filmitegelasi ei leitud!<p>\n";
        $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
        $SQLsentence = "SELECT first_name, last_name, role, title 
                        FROM person 
                        JOIN person_in_movie 
                        ON person.person_id = person_in_movie.person_id 
                        JOIN movie 
                        ON movie.movie_id = person_in_movie.movie_id";
        if($sortby == 0 and $sortorder == 0) {
            $stmt = $conn->prepare($SQLsentence); // fetching data UNSORTED
        }
    
        if($sortby == 4) { //TITLE
            if($sortorder == 2) {
                $stmt = $conn->prepare($SQLsentence ." ORDER BY title DESC"); // fetching data SORTED BY title DESCENDING
            } else {
                $stmt = $conn->prepare($SQLsentence ." ORDER BY title");
            }
        }
    
        if($sortby == 3) { //PERSON
            if($sortorder == 2) {
                $stmt = $conn->prepare($SQLsentence ." ORDER BY last_name DESC"); // fetching data SORTED BY person DESCENDING
            } else {
                $stmt = $conn->prepare($SQLsentence ." ORDER BY last_name");
            }
        }
        if($sortby == 2) { //ROLE
            if($sortorder == 2) {
                $stmt = $conn->prepare($SQLsentence ." ORDER BY role DESC"); // fetching data SORTED BY role DESCENDING
            } else {
                $stmt = $conn->prepare($SQLsentence ." ORDER BY role");
            }
        }
        echo $conn->error;
        $stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb);
        $stmt->execute();
        $lines = "";
        while($stmt->fetch()) {
            $lines .= "<tr> \n";
            $lines .= "\t <td>".$firstnamefromdb ." " .$lastnamefromdb ."</td>";
            $lines .= '<td>' .$rolefromdb .'</td>';
            $lines .= '<td>' .$titlefromdb ."</td>\n";
            $lines .= "</tr> \n";
        }
        if(!empty($lines)) {
            $notice = "<table> \n";
            $notice .= "<tr> \n";
            $notice .= '<th>Isiku nimi
            &nbsp;<a href="?sortby=3&sortorder=1">&uarr;</a>
            &nbsp;<a href="?sortby=3&sortorder=2">&darr;</a></th>' ."\n";
            $notice .= '<th>Roll filmis
            &nbsp;<a href="?sortby=2&sortorder=1">&uarr;</a>
            &nbsp;<a href="?sortby=2&sortorder=2">&darr;</a></th>' ."\n";
            $notice .= '<th>Film 
            &nbsp;<a href="?sortby=4&sortorder=1">&uarr;</a>
            &nbsp;<a href="?sortby=4&sortorder=2">&darr;</a> </th>' ."\n";
            $notice .= "</tr> \n";
            $notice .= $lines;
            $notice .= "</table> \n";
        }
        $stmt->close();
        $conn->close();
        return $notice;
    }
    
    //vana
    function old_readpersonsinfilm() {
        $studionotice = "<p>Kahjuks filmitegelasi ei leitud!<p>\n";
        $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("SELECT first_name, last_name, role, title 
        FROM person 
        JOIN person_in_movie 
        ON person.person_id = person_in_movie.person_id 
        JOIN movie 
        ON movie.movie_id = person_in_movie.movie_id"); // fetching data
        echo $conn->error;
        $stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb);
        $stmt->execute();
        $lines = "";
        while($stmt->fetch()) {
            $lines .= '<p>' .$firstnamefromdb .' ' .$lastnamefromdb;
            if(!empty($rolefromdb)) {
                $lines .= ' tegelane ' .$rolefromdb;
            }
            $lines .= ' on filmis "' .$titlefromdb .'".' ."\n";
        }
        if(!empty($lines)) {
            $notice = $lines;
        }
        $stmt->close();
        $conn->close();
        return $notice;
    }
    
    function readmovietoselect($selected){
        $notice = "<p>Kahjuks filme ei leitud!</p> \n";
        $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("SELECT movie_id, title FROM movie");
        $conn->set_charset("utf8");
        echo $conn->error;
        $stmt->bind_result($idfromdb, $titlefromdb);
        $stmt->execute();
        $films = "";
        while($stmt->fetch()){
            $films .= '<option value="' .$idfromdb .'"';
            if(intval($idfromdb) == $selected){
                $films .=" selected";
            }
            $films .= ">" .$titlefromdb ."</option> \n";
        }
        if(!empty($films)){
            $notice = '<select name="filminput">' ."\n";
            $notice .= '<option value="" selected disabled>Vali film</option>' ."\n";
            $notice .= $films;
            $notice .= "</select> \n";
        }
        $stmt->close();
        $conn->close();
        return $notice;
    }
    
    function listRoles($selectedrole, $selectedfilm) {
        $rolenotice = "";
        $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("SELECT person_in_movie_id, role FROM person_in_movie WHERE movie_id = ?");
        $conn->set_charset("utf8");
        echo $conn->error;
        $stmt->bind_param("i", $selectedfilm);
        $stmt->bind_result($idfromdb, $rolefromdb);
        $stmt->execute();
        $roles = "";
        while($stmt->fetch()){
            $roles .= '<option value="' .$idfromdb .'"';
            if($idfromdb == $selectedrole){
                $roles .=" selected";
            }
            $roles .= ">" .$rolefromdb ."</option> \n";
        }
        if(!empty($roles)){
            $rolenotice = '<select name="filmroleinput">' ."\n";
            $rolenotice .= '<option value="" selected disabled>Vali roll</option>' ."\n";
            $rolenotice .= $roles;
            $rolenotice .= "</select> \n";
        }
        $stmt->close();
        $conn->close();
        return $rolenotice;
    }
