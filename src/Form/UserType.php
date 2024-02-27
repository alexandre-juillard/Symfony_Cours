<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom :',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Bobby'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom :',
                'required' =>false,
                'attr' => [
                    'placeholder' => 'Carlson'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'email :',
                'required' => false,
                'attr' => [
                    'placeholder' => 'bobby@test.com'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'mapped' => false,
                'invalid_message' => 'Les mot de passe ne correspondent pas.',
                'first_options'  => [
                    'label' => 'Mot de passe :',
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'max' => 4096
                        ]),
                        new Assert\Regex(
                             pattern: '/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,16}$/' 
                        ),
                    ],
                    'help' => 'Le mot de passe doit contenir au minimum 1 lettre majuscule, 1 lettre minuscule, 1 chiffre et un caractère spécial.',
                ],
                'second_options' => [
                    'label' => 'Confirmez le mot de passe :', 
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

} 