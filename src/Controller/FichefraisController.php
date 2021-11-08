<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Visiteur;
use App\Entity\Fichefrais;
use App\Entity\Fraisforfait;
use App\Form\FichefraisType;
use DateTimeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/fichefrais")
 */
class FichefraisController extends AbstractController
{
    /**
     * @Route("/", name="fichefrais_index", methods={"GET"})
     */
    public function index(): Response
    {
        $fichefrais = $this->getDoctrine()
            ->getRepository(Fichefrais::class)
            ->findAll();

        return $this->render('fichefrais/index.html.twig', [
            'fichefrais' => $fichefrais,
        ]);
    }

    /**
     * @Route("/new", name="fichefrais_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $fichefrai = new Fichefrais();
        $fichefrai->setMontantvalide('0');
        $fichefrai->setDatemodif(new \DateTime());
            
        $form = $this->createForm(FichefraisType::class, $fichefrai);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $fichefrai->setMois( $form->get('mois')->getData() );
            $fichefrai->setNbjustificatifs( $form->get('nbjustificatifs')->getData() );
            $fichefrai->setMontantvalide( $form->get('montantvalide')->getData() );
            $fichefrai->setDatemodif( $form->get('datemodif')->getData() );
            $fichefrai->setIdetat( $form->get('idetat')->getData() );
            $fichefrai->setIdvisiteur( $form->get('idvisiteur')->getData() );
            $fichefrai = $form->getData();

            $entityManager->persist($fichefrai);
            $entityManager->flush();

            return $this->redirectToRoute('fichefrais_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fichefrais/new.html.twig', [
            'fichefrai' => $fichefrai,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{mois}", name="fichefrais_show", methods={"GET"})
     */
    public function show(Fichefrais $fichefrai): Response
    {
        return $this->render('fichefrais/show.html.twig', [
            'fichefrai' => $fichefrai,
        ]);
    }

    /**
     * @Route("/{mois}/edit", name="fichefrais_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Fichefrais $fichefrai): Response
    {
        $form = $this->createForm(FichefraisType::class, $fichefrai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fichefrais_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fichefrais/edit.html.twig', [
            'fichefrai' => $fichefrai,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{mois}", name="fichefrais_delete", methods={"POST"})
     */
    public function delete(Request $request, Fichefrais $fichefrai): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fichefrai->getMois(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fichefrai);
            $entityManager->flush();
        }

        return $this->redirectToRoute('fichefrais_index', [], Response::HTTP_SEE_OTHER);
    }
}
