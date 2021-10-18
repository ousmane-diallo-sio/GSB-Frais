<?php

namespace App\Controller;

use App\Entity\Fichefrais;
use App\Entity\Visiteur;
use Doctrine\ORM\QueryBuilder;
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

    public function consulterFicheFrais(){
        $em = $this->getDoctrine()->getManager();
        $repositoryFicheFrais = $em->getRepository(Visiteur::class);
        $ficheFrais = $repositoryFicheFrais->findAll();



        return $this->render('consulter_fiche_frais/index.html.twig', [
            'ficheFrai' => print_r($this->getFiches()),
            'ficheFrais' => $this->getFiches(),
        ]);

    }

    private function getFiches(){
        $listeId = array();
        $listeNom = array();

        $em = $this->getDoctrine()->getManager();
        $repositoryFicheFrais = $em->getRepository(Visiteur::class);
        $ficheFrais = $repositoryFicheFrais->findAll();
        foreach($ficheFrais as $fiche){
            array_push($listeId, $fiche->getId());
            array_push($listeNom, $fiche->getNom());
        }

        $listeEntiere = array(
            'id' => $listeId,
            'nom' => $listeNom
        );

        return $listeEntiere;
    }

}
