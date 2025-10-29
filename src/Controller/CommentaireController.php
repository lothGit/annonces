<?php

namespace App\Controller;
use DateTimeImmutable;
use App\Entity\Annonce;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire', name: 'app_commentaire')]
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }
	#[Route('/commentaire/ajout/{id}',name:'ajout_commentaire',defaults:['id'=>''])]
	public function ajoutComment(Request $request,EntityManagerInterface $manager,$id):Response
	{
        $commentaire=new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isvalid()) {
            $annonce=$manager->getRepository(Annonce::class)->find($id);
            $date=new DateTimeImmutable();
            $commentaire->setAnnonce($annonce);
            $commentaire->setPublishedAt($date);
            $manager->persist($commentaire);
            $manager->flush();
            return $this->redirectToRoute('annonce.details',['id'=>$commentaire->getAnnonce()->getId()]);
        }
		return $this->render('commentaire/ajout.html.twig',['id'=>$id,'form'=>$form->createView()]);
	}
}
