<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

class PaiementController extends AbstractController
{

    public function paiement(): Response
    {
        if(!empty($_POST)){

            $safe = array_map('trim', array_map('strip_tags', $_POST));
           
            $errors = [];
            
            if(!v::length(5, 120)->validate($safe['titulary'])){ // validation titulaire de la carte
                $errors[] = 'Votre nom de titulaire doit comporter entre 5 et 120 caractères';
            }
            if(!v::length(12)->validate($safe['numbcard'])){ // validation numero carte
                $errors[] = 'Votre numéro de carte comporte 12 chiffres.';
            }
            if(!v::length(3)->validate($safe['crypto'])){ // validation cryptogramme
                $errors[] = 'Votre cryptogramme comporte 3 chiffres. Il se trouve derrière votre carte bancaire.';
            }


            if(count($errors) === 0){


            $_POST = array();
                // On crée le message flash
                $this->addFlash('success', 'Votre paiement a été accepté.');
            }else{

                $this->addFlash('danger', implode('<br>', $errors));

            }

        
    }
    return $this->render('paiement/paiement.html.twig', [
        'paiement_controller' => 'PaiementController',
        ]);
}
}
