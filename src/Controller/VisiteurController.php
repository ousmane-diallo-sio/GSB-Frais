<?php

namespace App\Controller;

use App\Entity\Visiteur;
use App\Form\VisiteurType;
use App\Repository\VisiteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/visiteur")
 */
class VisiteurController extends AbstractController
{
    /**
     * @Route("/", name="visiteur_index", methods={"GET"})
     */
    public function index(VisiteurRepository $visiteurRepository): Response
    {
        return $this->render('visiteur/index.html.twig', [
            'visiteurs' => $visiteurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="visiteur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $visiteur = new Visiteur();
        $form = $this->createForm(VisiteurType::class, $visiteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($visiteur);
            $entityManager->flush();

            return $this->redirectToRoute('visiteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('visiteur/new.html.twig', [
            'visiteur' => $visiteur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="visiteur_show", methods={"GET"})
     */
    public function show(Visiteur $visiteur): Response
    {
        return $this->render('visiteur/show.html.twig', [
            'visiteur' => $visiteur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="visiteur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Visiteur $visiteur): Response
    {
        $form = $this->createForm(VisiteurType::class, $visiteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('visiteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('visiteur/edit.html.twig', [
            'visiteur' => $visiteur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="visiteur_delete", methods={"POST"})
     */
    public function delete(Request $request, Visiteur $visiteur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$visiteur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($visiteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('visiteur_index', [], Response::HTTP_SEE_OTHER);
    }
}
