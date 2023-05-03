<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Transporteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseCommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        //dd($options);
        $builder
            ->add('adresses', EntityType::class, [
                'label' => "Choisir mon adresse de livraison",
                'required'  => true,
                'class' => Adresse::class,
                'choices' => $user->getAdresses(),
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('transporteur', EntityType::class, [
                'label' => "Choisir mon transporteur",
                'required'  => true,
                'class' => Transporteur::class,
                'multiple' => false,
                'expanded' => true,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => array(),
        ]);
    }
}
