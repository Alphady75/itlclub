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
use Symfony\Component\Validator\Constraints\Email;

class EditUserFormType extends AbstractType
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
            ->add('numeroCompte', TextType::class, [
                'label' => 'Numéro de carte',
                'attr' => ['placeholder' => 'Numéro de carte'],
                'required' => false,
            ])
            // ->add('number', TextType::class, [
            //     'label' => 'Numéro de carte préselectionné',
            //     'attr' => ['placeholder' => 'Numéro de carte préselectionné'],
            //     'required' => false,
            // ])
            ->add('validateNumCompte', CheckboxType::class, [
                'required' => false,
                'label' =>  'Validé le numéro de carte',
            ])
            ->add('telephone', TextType::class, [
                'required'  => false,
                'label' => 'Téléphone',
                'attr' => ['placeholder' => 'Téléphone...'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
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
            ->add('job', TextType::class, [
                'required'  => false,
                'label' =>  'Profession',
                'attr'  =>  ['placeholder' => "Profession..."]
            ])
            ->add('isVerified', CheckboxType::class, [
                'label' =>  'Bloquez le compte',
                'required'  => false,
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
