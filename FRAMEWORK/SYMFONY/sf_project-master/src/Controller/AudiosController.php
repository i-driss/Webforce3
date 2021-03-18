<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Audios;

class AudiosController extends AbstractController
{

    /**
     * Liste des chansons en bdd
     */
    public function list(): Response
    {
        // L'entityManager qui permet de se connecter à la bdd
        $em = $this->getDoctrine()->getManager();


        // Accès à la methode "findAll" du repository pour récupérer toutes les données de la table "audios"
        $audios = $em->getRepository(Audios::class)->findAll();

        return $this->render('audios/list.html.twig', [
            'audios' => $audios,
        ]);
    }

    /**
     * Permet l'ajout d'une chanson
     */
    public function add(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $errors = []; // Je fais une variable $errors qui contiendra toutes mes erreurs
        if(!empty($_POST)){
            $safe = array_map('trim', array_map('strip_tags', $_POST));

            if(strlen($safe['title']) < 3 || strlen($safe['title']) > 60){
                $errors[] = 'Le titre doit comporter entre 3 et 60 caractères';
            }
            
            if(strlen($safe['artist']) < 1 || strlen($safe['artist']) > 100){
                $errors[] = 'Le nom de l\'artiste doit comporter entre 3 et 60 caractères';
            }


            if(isset($_FILES['music']) && !empty($_FILES['music']) && $_FILES['music']['error'] != UPLOAD_ERR_NO_FILE){
                
                if($_FILES['music']['error'] != UPLOAD_ERR_OK){
                    // Si l'utilisateur tombe sur cette erreur.. il y a un problème de configuration du server (xampp, wamp, hébergeur)
                    $errors[] = 'Une erreur est survenue lors du transfert du fichier audio'; 
                }
                else {

                    $maxSize = 7 * 1000 * 1000; // Me donnera 7 Mo en octets
                    if($_FILES['music']['size'] > $maxSize){
                        $errors[] = 'Le fichier audio est est trop volumineux - 7Mo maximum';
                    }
                    else {
                        // Le poids est ok, je vérifie le type (c'est à dire que je m'assure que mon image soit bien une image)

                        $allowMimesTypes = ['audio/mp3', 'audio/mpeg', 'audio/mpeg3', 'audio/x-mpeg-3'];
                        if(!in_array($_FILES['music']['type'], $allowMimesTypes)){
                            $errors[] = 'Le type de fichier n\'est pas pris en charge (mp3 seulement)';
                        }
                    }
                } 
            } // endif isset($_FILES['music']) && !empty($_FILES['music']) && $_FILES['music']['error'] != UPLOAD_ERR_NO_FILE)
            else {
                $errors[] = 'Vous devez sélectionner un fichier audio mp3';
            }

            // Ici je compte les erreurs .. s'il est égal à 0, cela veut dire que tout est OK
            if(count($errors) === 0){
                if($_FILES['music']['error'] === UPLOAD_ERR_OK){
                    // Le dossier qui contiendra mon mp3 finale
                    $rootPublic = $_SERVER['DOCUMENT_ROOT']; // Chemin jusqu'à "public" 
                    $publicOutput = 'assets/uploads/audios/'; // Chemin à partir de public
                    $dirOutput = $rootPublic.$publicOutput;

                    // Fabrique le dossier d'upload si il n'est pas existant
                    if(!is_dir($dirOutput)){
                        mkdir($dirOutput, 0777);
                    }

                    $filename = uniqid().'.mp3';

                    if(!move_uploaded_file($_FILES['music']['tmp_name'], $dirOutput.$filename)){
                        die('Erreur d\'upload fichier MP3');
                    }
                }

                // Sauvegarde SQL
                // Je prépare mes données
                $audio = new Audios();
                $audio->setTitle($safe['title']);
                $audio->setArtist($safe['artist']);
                $audio->setSoundfile($publicOutput.$filename);

                $em->persist($audio);
                $em->flush();

                $this->addFlash('success', 'Votre fichier MP3 a été ajouté avec succès');

            } // endif count($errors) === 0
            else {
                $this->addFlash('danger', implode('<br>', $errors));
            }
        } // endif (!empty($_POST))


        return $this->render('audios/add.html.twig', [
            'controller_name' => 'AudiosController',
        ]);
    }

    public function view(int $id): Response
    {
         // L'entityManager qui permet de se connecter à la bdd
        $em = $this->getDoctrine()->getManager();


        // Accès à la methode "find()" du repository pour récupérer UNE ligne spécifique à un ID
        $audio = $em->getRepository(Audios::class)->find($id);
        

        return $this->render('audios/view.html.twig', [
            'audio' => $audio,
        ]);
    }
}
