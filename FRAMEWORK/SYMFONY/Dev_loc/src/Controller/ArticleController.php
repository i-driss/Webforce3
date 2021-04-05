<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Respect\Validation\Validator as v;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Commentary;
use App\Entity\User;

class ArticleController extends AbstractController
{

    public function article(): Response
    {   

        // On récupère l'article
        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(['id' => $_GET['id']]);
        $status = $this->getUser()->getStatus();
        
        $categorie = $article -> getCategory() -> getName();
    
        // Recuperez la catégorie de l'article pour le mettre dans le render
        // On récupère les commentaires correspondant à l'article
        $commentary = $this->getDoctrine()->getRepository(Commentary::class)->findBy([
            'article' => $_GET['id'],
        ],['created_at' => 'desc']);
        
        if(!empty($_POST)){

            $safe = array_map('trim', array_map('strip_tags', $_POST));
            $errors = [];
            
            if(!v::length(10, null)->validate($safe['commentary'])){ // validation contenu
                $errors[] = 'Votre contenu doit comporter au minimum 10 caractères';
            }

            if(count($errors) === 0){

                $em = $this->getDoctrine()->getManager();

                
                $commentary = new Commentary;
                $commentary->setArticle($article);
                $commentary->setContent($safe['commentary']);
                
                $commentary->setCreatedAt(new \DateTime('now'));

                $em->persist($commentary);
                $em->flush();
                $_POST = array();

                // On crée le message flash
                $this->addFlash('success', 'Votre commentaire a été publié.');
                }
            }
            
            $user = $this->getUser();
            return $this->render('forum/article.html.twig', [
            'article' => $article,
            'status' => $status,
            'page' => 'forum',
            'commentaires' => $commentary,
            'categorie' => $categorie,
            'user' => $user,
            ]);
        

        if(!$article){
        // Si aucun article n'est trouvé, nous créons une exception
        throw $this->createNotFoundException('L\'article n\'existe pas');
            }

    }


    public function addArticle(): Response
    {
        $user = $this->getUser();
        $status = $this->getUser()->getStatus();
        
        $errors = [];
    
            if(!empty($_POST)){
                $safe = array_map('trim', array_map('strip_tags', $_POST));
    
                if(!v::length(5, 100)->validate($safe['title'])){ // Validation Titre
                    $errors[] = 'Votre titre doit contenir entre 5 et 100 caractères';
                }
    
                if(!v::length(30, null)->validate($safe['content'])){ // validation contenu
                    $errors[] = 'Votre contenu doit comporter au minimum 30 caractères';
                }
    
                if(count($errors) === 0){
                    
                    $em = $this->getDoctrine()->getManager();
                    $category = $em->getRepository(Category::class);
                     // Replace the categories array (1,2) with the result of a findBy of the Categories Repository
                    $safe['category'] = $category->findOneBy(['id' => $safe['category']]);
                    
                    $article = new Article;
                    $article->setUser($this->getUser());
                    $article->setTitle($safe['title']);
                    $article->setCategory($safe['category']);
                    $article->setContent($safe['content']);
                    $article->setCreatedAt(new \DateTime('now'));
    
                    $em->persist($article);
                    $em->flush();
                    $_POST = array();
                    $article = $this->getDoctrine()->getRepository(Article::class)->findOneby(['title' => $safe['title']]);
                    // On crée le message flash
                    $this->addFlash('success', 'Votre article a été publié.');

                    return $this->redirectToRoute('article_controller', ['category'=> $safe['category'], 'id'=>$article -> getId() ]);

                } // endif count($errors) === 0
                else {
                    $this->addFlash('danger', implode('<br>', $errors));
                }
    
            }

            return $this->render('forum/addArticle.html.twig', [
                'status' => $status,
                'page' => 'forum',
                'categorie' => '',
                ]);
        }
        /* 
        @ParamConverter("article", options={"mapping": {"articleid" : "id"}})
        */
        public function updateArticle(Request $request): Response
        {
            
            $em = $this->getDoctrine()->getManager();
            $article = $em->getRepository(Article::class)->findOneBy(['id' => $_GET['id']]);
            $user = $this->getUser();
            $status = $this->getUser()->getStatus(); 
            
            $errors = [];
        
                if(!empty($_POST)){
                    $safe = array_map('trim', array_map('strip_tags', $_POST));
        
                    if(!v::length(5, 100)->validate($safe['title'])){ // Validation Titre
                        $errors[] = 'Votre titre doit contenir entre 5 et 100 caractères';
                    }
        
                    if(!v::length(30, null)->validate($safe['content'])){ // validation contenu
                        $errors[] = 'Votre contenu doit comporter au minimum 30 caractères';
                    }
        
                if(count($errors) === 0){
                
                $category = $em->getRepository(Category::class);
                // Replace the categories array (1,2) with the result of a findBy of the Categories Repository
                $safe['category'] = $category->findOneBy(['id' => $safe['category']]);

                    $article->setUser($this->getUser());
                    $article->setTitle($safe['title']);
                    $article->setCategory($safe['category']);
                    $article->setContent($safe['content']);


            if($request->isMethod('POST')){
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
                
                // On crée le message flash
                $this->addFlash('success', 'Votre article a été modifié.');

                return $this->render('forum/article.html.twig',[
                    'article' => $article,
                    'status' => $status,
                    'page' => 'forum',
                    'categorie' => '',
                    'user' => $user,
                    ]);

            }else {
                $this->addFlash('danger', implode('<br>', $errors));
            }
        }
    }
        return $this->render('forum/updateArticle.html.twig', [
            'article' => $article,
            'status' => $status,
            'page' => 'forum',
            'categorie' => '',
            'user' => $user,
            ]);
    }


