<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class DeveloperController extends AbstractController
{
    /**
     * @Route("/developer", name="developer")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $status = $this->getUser()->getStatus();
       
        
        if(isset($_GET['user'])){
            $dev = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $_GET['user']]);
            if($this->getUser()){
                
                return $this->render('developer/developer.html.twig', [
                    'controller_name' => 'ProjetController',
                    'status' => $status,
                    'page' => 'projet',
                    'user' => $user,
                    'dev'  => $dev,
                ]);   
            }else{
                return $this->redirectToRoute('home_controller');
            }
        }
    }
}
