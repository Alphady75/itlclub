<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\NotBlank;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre du document',
                'attr' => ['placeholder' => 'Titre du document...'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('documentFile', VichImageType::class, [
                'attr'  =>  ['class' => 'mt-3'],
                'label'     =>  'Joindre un document (pdf)',
                'allow_delete' =>  false,
                'download_label'     =>  false,
                'required'  =>  true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner un document',
                    ])
                ],
            ])
            ->add('company', EntityType::class, [
                'label' => 'Partenaire',
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
            'data_class' => Document::class,
        ]);
    }
}
