<?php

namespace App\Controller;

use App\Entity\Lignefraishorsforfait;
use App\Form\LignefraishorsforfaitType;
use App\Repository\LignefraishorsforfaitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lignefraishorsforfait")
 */
class LignefraishorsforfaitController extends AbstractController
{
    /**
     * @Route("/", name="lignefraishorsforfait_index", methods={"GET"})
     */
    public function index(LignefraishorsforfaitRepository $lignefraishorsforfaitRepository): Response
    {
        return $this->render('lignefraishorsforfait/index.html.twig', [
            'lignefraishorsforfaits' => $lignefraishorsforfaitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="lignefraishorsforfait_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lignefraishorsforfait = new Lignefraishorsforfait();
        $form = $this->createForm(LignefraishorsforfaitType::class, $lignefraishorsforfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lignefraishorsforfait);
            $entityManager->flush();

            return $this->redirectToRoute('lignefraishorsforfait_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lignefraishorsforfait/new.html.twig', [
            'lignefraishorsforfait' => $lignefraishorsforfait,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="lignefraishorsforfait_show", methods={"GET"})
     */
    public function show(Lignefraishorsforfait $lignefraishorsforfait): Response
    {
        return $this->render('lignefraishorsforfait/show.html.twig', [
            'lignefraishorsforfait' => $lignefraishorsforfait,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lignefraishorsforfait_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Lignefraishorsforfait $lignefraishorsforfait): Response
    {
        $form = $this->createForm(LignefraishorsforfaitType::class, $lignefraishorsforfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lignefraishorsforfait_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lignefraishorsforfait/edit.html.twig', [
            'lignefraishorsforfait' => $lignefraishorsforfait,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="lignefraishorsforfait_delete", methods={"POST"})
     */
    public function delete(Request $request, Lignefraishorsforfait $lignefraishorsforfait): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lignefraishorsforfait->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lignefraishorsforfait);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lignefraishorsforfait_index', [], Response::HTTP_SEE_OTHER);
    }
}
