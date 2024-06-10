<?php
// CRUD controller pour les Categories //
namespace App\Controller\Admin;

use App\Entity\Categories;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;


    // CRUD pour categories dans dashboard //
class CategoriesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Categories::class;
    }

    public function configureCrud(Crud $crud) : Crud 
    {
        return $crud
            ->setEntityLabelInPlural('Categories')
            ->setEntityLabelInSingular('Categorie')
            ->setPaginatorPageSize(10);
            
    }
        // ce que l'on voit dans le mode EDIT //
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('nameCategory'),
            
        ];
    }
}
