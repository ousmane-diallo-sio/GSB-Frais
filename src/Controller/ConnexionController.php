<?php

namespace App\Controller;

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

            $bd = new PDO(
                'mysql:host=localhost;dbname=gsbFrais' ,
                'sanayabio' ,
                'azerty'
            ) ;

            $sql = 'select * from Visiteur where login = :login and mdp = :mdp';
            
            $st = $bd -> prepare( $sql ) ;

            $st -> execute( array( 
                                ':login' => $login ,
                                ':mdp' => $mdp 
                        ) 
                    ) ;
            $resultat = $st -> fetchall() ;

            unset( $bd ) ;

            if( count( $resultat ) == 1 ) {

                $session = $request->getSession();
                //$session->start();
                $session->set('login', $resultat[0]['login']);
                $session->set('id', $resultat[0]['id']);
                $session->set('nom', $resultat[0]['nom']);
                $session->set('prenom', $resultat[0]['prenom']);
                $session->set('adresse', $resultat[0]['adresse']);
                $session->set('cp', $resultat[0]['cp']);
                $session->set('ville', $resultat[0]['ville']);
                $session->set('dateEmbauche', $resultat[0]['dateEmbauche']);


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
