<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;


class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     */
    public function forum(Request $request): Response
    {
        $user = $this->getUser();
        
        

        
    if(isset($_GET['page'])){     

        if($this -> getUser()->getStatus() == 'developer' && $this -> getUser() && $_GET['page'] == 'accueil' ){

        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy([],['created_at' => 'desc']);

        $status = $this->getUser()->getStatus();
        $user = $this->getUser();

        return $this->render('forum/forum.html.twig', [
            'articles' => $articles,
            'status' => $status,
            'page' => 'forum',
            'categorie' => 'accueil',
            'user' => $user,
            ]);

        }elseif($this -> getUser()->getStatus() == 'developer' && $this -> getUser() && $_GET['page'] == 'HTML/CSS' ){

            // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
            
            $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(['category' => '1'],['created_at' => 'desc']);

            $status = $this->getUser()->getStatus();
            $user = $this->getUser();

            return $this->render('forum/forum.html.twig', [
                'articles' => $articles,
                'status' => $status,
                'page' => 'forum',
                'categorie' => 'html/css',
                'user' => $user,
                ]);

            }elseif($this -> getUser()->getStatus() == 'developer' && $this -> getUser() && $_GET['page'] == 'javascript' ){

                // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
            
            $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(['category' => '2'],['created_at' => 'desc']);

            $status = $this->getUser()->getStatus();
            $user = $this->getUser();

            return $this->render('forum/forum.html.twig', [
                'articles' => $articles,
                'status' => $status,
                'page' => 'forum',
                'categorie' => 'javascript',
                'user' => $user,
                ]);

            }elseif($this -> getUser()->getStatus() == 'developer' && $this -> getUser() && $_GET['page'] == 'php' ){

                $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(['category' => '3'],['created_at' => 'desc']);

                $status = $this->getUser()->getStatus();
                $user = $this->getUser();

                return $this->render('forum/forum.html.twig', [
                    'articles' => $articles,
                    'status' => $status,
                    'page' => 'forum',
                    'categorie' => 'php',
                    'user' => $user,
                    ]);
            }elseif($this -> getUser()->getStatus() == 'developer' && $this -> getUser() && $_GET['page'] == 'framework' ){

                $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(['category' => '4'],['created_at' => 'desc']);

                $status = $this->getUser()->getStatus();
                $user = $this->getUser();

                return $this->render('forum/forum.html.twig', [
                    'articles' => $articles,
                    'status' => $status,
                    'page' => 'forum',
                    'categorie' => 'framework',
                    'user' => $user,
                    ]);
            }elseif($this -> getUser()->getStatus() == 'developer' && $this -> getUser() && $_GET['page'] == 'bdd' ){

                $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(['category' => '5'],['created_at' => 'desc']);

                $status = $this->getUser()->getStatus();
                $user = $this->getUser();

                return $this->render('forum/forum.html.twig', [
                    'articles' => $articles,
                    'status' => $status,
                    'page' => 'forum',
                    'categorie' => 'bdd',
                    'user' => $user,
                    ]);
            }

            else{   

            return $this->redirectToRoute('home_controller');
            } 
        }else{
        return $this->redirectToRoute('Page_404_not_Found');
    } 
    }
}
