<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConfidentialiteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('masqueProfil', ChoiceType::class, [
                'label' =>  'Masquer mon profil du répertoire ?',
                'choices'   =>  [
                    'Oui' =>  'Oui',
                    'Non' => 'Non',
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('dawnloadData', ChoiceType::class, [
                'label' =>  'Téléchargez vos données?',
                'choices'   =>  [
                    'Oui' =>  'Oui',
                    'Non' => 'Non',
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('deleteData', ChoiceType::class, [
                'label' =>  'Nombre de salariés',
                'choices'   =>  [
                    '0-4 salaries' =>  1,
                    '5-9 salaries' =>  2,
                    '10 salaries et + ' =>  3,
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' =>  'Saisissez votre mot de passe actuel pour confirmer l’effacement de vos données personnelles.',
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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
