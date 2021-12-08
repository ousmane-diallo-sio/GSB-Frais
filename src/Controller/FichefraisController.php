<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Visiteur;
use App\Entity\Fichefrais;
use App\Entity\Fraisforfait;
use App\Form\FichefraisType;
use App\Form\FraisforfaitType;
use App\Repository\FichefraisRepository;
use App\Repository\FraisforfaitRepository;
use App\Repository\VisiteurRepository;
use DateTime;
use DateTimeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Runtime\Symfony\Component\Console\Output\OutputInterfaceRuntime;
use Doctrine\ORM\QueryBuilder;


/**
 * @Route("/fichefrais")
 */
class FichefraisController extends AbstractController
{
    /**
     * @Route("/", name="fichefrais_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $rpFicheFrais = $em->getRepository(FicheFrais::class);
        $fichefrais = $rpFicheFrais->findBy([
            'idvisiteur' => $session->get('id')
        ]);

        return $this->render('fichefrais/index.html.twig', [
            'fichefrais' => $fichefrais,
        ]);
    }

    /**
     * @Route("/new/{echec}", name="fichefrais_new", methods={"GET","POST"}, defaults={"echec" = null})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        $fichefrai = new Fichefrais();
        $fichefrai->setMontantvalide('0');
        $fichefrai->setDatemodif(new \DateTime());
        $visiteur = $this->getDoctrine()->getRepository(Visiteur::class)->findOneBy([ 'id' => $session->get('id') ]);
        $fichefrai->setIdvisiteur($visiteur);

        $fraisforfaits = $this->getDoctrine()->getRepository(Fraisforfait::class)->findAll();

        $form = $this->createForm(FichefraisType::class, $fichefrai, [
            'idvisiteur' => $visiteur->getId(),
            'visiteur' => $visiteur,
            'idfraisforfait' => $fraisforfaits
        ]);
        
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
        $session = $request->getSession();
        $visiteur = $this->getDoctrine()->getRepository(Visiteur::class)->findOneBy([ 'id' => $session->get('id') ]);
        
        $fraisforfaits = $this->getDoctrine()->getRepository(Fraisforfait::class)->findAll();

        $form = $this->createForm(FichefraisType::class, $fichefrai, [
            'idvisiteur' => $visiteur->getId(),
            'visiteur' => $visiteur,
            'idfraisforfait' => $fraisforfaits
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if (!$fichefrai) {
                throw $this->createNotFoundException(
                    'No product found for id '.$fichefrai
                );
            }


            return $this->redirectToRoute('fichefrais_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fichefrais/edit.html.twig', [
            'fichefrai' => $fichefrai,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{mois}/{id}", name="fichefrais_delete", methods={"POST"})
     */
    public function delete(Request $request, String $mois, String $id): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mois, $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $rp = $em->getRepository(Fichefrais::class);
            print_r($mois);
            print_r($id);
            $rp->supprimerFiche( $id, $mois );
            //Trouver un moyen d'utiliser le query builder ici
            //$em->remove($fichefrai);
            //$em->persist($mois);
            //$em->flush();
        }

        return $this->redirectToRoute('fichefrais_index', [], Response::HTTP_SEE_OTHER);
    }
}
