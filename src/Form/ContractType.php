<?php

namespace App\Form;

use App\Entity\Contract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commercial', TextType::class, [
                'label' => 'Commercial',
                'attr' => ['placeholder' => 'prénom + nom...'],
            ])
            ->add('contractState', CheckboxType::class, [
                'label' => "Actif",
                'required' => false,
            ])
            ->add('authorizedPerson1', TextType::class, [
                'label' => 'Personne authorisée n°1',
                'attr' => ['placeholder' => 'prénom + nom...'],
                'required'  => false,
            ])
            ->add('authorizedPerson2', TextType::class, [
                'label' => 'Personne authorisée n°2',
                'attr' => ['placeholder' => 'prénom + nom...'],
                'required'  => false,
            ])
            ->add('authorizedPerson3', TextType::class, [
                'label' => 'Personne authorisée n°3',
                'attr' => ['placeholder' => 'prénom + nom...'],
                'required'  => false,
            ])
            ->add('banqueName', TextType::class, [
                'label' => 'Banque',
                'attr' => ['placeholder' => ' '],
            ])
            ->add('iban', TextType::class, [
                'label' => 'IBAN',
                'attr' => ['placeholder' => ' '],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('bic', TextType::class, [
                'label' => 'RIB',
                'attr' => ['placeholder' => ' '],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'query_builder' => function (CompanyRepository $getcompanies) {
                    return $getcompanies->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contract::class,
        ]);
    }
}
