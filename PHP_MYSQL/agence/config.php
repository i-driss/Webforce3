<?php

$dsn = "mysql:host=localhost:3306 ; dbname=wf3_php_intermediaire_prenom; charset=UTF8";
$user = "root";
$password = "";

try{
    $bdd = new PDO("$dsn" , "$user", "$password");

}catch(PDOException $Exception){
    echo 'Erreur de connexion' .$Exception->getMessage();
}

$bdd->exec('SET NAMES UTF8');
?>