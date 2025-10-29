<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Annonce;
use App\Entity\Categorie;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class AnnonceController extends AbstractController
{
    
	public $manager;
	public $annonceRepo;
	public $date;
	
    public function __construct(PersistenceManagerRegistry $manager,AnnonceRepository $annonceRepo)
    {
        $this->manager = $manager;
		$this->annonceRepo=$annonceRepo;
		$this->date=new DateTimeImmutable();
    }
	
	#[Route("/", name: "annonce.index")]
	#[Route('/annonces/categorie/{id}', name: 'annonce.categorie')]
    public function index(Request $request, CategorieRepository $categorie): Response
    {
	   $annonces=$this->annonceRepo->findAll();
	   // on n'a pas mis de $id en parametre car on ne veut pas être obligé d'en mettre c'est le cas si on tape sur l'url /conferences du coup si on veut utiliser les deux routes (/conferences et /conferences/categorie/{id}) on prefere récupérer l'id par la methode $request->attributes->get('id')
       if ($request->attributes->get('id')) {
           //$annonces = $this->em->getRepository(Conference::class)->findBy(['categorie' => $request->attributes->get('id')]);
		   //$annonces=$this->annonceRepo->findBy(['Categorie' => $request->attributes->get('id')]);
           /*$annoncesCat=[];        
           //tous les categories ayant le meme nom
           $categories=$categorie->findByNameCat(['Categorie' => $request->attributes->get('nom')]);
           $annonceArr=$this->annonceRepo->findAll();
           for($i=0;$i<count($categories);$i++){
                for($j=0;$j<count($annonceArr);$j++){
                    if($categories[$i]->getId()==$annonceArr[$j]->getCategorie()->getId()){
                        $annoncesCat[]=$annonceArr[$j]; 
                    }
                }      
            }
            $annonces=$annoncesCat;*/
            $annonces=$this->annonceRepo->findBy(['Categorie' => $request->attributes->get('id')]);
       } else {
           $annonces = $this->annonceRepo->findAll();
       }
        $categories=$categorie->findAll();
       // dd($categories);
       // ici on retourne le template annonces.html.twig par rapport aux parametres passés en arguments
        return $this->render("annonces/index.html - Copie.twig", ['annonces' => $annonces, 'categories' => $categories]);
        
        //return $this->render("annonces/index.html - Copie.twig", compact('annonces', 'categories'));
	   
    }

    #[Route('/annonce/details/{id}', name: 'annonce.details', requirements: ['id' => '\d+'])]
    public function details($id)
    {
        //$annonce = $this->em->getRepository(annonce::class)->find($id);
		$annonce=$this->annonceRepo->find($id);
		//dd($annonce);
        return $this->render("annonces/annonce.html.twig", ['annonce' => $annonce]);
    }
    
    #[Route('/annonce/edit/{id}', name: 'annonce.edit')]
    public function editer(Request $request, $id)
    {
		$em = $this->manager->getManager();
		$annonce=$this->annonceRepo->find($id);
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isvalid()) {
            $annonce = $form->getData();
            // on utilise seulement le flush() si on veut supprimer ou modifier
            // si on utilise persist() et flush(), on rajoutera une autre ligne dans la table
            $em->flush();
            return $this->redirectToRoute('annonce.index');
        }
        return $this->render("annonces/edit.html.twig", ['form' => $form->createView(),'bouton'=>'modifier']);
    }

    #[Route('/annonce/supp/{id}', name: 'annonce.supp')]
    public function delete($id)
    {
        $em = $this->getManager();
		$annonce=$this->annonceRepo->find($id);
        //$em->remove($annonce); // pour supprimer
        //$em->flush();
        return $this->redirectToRoute('annonce.index');
    }

    #[Route('/annonce/add', name: 'annonce.add')]
    public function add(Request $request)
    {
        $annonce = new annonce();
		$em = $this->manager->getManager();
        $form = $this->createForm(AnnonceType::class, $annonce);

        // ici je lie les données du formulaire avec l'objet annonce s'il y'en a
        // il hydrate les propriétés
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isvalid()) {
            //dd(get_class_methods($form->getData()-_>getImage()->getFie()-getClientOriginalName()));
            $annonce = $form->getData();
            $dossier_images=$_SERVER['DOCUMENT_ROOT']."\uploads\images";
            $fichierImage=rand(1000,9999).time().'_'.$form->getData()->getImage()->getFile()->getClientOriginalName();
            $objetFichier=$form->getData()->getImage()->getFile();
            $objetFichier->move($dossier_images,$fichierImage);
            $bddFile="uploads/images";
            $annonce->getImage()->setUrl($bddFile.'/'.$fichierImage);
            $annonce->getImage()->setAlt($fichierImage);
            $annonce->getImage()->setFile($objetFichier);
			$annonce->setPublishAt($this->date);
			$em->persist($annonce);
			$em->flush();
            //$this->$this->addFlash('success','ajout avec succes');
               
            return $this->redirectToRoute("annonce.index");
        }

        return $this->render("annonces/ajout.html.twig", ['form' => $form->createView(),'bouton'=>'ajouter']);
    }

    #[Route('/annonce/menu',name:'menu')]
    public function menu(AnnonceRepository $annonce,CategorieRepository $categorie)
    {
        //$annonces=$annonce->findAll();
        $annonces=$annonce->findLastAnnonces();
        //$categories=$categorie->findAll();
        $categories=$categorie->findAll();
        
        return $this->render("annonces/menu.html.twig",['annonces'=>$annonces,
        'categories'=>$categories]);
    }

    #[Route('/favorite:{id}', name: 'favorite')]
    public function favorite($id,AnnonceRepository $annonce)
    {
        $em = $this->manager->getManager();
        $annonce=$annonce->find($id);
        //dd($annonce);
        
        if ($annonce->getFavorite() > 0) {
           $compteur = $annonce->getFavorite() + 1;
            $annonce->setFavorite(0);
        } else {
            $annonce->setFavorite(1);
        }
        $em->flush();

        return $this->redirectToRoute('annonce.details', ['id' => $id]);
    }
    #[Route('/annonce/search', name: 'annonce.search')]
    public function search(Request $request,AnnonceRepository $annonce)
    {
        $annonces=$annonce->findByDate($request->get('date'));
        $categories = $this->manager->getRepository(Categorie::class)->findAll();
        return $this->render("annonces/index.html - Copie.twig", 
        ['annonces' => $annonces, 'categories' => $categories]);
    }
}
