<?php

namespace App\Controller;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Repository\ChatRepository;
use App\Entity\User;
use App\Entity\Chat;




class ProjetController extends AbstractController
{

    // public function add(): Response
    // {
    //     // Vérification de la méthode
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         // Ici, la méthode est en POST
    //         // Vérification de la connexion de l'utilisateur
    //         if ($this->getUser()) {
    //             // Ici, l'utilisateur est connecté

    //             // Récupération du message
    //             $donneesJson = file_get_contents('php://input');

    //             // Conversion JSON vers Objet PHP
    //             $donnees = json_decode($donneesJson);
    //             // Vérification de l'éxistence d'un message
    //             if (isset($donnees->message) && !empty($donnees->message)) {
    //                 // Ici, on a un message


                        // Celui avec qui je discute
                        // $id_destinataire = (int) $donnees->recipient_id; // valeur id qui provient de l'ajax
                        // user ->find($id_destin)
                    
    //                 // Connexion à la base de données
    //                 $em = $this->getDoctrine()->getManager();

    //                 // Préparation de la requête
    //                 $message = new Chat();
    //                 $message->setMessage($donnees->message);
    //                 $message->setCreatedAt(new \DateTime('now'));
    //                 $message->setSendFrom($this->getUser());

    //                 // Stockage de la requête
    //                 $em->persist($message);
    //                 // Execution de la requête avec vérification
    //                 if ($em->flush()) {
    //                     http_response_code(200);
    //                     echo json_encode(['message' => 'Enregistrement effectué']);
    //                 } else {
    //                     http_response_code(400);
    //                     echo json_encode(['message' => 'Une erreur est survenue']);
    //                 }
    //             } else {
    //                 // Ici, nous n'avons pas de message
    //                 http_response_code(400);
    //                 echo json_encode(['message' => 'Veuillez remplir le champ message.']);
    //             }
    //         } else {
    //             // Ici, nous ne sommes pas connectés
    //             http_response_code(400);
    //             echo json_encode(['messages' => 'Veuillez vous connecter pour envoyer votre message.']);
    //         }
    //     } else {
    //         // Ici, la méthode n'est pas la bonne
    //         http_response_code(405);
    //         echo json_encode(['message' => 'La méthode utilisée ne convient pas.']);
    //     }

    //     return $this->render('message/index.html.twig', [
    //         'controller_name' => 'MessageController',
    //     ]);
    // }

    

    public function index(): Response
    {
        if($this->getUser()){
            
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $status = $this->getUser()->getStatus();
            $contactsId = $this->getDoctrine()->getRepository(Chat::class)->findContact($user->getId()) ;
            
            $contacts = $this->getDoctrine()->getRepository(User::class)->findBy(['id' => $contactsId]);
            if($status === "developer"){
                if(empty($contacts)){
                    $contact = 1;
                }else{
                    $contact = 0;
                }
            }else{
                $contact = 0;
            }
            if(isset($_GET['user'])){
                $recipient = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $_GET['user']]);
                $messages = $this->getDoctrine()->getRepository(Chat::class)->findPrivateChats($user, $recipient);
            }else{
                $lastcontact = end($contactsId );
                $recipient = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $lastcontact]);
                $messages = $this->getDoctrine()->getRepository(Chat::class)->findPrivateChats($user, $recipient);

            }
            if(!empty($_POST)){
                $safe = array_map('trim', array_map('strip_tags', $_POST));
                $message = new Chat;
                $message->setMessage($safe['message']);
                $message->setCreatedAt(new \DateTime('now'));
                $message->setSendFrom($user);
                $message->setSendTO($recipient);
                
                
                $em->persist($message);
                $em->flush();
                $_POST = array();
                $messages = $this->getDoctrine()->getRepository(Chat::class)->findPrivateChats($user, $recipient);
    
            }
            if($this->getUser() && $status === "shopkeeper"){
                
                return $this->render('projet/index.html.twig', [
                    'controller_name' => 'ProjetController',
                    'status' => $status,
                    'page' => 'projet',
                    'recipient'  => $recipient ?? "0",
                    'user' => $user,
                    'messages' => $messages ?? "",
                    'contacts' => $contacts ?? "",
                    'emptycontact' => $contact ,
                ]);   
                          
            }elseif($this->getUser() && $status === "developer"){
                           
            }
            return $this->render('projet/index.html.twig', [
                'controller_name' => 'ProjetController',
                'status' => $status,
                    'page' => 'projet',
                    'recipient'  => $recipient ?? "0",
                    'user' => $user,
                    'messages' => $messages ?? "",
                    'contacts' => $contacts ?? "",
                    'emptycontact' => $contact ,
            ]);
        }else{
            return $this->redirectToRoute('home_controller');
        }
    }



    public function add(): Response
    {
        // Réception du message envoyé via ajax. Ajax, appellera cette apge en lui postant des données

        // On insère les données dans la table Chats
        // ajax fera appel a cette méthode
        // Elle renverra du json

        return new JsonResponse(['error' => 'Tentative d\'accès interdit']);
    }


    public function read(): Response
    {
        // Fonction qui liste tous les messages entre l'user connecté et le dev/commercant
        // Ajax fera appel a cet méthode
        // Elle renverra du json





        return new JsonResponse(['error' => 'Tentative d\'accès interdit']);
    }

}


