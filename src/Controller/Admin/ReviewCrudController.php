<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class ReviewCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Review::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Listes des Avis')
            ->setEntityLabelInSingular('Liste des Avis')

            ->setPageTitle("index", "HoussArtGallery- Administration des Avies des Clients")
            ->setPaginatorPageSize('10')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('message'),
            ImageField::new('imageFile')
                ->setBasePath('public/images/review') // Le chemin d'accès où les images sont stockées (relatif à votre dossier public).
                ->setUploadDir('public/images/review') // Le dossier de destination dans lequel les images téléchargées seront stockées (chemin relatif à la racine de votre projet Symfony).
                ->setUploadedFileNamePattern('[randomhash].[extension]') // Optionnel: modèle de nommage des fichiers pour éviter les conflits.
                ->setRequired(false),
            DateTimeField::new('createdAt')
                ->hideOnForm()
                ->setFormTypeOption('disabled', 'disabled'),
        ];
    }
}
