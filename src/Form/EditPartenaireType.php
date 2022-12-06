<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Email;

class EditPartenaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom(s)',
            'attr' => ['placeholder' => ' '],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est requis',
                ]),
            ],
        ])
        ->add('lastname', TextType::class, [
            'label' => 'Prénom(s)',
            'attr' => ['placeholder' => ' '],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est requis',
                ]),
            ],
        ])
        ->add('telephone', TextType::class, [
            'label' => 'Téléphone',
            'required' => false,
            'attr' => ['placeholder' => ' '],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est requis',
                ]),
            ],
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'attr' => ['placeholder' => 'Exemple@domail.com'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est requis',
                ]),
                new Email([
                    'message' => 'Veuillez saisir une adresse email valide',
                ]),
            ],
        ])
        ->add('roles', ChoiceType::class, [
            'label' =>  'Rôle',
            'choices'   =>  [
                'Administrateur' =>  'ROLE_ADMIN',
                'Partenaire'    =>  'ROLE_PARTENAIRE',
            ],
            'expanded' => true,
            'multiple' => true
        ])
        ->add('job', TextType::class, [
            'required'  => false,
            'label' =>  'Profession',
            'attr'  =>  ['placeholder' => "Profession..."]
        ])
        ->add('isVerified', CheckboxType::class, [
            'label' =>  'Bloquez le compte',
            'required'  => false,
        ])
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Nouveau mot de passe...'],
                'label' => false,
                'required' => false,
            ],
            'second_options' => [
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Confirmation du mot de passe...'],
                'label' => false,
                'required' => false,
            ],
            'invalid_message' => 'Les deux mots de passe ne correspondent pas.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
            'mapped' => false,
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
