<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            //choix multiple de catégories (voir doc symfo)
            ->add('categories', EntityType::class, [
                'label' => 'Catégorie :',
                'required' => false,
                'placeholder' => 'Sélectionner une ou plusieurs catégories',
                'class' => Categorie::class,
                'choice_label' => 'title',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.enable = :enable')
                        ->setParameter('enable', true)
                        ->orderBy('c.title', 'ASC');
                },
                'expanded' => false,
                'multiple' => true,
                //uniquement en relation ManyToMany car le setter n'existe pas
                'by_reference' => false,
                'autocomplete' => true,
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
            'sanitize_html' => true,
        ]);
    }
}
