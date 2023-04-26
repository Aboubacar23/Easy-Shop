<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Filtre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class , [
                'label' => false,
                'required'=> false,
                'attr' => [
                    'placeholder' => 'Chercher un produit',
                    'class' => 'form-control-sm'
                ]
            ])
            ->add('categories', EntityType::class,[
                'class' => Categorie::class,
                'label' => false,
                'required' => false,
                'placeholder' => 'Choisir Catégorie',
                'multiple' => true,
                'expanded' => true
            ])           
            ;
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filtre::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(){
        return "";
    }
}