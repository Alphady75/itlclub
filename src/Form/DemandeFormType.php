<?php

namespace App\Form;

use App\Entity\Demande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class DemandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hidenprofil', ChoiceType::class, [
                'label' =>  'Masquer mon profil du répertoire ?',
                'choices'   =>  [
                    'Oui' =>  1,
                    'Non'    =>  0,
                ],
                'expanded' => true,
            ])
            ->add('downloaddata', ChoiceType::class, [
                'label' =>  'Téléchargez vos données ?',
                'choices'   =>  [
                    'Oui' =>  1,
                    'Non'    =>  0,
                ],
                'expanded' => true,
            ])
            ->add('deletedata', ChoiceType::class, [
                'label' =>  'Effacement de vos données?',
                'choices'   =>  [
                    'Oui' =>  1,
                    'Non'    =>  0,
                ],
                'expanded' => true,
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' =>  "Saisissez votre mot de passe actuel pour confirmer l'envoie de votre demande.",
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisissez votre mot de passe actuel',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit avoir en maximum {{ limit }} caractère',
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
            'data_class' => Demande::class,
        ]);
    }
}
