<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Email;

class CreateUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('birthdayDate', DateType::class, [
                'required' => false,
                'label' => 'Date de naissance',
                'widget' => 'single_text',
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
            ->add('numeroCompte', IntegerType::class, [
                'required' => false,
                'label' => 'Numéro de carte',
                'attr' => ['placeholder' => 'Numéro de carte'],
            ])
            ->add('validateNumCompte', CheckboxType::class, [
                'required' => false,
                'label' =>  'Validé le numéro de carte',
            ])
            ->add('email', EmailType::class, [
                'label' =>  'Adresse e-mail',
                'attr'  =>  ['placeholder' => "Adresse Email..."],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                    new Email([
                        'message' => 'Cette valeur ne corespond pas à une adresse email valide' 
                    ])
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Mot de passe...'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Le mot de passe doit avoir en minimum {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Mot de passe',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Confirmation du mot de passe...'],
                    'label' => 'Confirmation du mot de passe',
                ],
                'invalid_message' => 'The password fields must match.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'label' =>  'Rôle',
                'choices'   =>  [
                    'Administrateur' =>  'ROLE_ADMIN',
                    'Adhérent'    =>  'ROLE_ADHERANT',
                ],
                'expanded' => true,
                'multiple' => true
            ])
            ->add('job', TextType::class, [
                'label' =>  'Profession',
                'attr'  =>  ['placeholder' => "Profession..."]
            ])
            ->add('isVerified', CheckboxType::class, [
                'label' =>  'Activez manuellement le compte',
                'required' => false,
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
