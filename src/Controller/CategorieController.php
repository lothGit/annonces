<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\AnnonceRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class CategorieController extends AbstractController
{
    public $manager;
	public $categorieRepo;
	
	public function __construct(PersistenceManagerRegistry $manager,CategorieRepository $categorieRepo)
    {
        $this->manager = $manager;
		$this->categorieRepo=$categorieRepo;
    }	
	
	#[Route('/categorie/add', name: 'add_categorie')]
    public function add(Request $request): Response
    {   
		$categorie = new Categorie();
		$em = $this->manager->getManager();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isvalid()){
			//dd($form->getData());
            $em->persist($form->getData());
            $em->flush();
            $this->redirectToRoute('list_categorie');
        }
        return $this->render('categories/add.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    #[Route('/categorie/list', name: 'list_categorie')]
    public function list(EntityManagerInterface $manager){
       //return new Response('les categories');
        //$categories = $manager->getRepository(Categorie::class)->findAll();
		$categories=$this->categorieRepo->findAll();
        return $this->render("categories/index.html.twig",['categories'=>$categories]);
    }

    #[Route('/categorie/detail/{id}', name: 'categorie.details')]
    public function details($id,AnnonceRepository $annonces): Response
    {
        //$categorie=new Categorie(null,null);
		//$categorie->id=1;
		//$annonces=$annonces->findBy(['categorie'=>1]);
	
		$annonces0=$annonces->findAll();
		$annonce;
	
		foreach($annonces0 as $cle=>$val)
		{
			if($annonces0[$cle]=='categorie_id' && $val==1)
			{
				echo "cle ".$cle.' '.$val;
				$annonces=$cle;
			}
		}
       dd($annonces);
        return $this->render('annonces/index.html.twig',compact('annonces'));
        //return $this->render('annonces/annonce.html.twig',['annonces'=>$annonces]);
    }

}
