<?php
    $errors = [];


    if(empty($_POST)==false){
    
        if (!isset($_POST['title']) || strlen($_POST['title']) < 1) {
            $errors['title'] = "Vous n'avez pas renseigné le titre";
        }
        
        if (!isset($_POST['description']) || strlen($_POST['description']) < 1) {
            $errors['description'] = "Vous n'avez pas renseigné la description";
        }

        if (!isset($_POST['postal_code']) || strlen($_POST['postal_code']) < 1) {
            $errors['postal_code'] = "Vous n'avez pas renseigné le code postal";
        }

        if (!isset($_POST['city']) || strlen($_POST['city']) < 1) {
            $errors['city'] = "Vous n'avez pas renseigné la ville";
        }

        if (!isset($_POST['price']) || strlen($_POST['price']) < 1) {
            $errors['price'] = "Vous n'avez pas choisi le prix";
        }
        
        
    }

    session_start();
    require_once "config.php";   
    var_dump($_POST);
    if(!empty($errors)){
        $_SESSION['errors'] = $errors;
        $_SESSION['inputs'] = $_POST;
    
    }else if(empty($_POST)==false){
                
            
        $query= $bdd->prepare(
            "INSERT INTO advert(title, description, postal_code, city, type, price)
             VALUES (?,?,?,?,?,?)
            ");
        var_dump($_POST);
    

        $query->execute([$_POST['title'], $_POST['description'], $_POST['postal_code'],$_POST['city'],$_POST['type'],$_POST['price']]);
        $_POST = array();
        var_dump($query);
        
        session_destroy();
        header('Location:index.php');
        }
    include "ajouter_annonce.html";
?>