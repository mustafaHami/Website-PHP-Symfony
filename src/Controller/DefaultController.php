<?php 
// Controller/DefaultController.php
namespace App\Controller;

use App\Service\BoutiqueService;
use App\Service\PanierService;
use App\Service\DeviseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DefaultController extends AbstractController {
    
private SessionInterface $session;

public function __construct(SessionInterface $session)
{
    $this->session = $session;   
}
    public function index(){
       // On met initialise la valeur de la devise à EUR
       // dès l'ouverture du site
       $devise =  $this->session->get('devise');

       if($devise == null){
            $devise = 'EUR';
        }
        
        $this->session->set('devise',$devise);

        return $this->render(
            'accueil.html.twig'
        );
    }
    
    public function contact(){
        return $this->render(
            'contact.html.twig'
        );
    }
    
    public function navBarProduit(PanierService $panier){
        
        $nbProduit = $panier->getNbProduits();
        
        return $this->render('Panier/nbProduit.html.twig',['nbProduit' => $nbProduit]); 
        
    }
    public function navBarDevise(){
        
        $deviseCourante  =  $this->session->get('devise');
        $tabeDevise = ['EUR','USD','GBP'];
        return $this->render('Devise/navBarDevise.html.twig',['deviseCourante' => $deviseCourante, 'tabDevise'=> $tabeDevise]);
        
    }
    
    public function sideBar(BoutiqueService $boutique){
        $produitsPlusvendu = $boutique->findTopProduct();
        $tabProduitPlusVendu = array();
        foreach ($produitsPlusvendu as $value) {
           
           $produit = $boutique->findProduitById($value['p']);
           $quantite = $value['qte'];
           $newProduit = array(
            "produit"  => $produit,
            "quantite" => $quantite,
         );
            $tabProduitPlusVendu[] = $newProduit;
        }
 
        return $this->render('sidebar.html.twig',['tabProduitPlusVendu' => $tabProduitPlusVendu]); 

    }

    public function saveDevise($devise, DeviseService $deviseService, SessionInterface $session)
    {   
        
        $session->set('devise',$devise);
        return $this->redirectToRoute('index');
       
        return null;
    }
    
}