    public function deleteArticle(): Response
    {

        $user = $this->getUser();
        $status = $this->getUser()->getStatus(); 

        $em = $this->getDoctrine()->getManager();
        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(['id' => $_GET['id']]);
        
        $em->remove($article);
        $em->flush();

        $this->addFlash('success', 'Votre article a été supprimé.');

        return $this->redirectToRoute('forum_controller',['page' => 'accueil' ]);
        }
    


    public function updateCommentary(Request $request): Response
    {   

        // On récupère l'article
        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(['id' => $_GET['id']]);
        $status = $this->getUser()->getStatus();
        
        $categorie = $article -> getCategory() -> getName();
    
        // Recuperez la catégorie de l'article pour le mettre dans le render
        // On récupère le commentaire correspondant à l'article
        $commentary = $this->getDoctrine()->getRepository(Commentary::class)->findOneBy([
            'id' => $_GET['commentary']
        ],['created_at' => 'desc']);

        if(!empty($_POST)){

            $user = $this->getUser();


            $errors = [];
            $safe = array_map('trim', array_map('strip_tags', $_POST));

            
            if(!v::length(10, null)->validate($safe['commentary'])){ // validation contenu
                $errors[] = 'Votre contenu doit comporter au minimum 10 caractères';
            }
            

            if(count($errors) === 0){

                $em = $this->getDoctrine()->getManager();

                $commentary->setArticle($article);
                $commentary->setContent($safe['commentary']);
                

                $em->persist($commentary);
                $em->flush();
                $_POST = array();

                if($request->isMethod('POST')){
                
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($article);
                    $em->flush();
                    
                    // On crée le message flash
                    $this->addFlash('success', 'Votre commentaire a été modifié.');
    
                    // return 
                    
                    return $this->render('forum/article.html.twig',[
                    'article' => $article,
                    'status' => $status,
                    'page' => 'forum',
                    'categorie' => '',
                    'user' => $user,
                    ]);
                    }
                }
            }

            $user = $this->getUser();
            return $this->render('forum/updateCommentary.html.twig', [
            'article' => $article,
            'status' => $status,
            'page' => 'forum',
            'commentaires' => $commentary,
            'categorie' => $categorie,
            'user' => $user,
            ]);
    }

    public function deleteCommentary(): Response
    {
        $user = $this->getUser();
        // On récupère l'article
        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(['id' => $_GET['id']]);
        $status = $this->getUser()->getStatus();
                
        $categorie = $article -> getCategory() -> getName();
            
        // Recuperez la catégorie de l'article pour le mettre dans le render
        // On récupère le commentaire correspondant à l'article
        $commentary = $this->getDoctrine()->getRepository(Commentary::class)->findOneBy([
        'id' => $_GET['commentary']
        ],['created_at' => 'desc']);

        $em = $this->getDoctrine()->getManager();

        

        

        $em->remove($commentary);
        $em->flush();

        $this->addFlash('success', 'Votre commentaire a été supprimé.');

        

        return $this->render('forum/article.html.twig', [
            'article' => $article,
            'status' => $status,
            'page' => 'forum',
            'categorie' => '',
            'user' => $user,
            ]);
        }
    }