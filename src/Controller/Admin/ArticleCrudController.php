<?php
// CRUD controller pour les Articles //
namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categories;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


    // CRUD pour articles dans dashboard //
class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
        return Categories::class;
    }
    public function configureCrud(Crud $crud) : Crud 
    {
        return $crud
            ->setEntityLabelInPlural('Articles')
            ->setEntityLabelInSingular('Article')
            ->setPaginatorPageSize(20);
            
    }
        // ce que l'on voit dans le mode EDIT //
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('nameArticle'),
            TextField::new('image'),
            IntegerField::new('price'),
            IntegerField::new('rating'),
            TextareaField::new('description'),
            IntegerField::new('stock'),
            AssociationField::new('categories'),
        ];
        
    }
}
