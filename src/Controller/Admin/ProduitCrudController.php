<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
       return [
            TextField::new('nom'),
            TextField::new('sous_titre'),
            SlugField::new('slug')->setTargetFieldName('nom'),
            ImageField::new('illustration')
                ->setBasePath('image_produits')
                ->setUploadDir('public/image_produits')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            MoneyField::new('prix')->setCurrency('EUR'),
            TextareaField::new('description'),
            AssociationField::new('categorie')
       ];
    }

}
