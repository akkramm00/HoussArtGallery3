<?php

namespace App\Controller\Admin;

use App\Entity\Colletion;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ColletionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Colletion::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Listes des collections')
            ->setEntityLabelInSingular('Liste des collections')

            ->setPageTitle("index", "HoussArtGallery - Administration des Collections")
            ->setPaginatorPageSize('10')
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('name'),
            TextField::new('artist'),
            TextField::new('category'),
            IntegerField::new('price'),
            TextField::new('imageFile')
                ->setFormType(VichImageType::class)->onlyWhenCreating(),
            ImageField::new('imageFile')
                ->setBasePath('public/uploads/images/')->onlyOnIndex()
                ->setRequired(false),
            TextEditorField::new('description'),
            DateTimeField::new('createdAt')
                ->hideOnForm()
                ->setFormTypeOption('disabled', 'disabled'),
        ];
    }
}
