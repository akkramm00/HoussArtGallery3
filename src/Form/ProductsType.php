<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-2'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Prix',
                'label_attr' => [
                    'class' => 'form-label mt-2'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\NotBlank()
                ]
            ])
            ->add('size', textType::class,  [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Taille',
                'label_attr' => [
                    'class' => 'form-label mt-2'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('property', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '2000'
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label mt-2'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 2000]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('artist', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Artiste',
                'label_attr' => [
                    'class' => 'form-label mt-2'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 100]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('category', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '200'
                ],
                'label' => 'Categorie',
                'label_attr' => [
                    'class' => 'form-label mt-2'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            // ->add('imageFile', FileType::class, [
            //     'attr' => [
            //         'class' => 'form-control'
            //     ],
            //     'label' => 'Image du produit',
            //     'label_attr' => [
            //         'class' => 'form-label mt-4'
            //     ]
            // ])
            ->add('isPublic', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label' => 'Public',
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'constraints' => [
                    new Assert\NotNull()
                ],
                'required' => true
            ])
            ->add('submit', submitType::class, [
                'attr' => [
                    'class' => 'btn btn-warning mt-2'
                ],
                'label' => 'Créer un nouveau produit'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
