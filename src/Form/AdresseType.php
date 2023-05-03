<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Country;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label'=> 'Nom',
                'attr' => [
                    'placeholder' => 'votre nom'
                ],
                'required' => true
            ])
            ->add('libelle', TextType::class, [
                'label'=> 'Libelle',
                'attr' => [
                    'placeholder' => 'votre libelle'
                ],
                'required' => true
            ])
            ->add('prenom', TextType::class, [
                'label'=> 'Prenom',
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ],
                'required' => true
            ])
            ->add('compagnie', TextType::class, [
                'label'=> 'Compagnie',
                'attr' => [
                    'placeholder' => 'Votre Compagnie'
                ],
            ])
            ->add('adresse',TextType::class, [
                'label'=> 'Adresse',
                'attr' => [
                    'placeholder' => 'Votre Adresse'
                ],
                'required' => true
            ])
            ->add('postale',TextType::class, [
                'label'=> 'Code postal',
                'attr' => [
                    'placeholder' => 'Votre Code postal'
                ],
                'required' => true
            ])
            ->add('ville',TextType::class, [
                'label'=> 'Ville',
                'attr' => [
                    'placeholder' => 'Votre Ville'
                ],
                'required' => true
            ])
            ->add('pays',CountryType::class, [
                'label'=> 'Pays',
                'attr' => [
                    'placeholder' => 'Votre Pays'
                ],
                'required' => true
            ])
            ->add('telephone',TextType::class, [
                'label'=> 'Téléphone',
                'attr' => [
                    'placeholder' => 'Votre Téléphone'
                ],
                'required' => true
            ])
          //  ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
