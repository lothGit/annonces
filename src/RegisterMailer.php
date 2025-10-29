<?php
namespace App;

use Twig\Environment;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterMailer 
{
    public $twig;
    public function __construct(public MailerInterface $mailer,Environment $twig){
        $this->twig=$twig;
    }
    public function send($firstname,$lastname,$email){
        $email = (new Email())
            ->from('admin@admin.com')
            ->to($email)
            ->subject('Nouvel utilisateur')
            ->text(' bienvenu sur notre site '.$firstname.' '.$lastname)
            ->html('<p>bienvenu sur notre site '.$firstname.' '.$lastname.'</p>');

        $this->mailer->send($email);
    }
   
}