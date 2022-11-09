<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/{_locale}/login", name="app_login",requirements={"_locale"="%app.supported_locales%"}))
     */
    public function login(AuthenticationUtils $authenticationUtils,SessionInterface $session): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // Puisque lors de la déconnexion la session ce vide
        // on perd la devise enregistré ce qui retorune une erreur.
        // pour eviter cela je l'initialise avec la devise EUR
        $devise =  $session->get('devise');
       
        if($devise == null){
             $devise = 'EUR';
         }
         
         $session->set('devise',$devise);
 
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/{_locale}/logout", name="app_logout",requirements={"_locale"="%app.supported_locales%"}))
     */
    public function logout(AuthenticationUtils $authenticationUtils): void
    {
       $this->get('session')->invalidate();   
    }
}