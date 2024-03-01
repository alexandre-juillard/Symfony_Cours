<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Mon Titre ici...'
                ]
            ]) 
            ->add('categories', ChoiceType::class, [
                'label' => 'Categorie(s) :',
                'placeholder' => 'Sélectionner une ou plusieurs catégories',
                'choices' => [
                   'Frontent' => 1,
                   'Backend' => 2,
                ],
                'expanded' => true,
                'multiple' =>true,
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image :',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer l\'image',
                'image_uri' => true,
                'download_uri' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Description de l\'article ici...',
                    'rows' => 10,
                ]
            ])
            ->add('enable', CheckboxType::class, [
                'label' => 'Actif :',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'isAdmin' => false,
        ]);
    }
}