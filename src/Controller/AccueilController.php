<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
   public $annonces=[
                [
                    'id'=>1,
                    'titre'=>'Développeur Symfony',
                    'description'=>'Cherche développeur symfony avec 3 ans d\' expérience',
                    'publishAt'=>'24-04-2024',
                    'lieu'=>'Paris',
                    'remuneration'=>'35000 annuel'
                ],
                [
                    'id'=>2,
                    'titre'=>'Intégrateur web',
                    'description'=>'Nous recherchons un intégrateur web Rect',
                    'publishAt'=>'15-04-2024',
                    'lieu'=>'Nanterre',
                    'remuneration'=>'30000€ annuel'
                ],
                [
                    'id'=>3,
                    'titre'=>'Développeur Java',
                    'description'=>'Nous recherchons un développeur java avec 3 ans d\'expériences',
                    'publishAt'=>'11-04-2024',
                    'lieu'=>'Lyon',
                    'remuneration'=>'35000€'
                ],
                [
                    'id'=>4,
                    'titre'=>'Développeur PHP/Symfony',
                    'description'=>'Cherche développeur PHP/Symfony confirmé',
                    'publishAt'=>'11-04-2024',
                    'lieu'=>'Paris',
                    'remuneration'=>'35000€'
                ],

        ];
   
   
    #[Route('/accueil', name: 'app_accueil')]
    public function index(): Response
    {
        //dd($this->annonces);
        return $this->render('accueil/accueil.html.twig', 
            ['annonces' => $this->annonces]
        );
    }
    #[Route('/annoncesx/{id}', name: 'annonce.details')]
    function details($id)  {
        foreach ($this->annonces as $cle => $annonce) {
            if ($annonce['id'] == $id) {
                $annonceDetail[] = $annonce ;
            }
        } 
       return $this->render('accueil/accueil.html.twig', ['annonce' => $annonceDetail,
       'annonces' => $this->annonces]);
    }
	
	#[Route("/annoncesqqq", name: "annonce.index")]
    public function indexAnnonce(): Response
    {
       // $manager->getRepository(annonce::class)->findAll() permet de recuperer toutes les conférences
       //$annonce = $this->em->getRepository(annonce::class)->findAll();
       // ici on retourne le template annonce.html.twig par rapport aux parametres passés en arguments
       return $this->render("annonces/index.html.twig");
    }

}
