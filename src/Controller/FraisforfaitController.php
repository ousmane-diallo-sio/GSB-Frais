<?php

namespace App\Controller;

use App\Entity\Fraisforfait;
use App\Form\FraisforfaitType;
use App\Repository\FraisforfaitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/fraisforfait")
 */
class FraisforfaitController extends AbstractController
{
    /**
     * @Route("/", name="fraisforfait_index", methods={"GET"})
     */
    public function index(FraisforfaitRepository $fraisforfaitRepository): Response
    {
        return $this->render('fraisforfait/index.html.twig', [
            'fraisforfaits' => $fraisforfaitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="fraisforfait_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $fraisforfait = new Fraisforfait();
        $form = $this->createForm(FraisforfaitType::class, $fraisforfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fraisforfait);
            $entityManager->flush();

            return $this->redirectToRoute('fraisforfait_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fraisforfait/new.html.twig', [
            'fraisforfait' => $fraisforfait,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="fraisforfait_show", methods={"GET"})
     */
    public function show(Fraisforfait $fraisforfait): Response
    {
        return $this->render('fraisforfait/show.html.twig', [
            'fraisforfait' => $fraisforfait,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="fraisforfait_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Fraisforfait $fraisforfait): Response
    {
        $form = $this->createForm(FraisforfaitType::class, $fraisforfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fraisforfait_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fraisforfait/edit.html.twig', [
            'fraisforfait' => $fraisforfait,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="fraisforfait_delete", methods={"POST"})
     */
    public function delete(Request $request, Fraisforfait $fraisforfait): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fraisforfait->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fraisforfait);
            $entityManager->flush();
        }

        return $this->redirectToRoute('fraisforfait_index', [], Response::HTTP_SEE_OTHER);
    }
}
