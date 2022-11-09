<?php

namespace App\Service;


use App\Entity\Commande;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;


// un service qui permet de gérer l'envoie d'un mail
class MailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function envoieMail(Commande $commande,string $view)
    {

        $mail = $commande->getUsager()->getEmail();
        $nomUser = $commande->getUsager()->getNom();
        $prenUser = $commande->getUsager()->getPrenom();
        
            $email = (new Email())
            ->from('mustafahami61@hotmail.fr')
            ->to($mail)
            ->subject('Merci '.$prenUser.' '.$nomUser.', pour votre commande n°' . $commande->getId())
            
            // Raw html
            ->html($view);
       $this->mailer->send($email);
        
    }
}