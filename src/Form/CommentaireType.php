<?php

namespace App\Form;


use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Votre titre ici...',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description :',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Votre description ici...',
                    'rows' => 6,
                ]
            ])
            ->add('rate', RangeType::class, [
                'label' => 'Note :',
                'required' => false,
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                    'step' => 1,
                ]
            ])
            ->add('gdpr', CheckboxType::class, [
                'label' => 'Accepter les CGU',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez accepter les CGU pour poster un commentaire',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
            'sanitize_html' => true,
        ]);
    }
}
