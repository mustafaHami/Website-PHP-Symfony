<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use App\Service\BoutiqueService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\Length;

class BoutiqueController extends AbstractController {
 
    public function index(BoutiqueService $boutique) { 
        $categories = $boutique->findAllCategories(); 
        $nbCategorie = count($categories);
        return $this->render(
            'boutique.html.twig',[
                'categories' => $categories,
                'nbCategories' => $nbCategorie]
        );
        
    }
    
    public function rayon(BoutiqueService $boutique, $idCategorie, SessionInterface $session){
        $produits = $boutique->findProduitsByCategorie($idCategorie);
        $categorie = $boutique->findCategorieById($idCategorie);
        $nbProduit = count($produits);
        $devise = $session->get('devise');
        return $this->render(
            'rayon.html.twig',[
                'produits' => $produits,
                'nbProduits' => $nbProduit,
                'devise' => $devise,
                'categorie' => $categorie
                ]
        ); 
    }

    public function chercher(BoutiqueService $boutique){
      $nomProduit = $_GET["searchString"];
        $produits = $boutique->findProduitsByLibelleOrTexte($nomProduit);
        $nbProduit = count($produits);
        return $this->render(
            'chercher.html.twig',[
                'produits' => $produits,
                'nomProduit' =>$nomProduit,
                'nbProduits' => $nbProduit
                ]
                    
        );
    }
 
}
    
    
?>