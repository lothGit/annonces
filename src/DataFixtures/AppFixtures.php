<?php

namespace App\DataFixtures;


use DateTime;
use Faker\Factory;
use App\Entity\Image;
use DateTimeImmutable;
use App\Entity\Categorie;
use App\Entity\Annonce;
use App\Entity\Commentaire;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $faker->addProvider(new \Xvladqt\Faker\LoremFlickrProvider($faker));
        // l'entité: categorie
        // l'entité: image
        // l'entité: annonce
        // l'entité: commentaire

        $categories = ["annonce sur Symfony", "annonce sur Drupal", "annonce sur Laravel"];
        /*for ($i = 1; $i <= 10; $i++) {
            $categorie = new Categorie();
            $categorie->setNom($faker->randomElement($categories));
            $manager->persist($categorie);
            $this->addReference('categorie' . $i, $categorie);
        }*/

        $cat = [];
        for ($i = 0; $i <3 ; $i++) {
            $categorie = new Categorie();
            $categorie->setNom($categories[$i]);
            $manager->persist($categorie);
            $cat[] = $categorie;
        }

        for ($i = 1; $i <= 10; $i++) {
            $image = new Image();
             $fichier = "https://blog.1001salles.com/wp-content/uploads/2015/04/preparer-sa-salle.jpg";
            // $fichier = $faker->image($dir = '/tmp', $width = 640, $height = 480);
            // $fichier = $faker->image($width=640, $height=480, ['cats']);
            //$fichier = $faker->image(null, $width = 640, $height=480, ['cats'], true, true, true); 
            $infos = pathinfo($fichier);
            $nomFichier = $infos['basename'];
            $repertoire = 'public/uploads/images/' . $nomFichier;
            $url = 'uploads/images/' . $nomFichier;
            copy($fichier, $repertoire); // permet de copier un fichier dans un repertoire
            $image->setUrl($url);
            $image->setAlt($nomFichier);

            $file = new UploadedFile($repertoire, $nomFichier);
            $image->setFile($file);
            $manager->persist($image);
            $this->addReference('image' . $i, $image);
        }

        for ($i = 1; $i <= 10; $i++) {
            $annonce = new Annonce();
            $annonce->setTitre($faker->name)
                ->setDescription($faker->text)
                ->setLieu($faker->city)
                ->setPublishAt(new DateTimeImmutable())
                ->setRemuneration('3000.00')
                //->setCategorie($this->getReference('categorie'.$i))
                ->setCategorie($faker->randomElement($cat))
                ->setImage($this->getReference('image' . $i))
                ->setFavorite(0);
            $manager->persist($annonce);
            $this->addReference('annonce' . $i, $annonce);
        }

        for ($i = 1; $i <= 10; $i++) {
            $commentaire = new Commentaire;
            $commentaire->setPseudo($faker->title)
                ->setContent($faker->text)
                ->setPublishedAt(new DateTimeImmutable())
                ->setannonce($this->getReference('annonce' . $i));
            $manager->persist($commentaire);
        }

        $manager->flush();
    }
}