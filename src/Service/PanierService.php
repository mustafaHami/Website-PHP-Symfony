<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\BoutiqueService;
use App\Entity\Usager;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\LigneCommande;

// Service pour manipuler le panier et le stocker en session 
class PanierService
{
    /////////////////////////////////////////////////////////////////////////// 
    const PANIER_SESSION = 'panier'; // Le nom de la variable de session du panier 
    private $session; // Le service Session
    private $boutique; // Le service Boutique
    private $panier; // Tableau associatif idProduit => quantite
    private EntityManagerInterface $em;
    // donc $this->panier[$i] = quantite du produit dont l'id = $i
    // constructeur du service
    public function __construct(SessionInterface $session, BoutiqueService $boutique,EntityManagerInterface $em)
    { // Récupération des services session et BoutiqueService
        $this->boutique = $boutique;
        $this->session = $session;
        $this->em = $em;
        // Récupération du panier en session s'il existe, init. à vide sinon
        $this->panier = $session->get(self::PANIER_SESSION, []);
    }
    // Contenu renvoie le contenu du panier
    // tableau d'éléments [ "produit" => un produit, "quantite" => quantite ]
    public function getContenu()
    { // à compléter 
        $tabProduit[] = [];
        foreach ($this->panier as $idProduit => $quantite) {

            $produit = $this->boutique->findProduitById($idProduit);
            $newProduit = array(
                "produit"  => $produit,
                "quantite" => $quantite,
            );
            $tabProduit[] = $newProduit;
        }
        
        return $tabProduit;
    }
    // getTotal renvoie le montant total du panier
    public function getTotal()
    { // à compléter
        $prixTotalPanier = 0;

        foreach ($this->panier as $idProduit => $quantite) {
            $produit = $this->boutique->findProduitById($idProduit); // Récupération du produit grâce à son l'id enregistrer dans la session
            $prixTotalProduit = $quantite * $produit->getPrix();
            $prixTotalPanier +=$prixTotalProduit;
        }


        return $prixTotalPanier;
    }
    // getNbProduits renvoie le nombre de produits dans le panier
    public function getNbProduits()
    {
        $nbProduit = 0;
        foreach ($this->panier as $idProduit => $quantite) {
            $nbProduit += $quantite;
        }
        return $nbProduit;
    }

    // ajouterProduit ajoute au panier le produit $idProduit en quantite $quantite 
    public function ajouterProduit(int $idProduit, int $quantite = 1)
    {
        if (isset($this->panier[$idProduit])) {
            $this->panier[$idProduit] += $quantite;
        } else {
            $this->panier[$idProduit] = $quantite;
        }
        $this->session->set(self::PANIER_SESSION, $this->panier);
    }

    // enleverProduit enlève du panier le produit $idProduit en quantite $quantite 
    public function enleverProduit(int $idProduit, int $quantite = 1)
    {

        if ($this->panier[$idProduit] == 1) {
            $this->supprimerProduit($idProduit);
        } else {
            $this->panier[$idProduit] -= $quantite;
        }

        $this->session->set(self::PANIER_SESSION, $this->panier);
    } // supprimerProduit supprime complètement le produit $idProduit du panier
    public function supprimerProduit(int $idProduit)
    {
        unset($this->panier[$idProduit]);
        $this->session->set(self::PANIER_SESSION, $this->panier);
    }
    // vider vide complètement le panier
    public function vider()
    {
        $this->session->remove(self::PANIER_SESSION);
    }
    
    public function panierToCommande(Usager $usager, $statut)
    {
        if(!empty($this->panier))
         {
            $commande = new Commande();
            $commande->setUsager($usager);
            $commande->setStatut("commande " . $statut);
            $commande->setDateCommande(new \DateTime('now'));
            $this->em->persist($commande);
            $this->em->flush();


            foreach ($this->panier as $idProduit => $quantite) {
                $ligneCommande = new LigneCommande();
                $ligneCommande->setCommande($commande);
                // $commande->addLigneCommande($ligneCommande);
                $produit = $this->boutique->findProduitById($idProduit);
                $ligneCommande->setProduit($produit);
                $ligneCommande->setQuantite($quantite);
                $prix = $produit->getPrix() * $quantite;
                $ligneCommande->setPrix($prix);
                $this->em->persist($ligneCommande);
            }
            $this->em->flush();
            $this->session->remove(self::PANIER_SESSION);
            return $commande;
        }else{
            return null;
        }
    }
    // renvoie tout les produits
    public function allProduitPanierForMail( $commande){
        
        $lignesCommandes = $this->em->getRepository(LigneCommande::class)->findProductForMail($commande->getId());
        
        foreach ($lignesCommandes as $ligneCommande) {
            
            $produit = $this->boutique->findProduitById($ligneCommande->getProduit()->getId());
            $quantite = $ligneCommande->getQuantite();
            $prix = $ligneCommande->getPrix();
            $newProduit = array(
                "produit"  => $produit,
                "quantite" => $quantite,
                'prix' => $prix
            );
            $tabProduit[] = $newProduit;
        }
        
        return $tabProduit;
        
    }

}