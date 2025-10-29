<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\SendMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, SendMailer $mailers ): Response
    {
        $contact=new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isvalid()) {
           $pseudo=$form->get('pseudo')->getData();
           $email=$form->get('email')->getData();
           $message=$form->get('message')->getData();

           $mailers->send($pseudo,$email,$message);
            return $this->redirectToRoute("contact.message");
        }
        return $this->render('contact/index.html.twig',
            ['form' => $form->createView()]);
    }

    #[Route('/contact/message', name: 'contact.message')]
    public function message(): Response
    {
        return $this->render('contact/message.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}
