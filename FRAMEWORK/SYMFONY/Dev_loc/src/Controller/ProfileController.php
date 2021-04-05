<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;

class ProfileController extends AbstractController
{

    public function profile(): Response
    {
        if($this->getUser()){
            
        
        $status = $this->getUser()->getStatus();
        $user = $this->getUser();

        $errors = [];

        if(!empty($_POST)){
            $safe = array_map('trim', array_map('strip_tags', $_POST));

            if(!v::stringType()->length(2, 30)->validate($safe['name'])){ // Validation email
                $errors[] = 'Veuillez renseigner votre nom';
            }
            if(!v::stringType()->length(2, 30)->validate($safe['firstname'])){ // Validation email
                $errors[] = 'Veuillez renseigner votre prénom';
            }
            if(!v::postalCode('FR')->validate($safe['postal_code'])){ // Validation email
                $errors[] = 'Veuillez renseigner un code postal valide';
            }elseif(substr($safe['postal_code'], 0, 2) !== "31"){
                $errors[] = 'Vous devez résider dans les environs de toulouse';
            }
            if(!empty($safe['city'])){
                if(!v::length(2, 30)->validate($safe['city'])){ // Validation email
                    $errors[] = 'veuillez renseigner une ville valide';
                }
            }
            // Verification Image
            if(isset($_FILES['profilPicture']) && !empty($_FILES['profilPicture']) && $_FILES['profilPicture']['error'] != UPLOAD_ERR_NO_FILE){

                if($_FILES['profilPicture']['error'] != UPLOAD_ERR_OK){
                    $errors[] = 'Une erreur est survenue lors du transfert de l\'image'; 
                }
                else {

                    $maxSize = 3 * 1000 * 1000; 

                    if($_FILES['profilPicture']['size'] > $maxSize){
                        $errors[] = 'L\'image est trop volumineuse, maximum 3Mo';
                    }
                    else {

                        $allowMimesTypes = ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png'];
                        if(!in_array($_FILES['profilPicture']['type'], $allowMimesTypes)){
                            $errors[] = 'Le type de fichier est invalide';
                        }
                    }
                }
                
            } // endif isset($_FILES['profilPicture']) && !empty($_FILES['profilPicture']) && $_FILES['profilPicture']['error'] != UPLOAD_ERR_NO_FILE
            
            if($status === "shopkeeper"){
                if(!v::stringType()->length(2, 30)->validate($safe['social_entity'])){ // Validation email
                    $errors[] = 'Veuillez renseigner la raison sociale de votre entreprise';
                }
            }elseif($status === "developer"){
                if(!v::length(30, 1000)->validate($safe['presentation'])){ // Validation email
                    $errors[] = 'Votre présentation doit comporter entre 30 et 1000 caractères';
                }
                if(!v::stringType()->length(2)->validate($safe['skill'])){ // validation mot de passe
                    $errors[] = 'Vous devez indiquer au moins un langage de programmation favori';
                }elseif(!v::stringType()->length(null, 30)->validate($safe['skill'])){
                    $errors[] = 'Le langage de programmation indiqué est trop long';
                }
                if(!v::stringType()->length(null, 30)->validate($safe['movie'])){
                    $errors[] = 'Le titre du film est trop long';
                }
                if(!v::length(null, 30)->validate($safe['music'])){
                    $errors[] = 'Le titre de la musique est trop longue';
                }
                // Verification CV
                if(isset($_FILES['cv']) && !empty($_FILES['cv']) && $_FILES['cv']['error'] != UPLOAD_ERR_NO_FILE){
    
                    if($_FILES['cv']['error'] != UPLOAD_ERR_OK){
                        $errors[] = 'Une erreur est survenue lors du transfert du CV'; 
                    }
                    else {
    
                        $maxSize = 3 * 1000 * 1000; 
    
                        if($_FILES['cv']['size'] > $maxSize){
                            $errors[] = 'Le CV est trop volumineux, maximum 3Mo';
                        }
                        else {
    
                            $allowMimesTypes = ['image/jpeg', 'image/jpg', 'application/pdf', 'image/png'];
                            if(!in_array($_FILES['cv']['type'], $allowMimesTypes)){
                                $errors[] = 'Le type de fichier est invalide';
                            }
                        }
                    }   
                } // endif isset($_FILES['cv']) && !empty($_FILES['cv']) && $_FILES['cv']['error'] != UPLOAD_ERR_NO_FILE
            }
            
            if(count($errors) === 0){
                $em = $this->getDoctrine()->getManager();
                $user = $this->getUser();

                
                if($_FILES['profilPicture']['error'] === UPLOAD_ERR_OK && !empty($_FILES['profilPicture'])){
                    $rootPublic = $_SERVER['DOCUMENT_ROOT']; // Chemin jusqu'à "public"                    
                    $publicOutput = 'asset/uploads/pictures/'; // Chemin à partir de public
                    $dirOutput = $rootPublic.$publicOutput;

                    switch ($_FILES['profilPicture']['type']) {
                        case 'image/jpg':
                        case 'image/jpeg':
                        case 'image/pjpeg':
                            $extension = 'jpg';
                        break;

                        case 'image/png':
                            $extension = 'png';
                        break;
                        case 'image/jfif':
                            $extension = 'jfif';
                        break;

                    }

                    $filename = uniqid().'.'.$extension;
                    
                    if(!move_uploaded_file($_FILES['profilPicture']['tmp_name'], $dirOutput.$filename)){
                        die('Erreur d\'upload fichier Images');
                    }
                    $user->setProfilPicture($publicOutput.$filename);
                }
                if(isset($_FILES['cv'])){
                    if($_FILES['cv']['error'] === UPLOAD_ERR_OK && !empty($_FILES['cv'])){
                        $rootPublicCv = $_SERVER['DOCUMENT_ROOT']; // Chemin jusqu'à "public"                    
                        $publicOutputCv = 'asset/uploads/cv/'; // Chemin à partir de public
                        $dirOutputCv = $rootPublicCv.$publicOutputCv;
    
                        switch ($_FILES['cv']['type']) {
                            case 'image/jpg':
                            case 'image/jpeg':
                            case 'image/pjpeg':
                                $extension = 'jpg';
                            break;
    
                            case 'image/png':
                                $extension = 'png';
                            break;
                            case 'application/pdf':
                                $extension = 'pdf';
                            break;
    
                        }
    
                        $filenameCv = uniqid().'.'.$extension;
                        
                        if(!move_uploaded_file($_FILES['cv']['tmp_name'], $dirOutput.$filename)){
                            die('Erreur d\'upload fichier CV');
                        }
                        $user->setCv($publicOutputCv.$filenameCv);
                    }
                }
                
                
                $user->setName($safe['name']);
                $user->setFirstname($safe['firstname']);
                if(isset($safe['social_entity'])){
                    $user->setSocialEntity($safe['social_entity']);
                }
                if(isset($safe['presentation'])){
                    $user->setPresentation($safe['presentation']);
                }
                if(isset($safe['skill'])){
                    $user->setSkill($safe['skill']);
                }
                if(isset($safe['movie'])){
                    $user->setMovie($safe['movie']);
                }
                if(isset($safe['music'])){
                    $user->setMusic($safe['music']);
                }
                $user->setAdress($safe['adress']);
                $user->setCity($safe['city']);
                $user->setPostalcode($safe['postal_code']);
                $em->persist($user);
                $em->flush();
                $_POST = array();
                $this->addFlash('success', 'Profil modifié avec succès');
                return $this->redirectToRoute('profile_controller');
            } // endif count($errors) === 0
            else {
                $this->addFlash('danger', implode('<br>', $errors));
            }
        }
        if($this->getUser() && $status === "shopkeeper"){
            $user = $this->getUser();
            return $this->render('profile/profile.html.twig', [
                'controller_name' => 'ProfileController',
                'status' => $status,
                'page' => 'profil',
                'user' => $user,
            ]);            
        }elseif($this->getUser() && $status === "developer"){
            $user = $this->getUser();
            return $this->render('profile/profile.html.twig', [
                'controller_name' => 'ProfileController',
                'status' => $status,
                'page' => 'profil',
                'user' => $user,
            ]);            
        }
    }else{

        return $this->redirectToRoute('home_controller');
        }
      
    }
}
