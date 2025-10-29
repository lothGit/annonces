<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Repository\CategorieRepository;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConferencsController extends AbstractController
{

    public $em;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
    }

    // le nom d'une route doit être unique
    #[Route('/conferences', name: 'conference.index')]
    public function index(): Response
    {
        // $manager->getRepository(Conference::class)->findAll() permet de recuperer toutes les conférences
        $conferences = $this->em->getRepository(Conference::class)->findAll();
        // ici on retourne le template conferences.html.twig par rapport aux parametres passés en arguments
        return $this->render("conferences/conferences.html.twig", ['conferences' => $conferences]);
    }

    #[Route('/conference/details/{id}', name: 'conference.details', requirements: ['id' => '\d+'])]
    public function details($id)
    {
        // foreach ($this->conferences as $cle => $conference) {
        //     if ($conference['id'] == $id) {
        //         $conferences[] = $conference;
        //     }
        // }

        $conference = $this->em->getRepository(Conference::class)->find($id);

        return $this->render("conferences/conference.html.twig", ['conference' => $conference]);
    }

    #[Route('/conference/edit/{id}', name: 'conference.edit')]
    public function editer(Request $request, $id)
    {
        // foreach ($this->conferences as $cle => $conference) {
        //     if ($conference['id'] == $id) {
        //         $conference['titre'] = 'conference diango';
        //         $conferences[] = $conference;
        //     }
        // }

        $conference = $this->em->getRepository(Conference::class)->find($id);

        $form = $this->createForm(ConferenceType::class, $conference);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isvalid()) {
            $conference = $form->getData();
            // on utilise seulement le flush() si on veut supprimer ou modifier
            // si on utilise persist() et flush(), on rajoutera une autre ligne dans la table
            $this->em->flush();

            return $this->redirectToRoute('conference.index');
        }
        return $this->render("conferences/edit.html.twig", ['form' => $form->createView(),'bouton'=>'modifier']);
    }
    #[Route('/conference/supp/{id}', name: 'conference.supp')]
    public function delete($id)
    {
        $conference = $this->em->getRepository(Conference::class)->find($id);
        $this->em->remove($conference); // pour supprimer
        $this->em->flush();
        return $this->redirectToRoute('conference.index');
    }

    #[Route('/conference/add', name: 'conference.add')]
    public function add(Request $request)
    {
       
        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference);

        // ici je lie les données du formulaire avec l'objet conference s'il y'en a
        // il hydrate les propriétés
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isvalid()) {
            //dd(get_class_methods($form->getData()-_>getImage()->getFie()-getClientOriginalName()));
            //$conference = $form->getData();
            
            $dossier_images=$_SERVER['DOCUMENT_ROOT']."\uploads\images";
            $fichierImage=rand(1000,9999).time().'_'.$form->getData()->getImage()->getFile()->getClientOriginalName();
            $objetFichier=$form->getData()->getImage()->getFile();
            $objetFichier->move($dossier_images,$fichierImage);
            $bddFile="uploads/images";
            $conference->getImage()->setUrl($bddFile.'/'.$fichierImage);
            $conference->getImage()->setAlt($fichierImage);
            $conference->getImage()->setFile($objetFichier);
            
            $this->em->persist($conference);
            $this->em->flush();
            //$this->$this->addFlash('success','ajout avec succes');
               
            return $this->redirectToRoute("conference.index");
        }

        return $this->render("conferences/ajout.html.twig", ['form' => $form->createView(),'bouton'=>'ajouter']);
    }

    #[Route('/conferences/menu',name:'menu')]
    public function menu(ConferenceRepository $conference,CategorieRepository $categorie)
    {
        $conferences=$conference->findAll();
        $categories=$categorie->findAll();
        return $this->render("conferences/menu.html.twig",['conferences'=>$conferences,
        'categories'=>$categories]);
    }
}
