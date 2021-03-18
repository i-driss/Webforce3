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
         LIMIT 15
         "
         );
    
         $query->execute();

         $annonces= $query->fetchAll(PDO::FETCH_ASSOC);

    include "index.html";
?>