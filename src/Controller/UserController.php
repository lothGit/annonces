<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Annonce;
use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public $em;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
        $this->publishedAt = new DateTimeImmutable();
    }

    #[Route('/user/profil/{id}', name: 'user.profil')]
    #[Route('/user/annonce/{idAnnonce}', name: 'user.annonce', defaults: ['idConf' => ""])]
    public function profil($id = '', $idAnnonce = ''): Response
    {
        $commentaires = '';
        $user = $this->getUser();
        //$annonces = $this->em->getRepository(Annonce::class)->findByUser($id);
        $annonces = $this->em->getRepository(Annonce::class)->findBy(['user' => $user]);
        //$commentaires = $this->em->getRepository(Commentaire::class)->findByannonce(7);
        //$commentaires = $this->em->getRepository(Commentaire::class)->findAll();
        if (isset($idConf) and !empty($idConf)) {
            $annonce = $this->em->getRepository(annonce::class)->find($idAnnonce);
            $commentaires = $this->em->getRepository(Commentaire::class)
                ->findBy(['annonce' => $annonce]);
        }
        return $this->render('user/index.html.twig', [
            'annonces' => $annonces,
            'commentaires' => $commentaires
        ]);
    }
}
