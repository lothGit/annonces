<?php
namespace App;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SendMailer 
{
    public function __construct(public MailerInterface $mailer){}
    public function send($pseudo,$email,$message){
        $email = (new Email())
            ->from($email)
            ->to('greta@gmail.com')
            ->subject('Nouvel utilisateur')
            ->text(' bienvenu sur notre site '.$email)
            ->html('<p>bienvenu sur notre site '.$email.'</p>');

        $this->mailer->send($email);
    }
   
}