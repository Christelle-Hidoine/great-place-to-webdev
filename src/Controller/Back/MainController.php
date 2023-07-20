<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class MainController extends AbstractController
{
    /**
     * @Route("/back/main", name="app_back_default")
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(): Response
    {
        return $this->render('back/main/index.html.twig', [
            
        ]);
    }
}
