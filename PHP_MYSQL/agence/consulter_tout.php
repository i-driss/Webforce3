<?php
    require_once "config.php";   

    $query = $bdd->prepare(
        "SELECT 
         id,
         title,
         description,
         postal_code,
         city,
         type,
         price,
         reservation_message
         FROM advert
         ORDER BY id
         
         "
         );
    
         $query->execute();

         $annonces= $query->fetchAll(PDO::FETCH_ASSOC);

    include "consulter_tout.html";
?>