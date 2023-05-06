<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class CommandeCrudController extends AbstractCrudController
{
    //pour configurer les action
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add('index', 'detail');
    }

    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
          //  DateTimeField::new('dateCreation', 'Passée le'),
            TextField::new('user.getFullName', 'Client'),
            MoneyField::new('total')->setCurrency('EUR'),
            TextField::new('nom_transporteur'),
            TextField::new('adresse'),
            BooleanField::new('isPaie', 'Payés'),
        ];
    } 

}
