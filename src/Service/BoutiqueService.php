<?php

namespace App\Service;

use App\Entity\Categorie;
use App\Entity\LigneCommande;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

// Un service pour manipuler le contenu de la Boutique
//  qui est composée de catégories et de produits stockés "en dur"
class BoutiqueService
{
    private EntityManagerInterface $em;
    // renvoie toutes les catégories
    public function findAllCategories()
    {
        $categories = $this->em->getRepository(Categorie::class)->findAll();
        return $categories;
    }

    // renvoie la categorie dont id == $idCategorie
    public function findCategorieById(int $idCategorie)
    {
        $categories = $this->em->getRepository(Categorie::class)->find($idCategorie);
        return $categories;
    }

    // renvoie le produit dont id == $idProduit
    public function findProduitById(int $idProduit)
    {
        $produit = $this->em->getRepository(Produit::class)->find($idProduit);
        return $produit;
    }

    // renvoie tous les produits dont idCategorie == $idCategorie
    public function findProduitsByCategorie(int $idCategorie)
    {
        $produit = $this->em->getRepository(Categorie::class)->find($idCategorie)->getProduits();
        return $produit;
    }
    // renvoie tous les produits les plus vendue
    public function findTopProduct(){
        $tab = $this->em->getRepository(LigneCommande::class)->findTopSellProduit();
        return $tab;
    }
    // renvoie tous les produits dont libelle ou texte contient $search
    public function findProduitsByLibelleOrTexte(string $search)
    {
        $produits = $this->em->getRepository(Produit::class)->findProduitByLibelle($search);
        
        if(empty($produits)){
            $produits =  $this->em->getRepository(Produit::class)->findAll();
        }
        return $produits;
    }

    // constructeur du service : injection des dépendances et tris
    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        // Injection du service RequestStack
        //  afin de pouvoir récupérer la "locale" dans la requête en cours
        $this->requestStack = $requestStack;
        // On trie le tableau des catégories selon la locale
        usort($this->categories, function ($c1, $c2) {
            return $this->compareSelonLocale($c1["libelle"], $c2["libelle"]);
        });
        $this->em = $em;
        // On trie le tableau des produits de chaque catégorie selon la locale
        usort($this->produits, function ($c1, $c2) {
            return $this->compareSelonLocale($c1["libelle"], $c2["libelle"]);
        });
    }

    ////////////////////////////////////////////////////////////////////////////
    private function compareSelonLocale(string $s1, $s2)
    {
        $collator = new \Collator($this->requestStack->getCurrentRequest()->getLocale());
        return collator_compare($collator, $s1, $s2);
    }
    
 
    private $requestStack; // Le service RequestStack qui sera injecté
    // Le catalogue de la boutique, codé en dur dans un tableau associatif
    private $categories = [
        [
            "id" => 1,
            "libelle" => "Fruits",
            "visuel" => "images/fruits.jpg",
            "texte" => "De la passion ou de ton imagination",
        ],
        [
            "id" => 3,
            "libelle" => "Junk Food",
            "visuel" => "images/junk_food.jpg",
            "texte" => "Chère et cancérogène, tu es prévenu(e)",
        ],
        [
            "id" => 2,
            "libelle" => "Légumes",
            "visuel" => "images/legumes.jpg",
            "texte" => "Plus tu en manges, moins tu en es un"
        ],
    ];
    private $produits = [
        [
            "id" => 1,
            "idCategorie" => 1,
            "libelle" => "Pomme",
            "texte" => "Elle est bonne pour la tienne",
            "visuel" => "images/pommes.jpg",
            "prix" => 3.42
        ],
        [
            "id" => 2,
            "idCategorie" => 1,
            "libelle" => "Poire",
            "texte" => "Ici tu n'en es pas une",
            "visuel" => "images/poires.jpg",
            "prix" => 2.11
        ],
        [
            "id" => 3,
            "idCategorie" => 1,
            "libelle" => "Pêche",
            "texte" => "Elle va te la donner",
            "visuel" => "images/peche.jpg",
            "prix" => 2.84
        ],
        [
            "id" => 4,
            "idCategorie" => 2,
            "libelle" => "Carotte",
            "texte" => "C'est bon pour ta vue",
            "visuel" => "images/carottes.jpg",
            "prix" => 2.90
        ],
        [
            "id" => 5,
            "idCategorie" => 2,
            "libelle" => "Tomate",
            "texte" => "Fruit ou Légume ? Légume",
            "visuel" => "images/tomates.jpg",
            "prix" => 1.70
        ],
        [
            "id" => 6,
            "idCategorie" => 2,
            "libelle" => "Chou Romanesco",
            "texte" => "Mange des fractales",
            "visuel" => "images/romanesco.jpg",
            "prix" => 1.81
        ],
        [
            "id" => 7,
            "idCategorie" => 3,
            "libelle" => "Nutella",
            "texte" => "C'est bon, sauf pour ta santé",
            "visuel" => "images/nutella.jpg",
            "prix" => 4.50
        ],
        [
            "id" => 8,
            "idCategorie" => 3,
            "libelle" => "Pizza",
            "texte" => "Y'a pas pire que za",
            "visuel" => "images/pizza.jpg",
            "prix" => 8.25
        ],
        [
            "id" => 9,
            "idCategorie" => 3,
            "libelle" => "Oreo",
            "texte" => "Seulement si tu es un smartphone",
            "visuel" => "images/oreo.jpg",
            "prix" => 2.50
        ],
    ];
}