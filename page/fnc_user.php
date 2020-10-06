<?php
    $database = "if20_tammeoja_1";
    
    function signup($firstname, $lastname, $email, $gender, $birthdate, $password) {
        $notice = null;
        $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES (?,?,?,?,?,?)");
        echo $conn->error;

        //krüpteerime salasõna
        $options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
        $pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);

        $stmt->bind_param("sssiss", $firstname, $lastname, $birthdate, $gender, $email, $pwdhash);

        if($stmt->execute()) {
            $notice = "ok";
        }
        else {
            $notice = $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $notice;
    }

    function signin($email, $password) {
        $notice = null;
        $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("SELECT password FROM vpusers WHERE email = ?");
        echo $conn->error;
        $stmt->bind_param("s", $email);
        $stmt->bind_result($passwordfromdb);

        if($stmt->execute()) {
            //kui tehniliselt korras
            if($stmt->fetch()) {
                //kasutaja leiti
                if(password_verify($password, $passwordfromdb)) {
                    //parool õige
                    $stmt->close();

                    //loen sisseloginud kasutaja infot
                    $stmt = $conn->prepare("SELECT vpusers_id, firstname, lastname FROM vpusers WHERE email = ?");
                    echo $conn->error;
                    $stmt->bind_param("s", $email);
                    $stmt->bind_result($idfromdb, $firstnamefromdb, $lastnamefromdb);
                    $stmt->execute();
                    $stmt->fetch();
                    //salvestame sesiooni muutujad
                    $_SESSION["userid"] = $idfromdb;
                    $_SESSION["userfirstname"] = $firstnamefromdb;
                    $_SESSION["userlastname"] = $lastnamefromdb;

                    //värvid tuleb lugeda profiilist, kui see on olemas
                    $_SESSION["userbgcolor"] = "#FFFFFF";
                    $_SESSION["usertxtcolor"] = "#000066";

                    $stmt->close();
                    $conn->close();
                    header("Location: home.php");
                    exit();
                } else {
                    //vale
                    $notice = "Vale salasõna!";
                }
            } else {
                $notice = "Sellist kasutajat (" .$email .") ei leitud!";
            }
        } else {
            //kui kõik on putsis
            $notice = $stmt->error;
        }
        $stmt->close();
        $conn->close();
        return $notice;
    }

    function storeuserprofile($description, $bgcolor, $txtcolor) {
        $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
        $stmt = $conn->prepare("SELECT vpuserprofiles_id FROM vpuserprofiles WHERE userid = ?");
        echo $conn->error;
        $stmt->bind_param("i", $_SESSION["userid"]);
        $stmt->bind_result($profileid);
        $stmt->execute();
        if($stmt->fetch()) {
            $stmt->close();
            $stmt = $conn->prepare("UPDATE vpuserprofiles SET description = ?, bgcolor = ?, txtcolor = ? WHERE userid = ?");
            $stmt->bind_param("sssi", $_POST["descriptioninput"], $_POST["bgcolorinput"], $_POST["txtcolorinput"],$_SESSION["userid"]);
            $stmt->execute();
        }
        else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO vpuserprofiles (userid, description, bgcolor, txtcolor) VALUES (?,?,?,?)");
            $stmt->bind_param("isss", $_SESSION["userid"], $_POST["descriptioninput"], $_POST["bgcolorinput"], $_POST["txtcolorinput"]);
            $stmt->execute();
        }
        $conn->close();
    }

        //SQL
        //kontrollime, kas äkki on profiil olemas
        // SELECT vpuserprofiles_id FROM vpuserprofiles WHERE userid = ?X
        //küsimärk asendada väärtusega
        //$_SESSION["userid"]

        //Kui profiili pole olemas, siis loome
        //INSERT INTO vpuserprofiles (userid, description, bgcolor, txtcolor) VALUES (?,?,?,?)

        //kui profiil on olemas, siis uuendame
        //UPDATE vpuserprofiles SET description = ?, bgcolor = ?, txtcolor = ? WHERE userid = ?

        //execute jms või loomisel/uuendamisel ühine olla

    function readuserdescription() {
        //kui profiil on olemas, loeb kasutaja lühitutvustuse
    }
