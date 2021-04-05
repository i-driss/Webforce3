<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Respect\Validation\Validator as v;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class AccountController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        
    }

    public function delete(TokenStorageInterface$tokenStorage): Response
    {
        if($this->getUser()){
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $tokenStorage->setToken(null);
            $em->remove($user);
            $em->flush();
            return $this->redirectToRoute('app_logout');
        }
        return $this->redirectToRoute('home_controller');
    }
    public function account(): Response
    {
        if($this->getUser()){
            $em = $this->getDoctrine()->getManager();
            $status = $this->getUser()->getStatus();
            $user = $this->getUser();
        
            if(!empty($_POST)){
                $errors = [];

                $safe = array_map('trim', array_map('strip_tags', $_POST));


                if(!empty($safe['email'])){
                    if(!v::notEmpty()->email()->validate($safe['email'])){ // Validation email
                        $errors[] = 'Votre email est invalide';
                    }
                }
                if(!empty($safe['password'])){
                    if(!v::notEmpty()->length(8,20)->validate($safe['password'])){
                        $errors[] = 'Votre mot de passe doit comporter entre 8 et 20 caractères';
                    }
                }
                if(!empty($safe['password'])){
                    if(!v::equals($safe['password'])->validate($safe['confirmPassword'])){
                        $errors[] = 'Vos mots de passe ne correspondent pas';
                    }
                }
                

                if(count($errors) === 0){
                    if(!empty($safe['email'])){
                        $user->setEmail($safe['email']);
                    }
                    if(!empty($safe['password'])){
                        $user->setPassword($this->passwordEncoder->encodePassword(
                            $user,
                            $safe['password']
                        ));
                    }

                    $em->persist($user);
                    $em->flush();
                    $_POST = array();
                    $this->addFlash('success', 'Compte modifié avec succès');
                }else {
                    $this->addFlash('danger', implode('<br>', $errors));
                }
             }
            $status = $this->getUser()->getStatus();
            $user = $this->getUser();
            if($this->getUser() && $status === "shopkeeper"){
                
                return $this->render('account/account.html.twig', [
                    'controller_name' => 'AccountController',
                    'status' => $status,
                    'page' => 'account',
                    'user' => $user,
                ]);            
            }elseif($this->getUser() && $status === "developer"){
                $user = $this->getUser();
                return $this->render('account/account.html.twig', [
                    'controller_name' => 'AccountController',
                    'status' => $status,
                    'page' => 'account',
                    'user' => $user,
                ]);            
            }
        }else{
    
            return $this->redirectToRoute('home_controller');
            }
          
        }
}
