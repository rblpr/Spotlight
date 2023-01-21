<?php
require_once 'bootstrap.php';

if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["first_name"]) && isset($_POST["last_name"])){
    $signup_result = $dbh->checkUser($_POST["username"], $_POST["email"]);
    if(count($signup_result)!=0){
        //Account non disponibile
        $templateParams["signupError"] = "Error! Username or email already used";
    }
    else{
        // Recupero la password criptata dal form di inserimento.
        $password = $_POST['password']; 
        // Crea una chiave casuale
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        // Crea una password usando la chiave appena creata.
        $password = hash('sha512', $password.$random_salt);
        list($result, $msg) = uploadImage(UPLOAD_DIR, $_FILES["propic"]);
        if($result != 0){
            $propic = $msg;
            $dbh->createUser($_POST["username"], $_POST["email"], $password, $random_salt, $_POST["first_name"], $_POST["last_name"], $propic);
            registerLoggedUser($_POST["username"], $_POST["email"]);
            $msg = "Inserimento completato correttamente!";
        }
        else{
            $msg .= "Errore in inserimento!";
        }
    }
}

if(isUserLoggedIn()){
    $templateParams["title"] = "Spotlight - Home";
    require "template/esempioSpotifyAPI.php";
}
else{
    $templateParams["title"] = "Spotlight - Sign-Up";
    require "template/signupForm.php";
}
?>