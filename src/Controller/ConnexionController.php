<?php

namespace App\Controller;

use App\Entity\Fichefrais;
use App\Entity\Visiteur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use \PDO;
use PDOException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;


class ConnexionController extends AbstractController
{

    
    public function __construct(){
        $this->connexionOk = false;
    }


    public function index(Request $request, $echec): Response
    {
        if($echec == false){

            $this->seConnecter($request);
            if($this->connexionOk){
                return $this->render('connexion/index.html.twig', [
                    'controller_name' => 'ConnexionController',
                    'session' => $request->getSession(),
                ]);
            }
            else{
                return new RedirectResponse('/connexion/echec');
            }
        }
        else{
            $session = $request->getSession();
            //$session->getFlashBag()->add('login', $request->request->get('login'));
            return $this->render('accueil/index.html.twig', [
                'controller_name' => 'ConnexionController',
            ]);
        }
    }



    public function seConnecter(Request $request){

        $login = $request->request->get('login');
        $mdp = $request->request->get('mdp');

        try{

            $em = $this->getDoctrine()->getManager();
            $repositoryConnexion = $em->getRepository(Visiteur::class);
            $visiteur = $repositoryConnexion->findOneBy( array('login' => $login, 'mdp' => $mdp) );


            if($visiteur){

                $rpFicheFrais = $em->getRepository(Fichefrais::class);
                $ficheFrais = $rpFicheFrais->findBy( array('idvisiteur' => $visiteur->getId()) );

                $session = $request->getSession();
                //$session->start();
                $session->set( 'visiteur', $visiteur );
                $session->set( 'login', $visiteur->getLogin() );
                $session->set( 'id', $visiteur->getId() );
                $session->set( 'nom', $visiteur->getNom() );
                $session->set( 'prenom', $visiteur->getPrenom() );
                $session->set( 'adresse', $visiteur->getAdresse() );
                $session->set( 'cp', $visiteur->getCP() );
                $session->set( 'ville', $visiteur->getVille() );
                $session->set( 'dateEmbauche', $visiteur->getDateEmbauche() );
                $session->set( 'ficheFrais', $ficheFrais );
                
                $this->connexionOk = true;

                return $session;

            }
        
        }
        catch( PDOException $e ){
            print_r($e);
        }
    }


    public function seDeconnecter(Request $request){
        $session = $request->getSession();
        $session->invalidate();
        return $this->redirect('index/index.html.twig');
    }


}
