<?php
// CRUD controller pour les User //
namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;


    // CRUD pour user dans dashboard //
class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
        
    }

    public function configureCrud(Crud $crud) : Crud 
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateurs')
            ->setEntityLabelInSingular('Utilisateur')
            ->setPaginatorPageSize(10);
            
    }

        // Fonction pour ne pas pouvoir delete un User , on veut juste EDIT //
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW)
        ;
    }

        // ce que l'on voit dans le mode EDIT //
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('Email')
                ->setFormTypeOption('disabled','disabled'),
            TextField::new('Password')
                ->hideOnIndex()
                ->hideOnForm(),
            TextField::new('pseudo'),
            TextField::new('prenomUser'),
            TextField::new('nomUser'),
            TextField::new('adresse')
                ->hideOnIndex()
                ->hideOnForm(),
            IntegerField::new('codePostal')
                ->hideOnIndex()
                ->hideOnForm(),
            TextField::new('ville')
                ->hideOnIndex()
                ->hideOnForm(),
            ArrayField::new('roles')
                ->hideOnIndex(),
            
        ];
    }
    
} 
