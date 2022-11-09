<?php

namespace App\Controller;

use App\Entity\Usager;
use App\Form\UsagerType;
use App\Repository\UsagerRepository;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Builder\Use_;
use App\Service\MailService;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @Route("/{_locale}/usager", requirements={"_locale"="%app.supported_locales%"}, defaults={"_locale"="fr"})
 */
class UsagerController extends AbstractController
{
    public function index(UsagerRepository $usagerRepository, SessionInterface $session): Response
    {
        $usager = $this->getUser();
        $usagerCommande = count($usager->getCommandes());

        return $this->render('usager/index.html.twig', [
            'usager' => $usagerRepository->find($usager->getId()),
            'usagercommande' => $usagerCommande
        ]);
    }


    public function new(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, UserPasswordHasherInterface $passwordHasher, MailService $mailer): Response
    {

        $usager = new Usager();

        $form = $this->createForm(UsagerType::class, $usager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('sucesse', 'Vous avez été bien inscrit');
            // Encoder le mot de passe qui est en clair pour l’instant
            $hashedPassword = $passwordHasher->hashPassword($usager, $usager->getPassword());
            $usager->setPassword($hashedPassword);
            // Définir le rôle de l’usager qui va être créé 
            $usager->setRoles(["ROLE_CLIENT"]);

            $entityManager->persist($usager);

            $entityManager->flush();
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('usager/new.html.twig', [
            'usager' => $usager,
            'form' => $form
        ]);
    }

    public function commandes(SessionInterface $session)
    {
        $usager = $this->getUser();
        $devise = $session->get('devise');
        $commandes = $usager->getCommandes();

        return $this->renderForm('usager/commande.html.twig', [
            'commandes' => $commandes,
            'devise' => $devise
        ]);
    }
}