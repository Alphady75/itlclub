<?php

namespace App\Form;

use App\Entity\AgenceAdress;
use App\Entity\Agency;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserCompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civilite', ChoiceType::class, [
                'label' =>  'Civilité',
                'choices'   =>  [
                    'Monsieur' =>  'Monsieur',
                    'Madame'    =>  'Madamme',
                ],
                'expanded' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom(s)',
                'attr' => ['placeholder' => 'Nom...'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Prénom(s)',
                'attr' => ['placeholder' => 'prénom...'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('job', TextType::class, [
                'label' => 'Fonction',
                'attr' => ['placeholder' => 'Fonction...'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' =>  'Rôle',
                'choices'   =>  [
                    'Administrateur' =>  'ROLE_ADMIN',
                    'Adhérent'    =>  'ROLE_ADHERANT',
                    'Commercial'    =>  'ROLE_COMMERCIAL',
                ],
                'expanded' => true,
                'multiple' => true
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => ['placeholder' => 'Téléphone...'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('agenceadresse', EntityType::class, [
                'label' =>  'Agence',
                'class' => AgenceAdress::class,
                'placeholder' => 'Selectionnez',
                'choice_label' => 'street',
                'multiple' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'attr' => ['placeholder' => 'Adresse e-mail'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                    new Email([
                        'message' => 'Veuillez saisir une adresse e-mail',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Nouveau mot de passe...'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir votre mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit avoir en minimum {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Confirmation du mot de passe...'],
                    'label' => 'Confirmer le mot de passe',
                ],
                'invalid_message' => 'Les deux mots de passe ne correspondent pas.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
            ])
            ->add('isVerified', CheckboxType::class, [
                'required' => false,
                'label' => 'Activer le compte'
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
