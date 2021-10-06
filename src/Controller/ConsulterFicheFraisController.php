<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsulterFicheFraisController extends AbstractController
{
    /**
     * @Route("/consulter/fiche/frais", name="consulter_fiche_frais")
     */
    public function index(): Response
    {
        return $this->render('consulter_fiche_frais/index.html.twig', [
            'controller_name' => 'ConsulterFicheFraisController',
        ]);
    }
}
