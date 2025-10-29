<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, ['attr' => ['placeholder' => 'le titre']])
            ->add('description')
            ->add('lieu')
			->add('remuneration')
            // ->add('date', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('ajout', SubmitType::class)
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'expanded' => true
            ])
            ->add('image', ImageType::class)
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
            'button_label'=>'Submit',
        ]);
    }
}
