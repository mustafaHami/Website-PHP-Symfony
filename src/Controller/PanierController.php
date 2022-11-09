<?php 

namespace App\Controller;

use App\Service\MailService;
use App\Service\PanierService;
use App\Repository\UsagerRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 

class PanierController extends AbstractController {
    
    public function index(PanierService $panierService,SessionInterface $session) { 
        
        $contenuePanier = $panierService->getContenu();
        $devise = $session->get('devise');
        $prixTotal = $panierService->getTotal();
        return $this->render(
            'Panier/index.html.twig',[
                'contenuePanier' => $contenuePanier,
                'prixTotal' => $prixTotal,
                'devise' => $devise
            ]
            
        );
    }

    public function ajouter(PanierService $panierService,$idProduit,$quantite){

        $panierService->ajouterProduit($idProduit,$quantite);

        return $this->redirectToRoute('panier_index');
    }

    public function enlever(PanierService $panierService,$idProduit,$quantite){
        
        $panierService->enleverProduit($idProduit,$quantite);
        return $this->redirectToRoute('panier_index');
    }

    public function supprimer(PanierService $panierService,$idProduit){
        $panierService->supprimerProduit($idProduit);
        return $this->redirectToRoute('panier_index');
    }
    public function vider(PanierService $panierService){
        $panierService->vider();
        return $this->redirectToRoute('panier_index');
    }
    
    public function validation(PanierService $panierService,SessionInterface $session,UsagerRepository $usagerRepository, MailService $mailer){
        
        $usager = $this->getUser();
        
        if($usager != null){
            
            $usager = $usagerRepository->find($usager->getId());
            $commande = $panierService->panierToCommande($usager,"valider");
            
            
            
            if($commande != null){
                $tabLigneCommande = $panierService->allProduitPanierForMail($commande);
                $total = $panierService->getTotal();
                $devise = $session->get('devise');
                $view = $this->render(
                    'Email/contact.html.twig',[
                        'commande' => $commande,
                        'tabLigneCommande' => $tabLigneCommande,
                        'total'=> $total ,
                        'usager' =>$usager,
                        'devise' => $devise
                    ]
                );
                $mailer->envoieMail($commande,$view);
                return $this->render(
                    'Panier/panierValide.html.twig',[
                        'commande' => $commande,
                        'usager' => $usager
                    ]            
                );
                
            }else{
                return $this->redirectToRoute('panier_index');
            }
        }else{
            return $this->redirectToRoute('app_login');    
        }
    }
}
?>