<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Products::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Listes des Produits')
            ->setEntityLabelInSingular('Liste des Produits')

            ->setPageTitle("index", "HoussArtGallery- Administration des Ventes")
            ->setPaginatorPageSize('10')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('Name'),
            TextField::new('size')
                ->hideOnIndex(),
            IntegerField::new('price')
                ->hideOnIndex(),
            TextField::new('artist'),
            TextField::new('category'),
            TextEditorField::new('property'),
            TextField::new('imageFile')
                ->setFormType(VichImageType::class)->onlyWhenCreating(),
            ImageField::new('imageFile')
                ->setBasePath('public/uploads/images/')->onlyOnIndex()
                ->setRequired(false),
            DateTimeField::new('createdAt')
                ->hideOnForm()
                ->setFormTypeOption('disabled', 'disabled'),
        ];
    }
}
