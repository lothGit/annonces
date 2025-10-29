<?php

namespace App\Tests\Unit;
use App\Entity\Annonce;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeTest extends KernelTestCase
{

    public function getEntity(): Annonce
    {
        return (new Annonce())
            ->setTitre('Annonce #1')
            ->setDescription('Description #1');
    }


    public function testEntityIsValid(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $annonce = $this->getEntity();

        $errors = $container->get('validator')->validate($annonce);

        $this->assertCount(0, $errors);
    }

    public function testInvalidName()
    {
        self::bootKernel();
        $container = static::getContainer();

        $recipe = $this->getEntity();
        $recipe->setTitre('');

        $errors = $container->get('validator')->validate($recipe);
        $this->assertCount(0, $errors);
    }

}
