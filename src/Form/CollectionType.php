<?php

namespace App\Form;

use App\Entity\Colletion;
use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CollectionType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control fs-4',
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
            ->add('artist', TextType::class, [
                'attr' => [
                    'class' => 'form-control fs-4',
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
                    'class' => 'form-control fs-4',
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
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control fs-4',
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
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control fs-4',
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
            ->add('imageFile', FileType::class, [
                'attr' => [
                    'class' => 'form-control fs-4'
                ],
                'label' => 'Image de la Collection',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('artistImageFile', FileType::class, [
                'attr' => [
                    'class' => 'form-control fs-4'
                ],
                'label' => 'Photo de L\'artiste',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('isFavorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label' => 'Favoris ?',
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'constraints' => [
                    new Assert\NotNull()
                ],
                'required' => false
            ])
            ->add('isPublic', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label' => 'Public ?',
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'constraints' => [
                    new Assert\NotNull()
                ],
                'required' => false
            ])
            ->add('products', EntityType::class, [
                'class' => Products::class,
                'query_builder' => function (ProductsRepository $r) {
                    return $r->createQueryBuilder('i')
                        ->where('i.user = :user')
                        ->orderBy('i.name', 'ASC')
                        ->setParameter('user', $this->token->getToken()->getUser());
                },
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', submitType::class, [
                'attr' => [
                    'class' => 'btn btn-warning mt-2'
                ],
                'label' => 'Créer une nouvelle collection'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Colletion::class,
        ]);
    }
}
