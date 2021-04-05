<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Respect\Validation\Validator as v;
use App\Entity\User;


class DefaultController extends AbstractController
{
    #[Route('/default', name: 'default')]
    public function home(): Response
    {
        return $this->render('default/home.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    public function mentions(): Response
    {
        return $this->render('default/mentions.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    public function support(MailerInterface $mailer): Response
    {

        if(!empty($_POST)){
            // Permet de nettoyer les données
            $safe= array_map('trim', array_map('strip_tags', $_POST));
            // les validations de formulaires

            $errors = [];
            /**
             * vérification à l'aide du package respect/validation
             * @see : https://respect-validation.readthedocs.io/en/latest/
             */

            // Le prénom doit faire au moins 1 caractère
            if(!v::notEmpty()->length(1, null)->validate($safe['firstname'])){
                $errors[] = 'Votre prénom doit comporter au moins 1 carectère ';
            }

            // Le nom doit faire au moins 1 caractère
            if(!v::notEmpty()->length(1, null)->validate($safe['lastname'])){
                $errors[] = 'Votre nom doit comporter au moins 1 carectère ';
            }

            // l'email ne doit pas être vide et doit être en format email
            if(!v::notEmpty()->email()->validate($safe['email'])){
                $errors[] = 'Votre sujet doit comporter entre 5 et 30 caractère';
            }

            // Le sujet doit faire au moins 5 caractères et 30
            if(!v::notEmpty()->length(5, 30)->validate($safe['subject'])){
                $errors[] = 'Votre sujet doit comporter entre 5 et 30 caractère';
            }

            // Longueur mini sans longueur maxi pour le contenu
            if(!v::notEmpty()->length(5, null)->validate($safe['message'])){
                $errors[] = 'Votre contenu doit comporter au moins 5 caractères';

            }

            $email = (new Email())
            ->from($safe['email']) // expéditeur
            ->to('contact@mystartsf.com') // destinataire
            //->cc('cc@example.com') // copie
            //->bcc('bcc@example.com') // copie cachée
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Message portant sur : '. $safe['subject'])
            ->text('Nouveau message venant du site Dev\'Loc : '. $safe['message'])
            ->html('<p>Nouveau message venant du site Dev\'Loc : <br><br>'. $safe['message'].'</p>');

            $mailer->send($email);
        }

        return $this->render('default/support.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    public function funding(): Response
    {
        return $this->render('default/funding.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    public function homeConnexion(): Response
    {
        $devs = $this->getDoctrine()->getRepository(User::class)->findBy(array('status' => 'developer','verified' => '1'));
        $status = $this->getUser()->getStatus();
        if($this->getUser() && $status === "shopkeeper"){
            
            return $this->render('default/accueilShop.html.twig', [
                'controller_name' => 'DefaultController',
                'devs' => $devs,
                'status' => $status,
                'page' => 'acceuil',
            ]);            
        }elseif($this->getUser() && $status === "developer"){
            $user = $this->getUser();
            return $this->render('default/accueilDev.html.twig', [
                'controller_name' => 'DefaultController',
                'status' => $status,
                'page' => 'accueil',
                'user' => $user,
                'missions' => '',
            ]);            
        }else{

            return $this->render('default/home.html.twig', [
                'controller_name' => 'DefaultController',
            ]);
        }
    }
    
}

