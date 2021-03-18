<?php
    require_once "config.php";  

    $query = $bdd->prepare(
        "SELECT *
         FROM advert
         WHERE id = ?
         
         "
         );
    
         $query->execute([$_GET['annonce']]);

         $details= $query->fetch(PDO::FETCH_ASSOC);

         if(empty($_POST['reservation_message'])==false && empty($details['reservation_message'])){
                
            
            $query= $bdd->prepare(
                "UPDATE advert
                 SET reservation_message = ?
                 WHERE id = ?
                ");
            var_dump($_POST);
        
    
            $query->execute([$_POST['reservation_message'], $_GET['annonce']]);
            $_POST = array();
            var_dump($query);
            
            }
    include "consulter_annonce.html";
?>