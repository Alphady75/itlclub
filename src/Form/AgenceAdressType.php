<?php

namespace App\Form;

use App\Entity\AgenceAdress;
use App\Entity\Agency;
use App\Repository\AgencyRepository;
use App\Repository\AgenceAdressRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AgenceAdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('number', IntegerType::class, [
                'label' => 'Numéro',
                'attr' => ['placeholder' => 'Numéro...'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('street', TextType::class, [
                'label' => 'Ruelle / avenue',
                'attr' => ['placeholder' => 'Ruelle / avenue...'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
                'attr' => ['placeholder' => 'code postal...'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
            ])
            ->add('agence', EntityType::class, [
                'required' => false,
                'placeholder' => 'Sélectionnez le comptoir',
                'label' => 'Partenaire',
                'class' => Agency::class,
                'query_builder' => function (AgencyRepository $getagenceadresse) {
                    return $getagenceadresse->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AgenceAdress::class,
        ]);
    }
}
