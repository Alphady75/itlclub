<?php

namespace App\Form;

use App\Entity\Offres;
use App\Entity\User;
use App\Entity\Company;
use App\Entity\CategorieOffre;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use App\Repository\CategorieOffreRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class OffresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Titre de l'offre",
                'attr' => ['placeholder' => "Titre de l'offre"],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'attr'  =>  ['class' => ''],
                'label'     =>  'Joindre une image (png, jpg, jpeg)',
                'required'  =>  false,
                'allow_delete' =>  false,
                'download_label'     =>  false,
                'image_uri'     =>  false,
                'download_uri'     =>  false,
                'imagine_pattern'   =>  'medium_size',
                /*'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ])
                ],*/
            ])
            ->add('description', TextareaType::class, [
                'label' => "Petite description",
                /*'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ])
                ],*/
            ])
            ->add('contenu', CKEditorType::class, [
                /*'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ])
                ],*/
            ])
            ->add('user', EntityType::class, [
                'label' =>  "Partenaire",
                'class' => User::class,
                'query_builder' => function (UserRepository $getpartenaires) {
                    return $getpartenaires->createQueryBuilder('u')
                        ->orderBy('u.email', 'ASC')
                        ->andWhere('u.partenaire = 1');
                },
                'choice_label' => 'name',
            ])
            ->add('categorieoffre', EntityType::class, [
                'label' =>  "Catégorie",
                'class' => CategorieOffre::class,
                'query_builder' => function (CategorieOffreRepository $getcategories) {
                    return $getcategories->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('visibility', CheckboxType::class, [
                'required' => false,
                'label' =>  'Accésible par tous',
            ])
            ->add('partenaireInfo1', TextareaType::class, [
                'attr' => ['rows' => 5],
                'label' => false,
                'required' => false,
                /*'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ])
                ],*/
            ])
            ->add('partenaireInfo2', TextareaType::class, [
                'attr' => ['placeholder' => "Contenu 2"],
                'attr' => ['rows' => 5],
                'required' => false,
                'label' => false,
                /*'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ])
                ],*/
            ])
            ->add('partenaireInfoVisibility', CheckboxType::class, [
                'required' => false,
                'label' =>  'Afficher ces informations sur le site',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offres::class,
        ]);
    }
}
