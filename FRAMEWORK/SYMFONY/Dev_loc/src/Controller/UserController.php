<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\HttpFoundation\Session\Session;


class UserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function register(LoginFormAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler, Request $request): Response
    {   
        $session = $request->getSession();
        if(!isset($session)){
            $session = new Session();
            $session->start();
        }
        // set and get session attributes
        if(!empty($_GET["status"])){
            $session->set('status', $_GET['status']);
        
        }else{
            return $this->redirectToRoute('home_controller');
        }
        // User connecté, redirection vers la home
        if($this->getUser()){
            return $this->redirectToRoute('user_register_profil', ['rubrique' => '2']);
        }else{
            
            
            $errors = [];
    
            if(!empty($_POST['email'])){
                $safe = array_map('trim', array_map('strip_tags', $_POST));
                $status = $_GET['status'];
    
                if(!v::notEmpty()->email()->validate($safe['email'])){ // Validation email
                    $errors[] = 'Votre email est invalide';
                }
    
                if(!v::length(8,20)->validate($safe['password'])){ // validation mot de passe
                    $errors[] = 'Votre mot de passe doit comporter entre 8 et 20 caractères';
                }
                if(!v::equals($safe['password'])->validate($safe['confirmPassword'])){
                    $errors[] = 'Vos mots de passe ne correspondent pas';
                }
    
                if(count($errors) === 0){
    
                    $em = $this->getDoctrine()->getManager();
                    $user = new User;
                    $availabe = true;
                    $user->setEmail($safe['email']);
                    $user->setStatus($status);
                    $user->setAvailabe($availabe);
                    $user->setPassword($this->passwordEncoder->encodePassword(
                        $user,
                        $safe['password']
                    ));
    
                    $em->persist($user);
                    $em->flush();
                    $_POST = array();
                    return $guardHandler->authenticateUserAndHandleSuccess(
                        $user,          // the User object you just created
                        $request,
                        $authenticator, // authenticator whose onAuthenticationSuccess you want to use
                        'main'          // the name of your firewall in security.yaml
                    );
                } // endif count($errors) === 0
                else {
                    $this->addFlash('danger', implode('<br>', $errors));
                }
    
            }
        }
        return $this->render('user/register.html.twig', [
            'controller_name' => 'UserController',
            'rubrique' => '1',
        ]);
    
    }//Fin function register
    
    public function registerProfil(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $status = $this->getUser()->getStatus();
        // User connecté
        if($this->getUser() && $status === "developer" ){

            if(empty($this->getUser()->getPresentation())){
                
                $errors = [];
        
                if(!empty($_POST)){
                    $safe = array_map('trim', array_map('strip_tags', $_POST));
        

        
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
                    else {
                        $errors[] = 'Vous devez sélectionner une image';
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
                    
        
                    if(count($errors) === 0){
        
                        if($_FILES['profilPicture']['error'] === UPLOAD_ERR_OK){
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
        
                            }
        
                            $filename = uniqid().'.'.$extension;
                            
                            if(!move_uploaded_file($_FILES['profilPicture']['tmp_name'], $dirOutput.$filename)){
                                die('Erreur d\'upload fichier Images');
                            }
                        }
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
        
                            if(!move_uploaded_file($_FILES['cv']['tmp_name'], $dirOutputCv.$filenameCv)){
                                die('Erreur d\'upload fichier CV');
                            }
                        }
                        $user = $this->getUser();
                        $user->setProfilPicture($publicOutput.$filename);
                        $user->setPresentation($safe['presentation']);
                        $user->setSkill($safe['skill']);
                        $user->setMovie($safe['movie']);
                        $user->setMusic($safe['music']);
                        $user->setCv($publicOutputCv.$filenameCv);
                        
        
                        $em->persist($user);
                        $em->flush();
                        
                        return $this->redirectToRoute('user_register_contact', ['rubrique' => '3']);
                    }else {
                        $this->addFlash('danger', implode('<br>', $errors));
                    } // endif count($errors) === 0
                }
                return $this->render('user/registerProfil.html.twig', [
                    'controller_name' => 'UserController',
                    'rubrique' => '2',
                    
                ]);
            }else{
                return $this->redirectToRoute('user_register_contact' , ['rubrique' => '3']);
            }
    
        }elseif($this->getUser() && $status === "shopkeeper" ){

            if(empty($this->getUser()->getPackage())){
                
                $errors = [];
                if(!empty($_POST)){
                    $safe = array_map('trim', array_map('strip_tags', $_POST));
                    
                    if(!v::length(30, 1000)->validate($safe['details'])){ // Validation email
                        $errors[] = 'Le détail de votre projet doit comporter entre 20 et 1000 caractères';
                    }
                    if(isset($safe['package'])){
                        if(!v::containsAny(['basic','advanced','prenium'])->validate($safe['package'])){
                            $errors[] = 'Vous devez choisir une offre de projet (Basique,Avancé,Prenium) aaa';
                        }
                    }elseif(!isset($safe['package'])){
                        $errors[] = 'Vous devez choisir une offre de projet (Basique,Avancé,Prenium)';
                    }
                    
                    if(count($errors) === 0){
                        
                        
                        $user = $this->getUser();
                        $user->setPackage($safe['package']);
                        $user->setDetails($safe['details']);
                        
                        $em->persist($user);
                        $em->flush();
                        
                        return $this->redirectToRoute('user_register_contact', ['rubrique' => '3']);
                    } // endif count($errors) === 0
                    else {
                        $this->addFlash('danger', implode('<br>', $errors));
                    }
                }
            return $this->render('user/registerProjet.html.twig', [
                'controller_name' => 'UserController',
                ]);
             }else{
                return $this->redirectToRoute('user_register_contact', ['rubrique' => '3']);
                   }//Fin if getUser()->getPackage()

             }else{
            return $this->render('user/register.html.twig', [
                'controller_name' => 'UserController',
            ]);
        }//Fin si user est connecté 
   }//Fin function registerProfil

    public function registerContact(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $status = $this->getUser()->getStatus();
        // User connecté
        if($this->getUser() && $status === "developer" ){
            if(empty($this->getUser()->getName())){
                $errors = [];
        
                if(!empty($_POST)){
                    $safe = array_map('trim', array_map('strip_tags', $_POST));

                    if(!v::stringType()->length(2, 30)->validate($safe['name'])){ // Validation email
                        $errors[] = 'Veuillez renseigner votre nom';
                    }
                    if(!v::stringType()->length(2, 30)->validate($safe['firstname'])){ // Validation email
                        $errors[] = 'Veuillez renseigner votre prénom';
                    }
                    if(!v::date('Y-m-d')->validate($safe['birthday'])){ // Validation email
                        $errors[] = 'Veuillez votre date de naissance';
                    }elseif(!v::minAge(18, 'Y-m-d')->validate($safe['birthday'])){
                        $errors[] = 'Vous devez avoir au moins 18 ans pour vous inscrire';
                    }
                    if(!v::postalCode('FR')->validate($safe['postal_code'])){ // Validation email
                        $errors[] = 'Veuillez renseigner un code postal valide';
                    }elseif(substr($safe['postal_code'], 0, 2) !== "31"){
                        $errors[] = 'Vous devez résider dans les environs de toulouse pour vous inscrire';
                    }
                    if(!empty($safe['adress'])){
                        if(!v::length(2, 50)->validate($safe['adress'])){ // Validation email
                            $errors[] = 'veuillez renseigner une adresse valide';
                        }
                    }   
                    if(!empty($safe['city'])){
                        if(!v::length(2, 30)->validate($safe['city'])){ // Validation email
                            $errors[] = 'veuillez renseigner une ville valide';
                        }
                    }   

                    if(count($errors) === 0){
    
                        $user = $this->getUser();
                        $user->setName($safe['name']);
                        $user->setFirstname($safe['firstname']);
                        $user->setBirthday(new \DateTime($safe['birthday']));
                        $user->setAdress($safe['adress']);
                        $user->setCity($safe['city']);
                        $user->setPostalcode($safe['postal_code']);
                        $user->setVerified(true);
        
                        $em->persist($user);
                        $em->flush();
                        $_POST = array();
                        return $this->redirectToRoute('home_connexion_controller');
                    } // endif count($errors) === 0
                    else {
                        $this->addFlash('danger', implode('<br>', $errors));
                    }
                }
                return $this->render('user/registerContact.html.twig', [
                    'controller_name' => 'UserController',
                ]);
                
            }else{
                return $this->redirectToRoute('home_connexion_controller');
            }
        }elseif($this->getUser() && $status === "shopkeeper" ){
            if(empty($this->getUser()->getName())){
                $errors = [];
        
                if(!empty($_POST)){
                    $safe = array_map('trim', array_map('strip_tags', $_POST));

                    if(!v::stringType()->length(2, 30)->validate($safe['name'])){ // Validation name
                        $errors[] = 'Veuillez renseigner votre nom';
                    }
                    if(!v::stringType()->length(2, 30)->validate($safe['firstname'])){ // Validation firstname
                        $errors[] = 'Veuillez renseigner votre prénom';
                    }
                    if(!v::stringType()->length(2, 30)->validate($safe['social_entity'])){ // Validation social entity
                        $errors[] = 'Veuillez renseigner la raison sociale de votre entreprise';
                    }
                    if(!v::postalCode('FR')->validate($safe['postal_code'])){ // Validation postal code
                        $errors[] = 'Veuillez renseigner un code postal valide';
                    }elseif(substr($safe['postal_code'], 0, 2) !== "31"){
                        $errors[] = 'Vous devez résider dans les environs de toulouse pour vous inscrire';
                    }
                    if(!empty($safe['city'])){
                        if(!v::length(2, 30)->validate($safe['city'])){ // Validation city
                            $errors[] = 'veuillez renseigner une ville valide';
                        }
                    }   

                    if(count($errors) === 0){
    
                        $user = $this->getUser();
                        $user->setName($safe['name']);
                        $user->setFirstname($safe['firstname']);
                        $user->setCity($safe['city']);
                        $user->setPostalcode($safe['postal_code']);
                        $user->setSocialEntity($safe['social_entity']);
                        $user->setVerified(true);
        
                        $em->persist($user);
                        $em->flush();
                        $_POST = array();
                        return $this->redirectToRoute('home_connexion_controller');
                    } // endif count($errors) === 0
                    else {
                        $this->addFlash('danger', implode('<br>', $errors));
                    }
                }
                return $this->render('user/registerInfo.html.twig', [
                    'controller_name' => 'UserController',
                ]);
                
            }else{
                return $this->redirectToRoute('home_connexion_controller');
            }
        }else{
            return $this->render('user/register.html.twig', [
                'controller_name' => 'UserController',
            ]);
        }//Fin si user est connecté 

   }//Fin function registerContact

}


