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
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;


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
            IntegerField::new('price'),
            TextField::new('artist'),
            TextField::new('category'),
            TextEditorField::new('property'),
            ImageField::new('imageFile')
                ->setBasePath('public/images/products') // Le chemin d'accès où les images sont stockées (relatif à votre dossier public).
                ->setUploadDir('public/images/products') // Le dossier de destination dans lequel les images téléchargées seront stockées (chemin relatif à la racine de votre projet Symfony).
                ->setUploadedFileNamePattern('[randomhash].[extension]') // Optionnel: modèle de nommage des fichiers pour éviter les conflits.
                ->setRequired(false),
            DateTimeField::new('createdAt')
                ->hideOnForm()
                ->setFormTypeOption('disabled', 'disabled'),
        ];
    }
}
