<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Pictures;

class PicturesController extends AbstractController
{
    public function index(): Response
    {
        // C'est un peu l'équivalent du "new PDO()"
        $em = $this->getDoctrine()->getManager();

        $errors = []; // Je fais une variable $errors qui contiendra toutes mes erreurs
        if(!empty($_POST)){

            // Permet de nettoyer les données 
            $safe = array_map('trim', array_map('strip_tags', $_POST));


            if(strlen($safe['title']) < 3 || strlen($safe['title']) > 60){
                $errors[] = 'Votre titre doit comporter entre 3 et 60 caractères';
            }

            // Je m'assure que le formulaire est intègre et qu'un fichier a bien été ajouter
            if(isset($_FILES['picture']) && !empty($_FILES['picture']) && $_FILES['picture']['error'] != UPLOAD_ERR_NO_FILE){

                if($_FILES['picture']['error'] != UPLOAD_ERR_OK){
                    // Si l'utilisateur tombe sur cette erreur.. il y a un problème de configuration du server (xampp, wamp, hébergeur)
                    $errors[] = 'Une erreur est survenue lors du transfert de l\'image'; 
                }
                else {

                    $maxSize = 3 * 1000 * 1000; // Me donnera 3 Mo en octets

                    if($_FILES['picture']['size'] > $maxSize){
                        $errors[] = 'L\'image est trop volumineuse';
                    }
                    else {
                        // Le poids est ok, je vérifie le type (c'est à dire que je m'assure que mon image soit bien une image)

                        $allowMimesTypes = ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'image/gif', 'image/webp'];
                        if(!in_array($_FILES['picture']['type'], $allowMimesTypes)){
                            $errors[] = 'Le type de fichier est invalide';
                        }
                    }
                }
                
            } // endif isset($_FILES['picture']) && !empty($_FILES['picture']) && $_FILES['picture']['error'] != UPLOAD_ERR_NO_FILE
            else {
                $errors[] = 'Vous devez sélectionner une image';
            }

            // Ici je compte les erreurs .. s'il est égal à 0, cela veut dire que tout est OK
            if(count($errors) === 0){

                // Sauvegarde du fichier physique
                /*
                  $_SERVER['DOCUMENT_ROOT'] : cette variable va me donner le chemin absolu jusqu'au répertoire "public"
                  C'est le répertoire par le quel on accède au site. Les autres, ne sont pas accessibles dans un navigateur web
                */
                if($_FILES['picture']['error'] === UPLOAD_ERR_OK){
                    // Le dossier qui contiendra mon image finale
                    $rootPublic = $_SERVER['DOCUMENT_ROOT']; // Chemin jusqu'à "public"                    
                    $publicOutput = 'assets/uploads/pictures/'; // Chemin à partir de public
                    $dirOutput = $rootPublic.$publicOutput;

                    // Me retournera l'extension à l'aide de la fonction pathinfo() basée sur le nom de fichier
                    // $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);

                    // Me retournera l'extension à l'aide d'un switch basée sur le mime type
                    switch ($_FILES['picture']['type']) {
                        case 'image/jpg':
                        case 'image/jpeg':
                        case 'image/pjpeg':
                            $extension = 'jpg';
                        break;

                        case 'image/png':
                            $extension = 'png';
                        break;

                        case 'image/gif':
                            $extension = 'gif';
                        break;

                        case 'image/webp':
                            $extension = 'webp';
                        break;
                    }

                    // Génèrera un nom de fichier type : "4b34055024223.png"
                    $filename = uniqid().'.'.$extension;

                    // Le premier paramètre de la fonction move_uploaded_file() c'est le fichier temporaire 
                    // $dirOutput.$filename => C:/xampp/htdocs/symfony_project/public/assets/uploads/pictures/4b34055024223.png
                    if(!move_uploaded_file($_FILES['picture']['tmp_name'], $dirOutput.$filename)){
                        die('Erreur d\'upload fichier Images');
                    }
                }

                // Sauvegarde en base données

                // Je prépare mes données
                $picture = new Pictures();
                $picture->setTitle($safe['title']);
                $picture->setFilename($publicOutput.$filename);
                $picture->setCreatedAt(new \DateTime('now'));

                $em->persist($picture);
                $em->flush();
                // Ca y est ! Youpi !

                $this->addFlash('success', 'Votre image a été ajouté avec succès');

            } // endif count($errors) === 0
            else {

                $this->addFlash('danger', implode('<br>', $errors));

            }

        } // endif !empty($_POST)

        // Récupération de toutes les données de la table "pictures"
        $pictures = $em->getRepository(Pictures::class)->findAll();

        return $this->render('pictures/index.html.twig', [
            'pictures' => $pictures,
        ]);
    }
}
