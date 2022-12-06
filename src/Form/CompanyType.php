<?php

namespace App\Form;

use App\Entity\Agency;
use App\Entity\Adress;
use App\Entity\User;
use App\Entity\Company;
use App\Entity\AgenceAdress;
use App\Entity\AgenceAdressRepository;
use App\Entity\Contract;
use App\Repository\UserRepository;
use App\Repository\AdressRepository;
use App\Repository\ContractRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\DataCollector\EventListener;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la société',
                'empty_data' => '',
                'attr' => ['placeholder' => 'Nom de la société'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('siret', TextType::class, [
                'label' => 'Numéro SIRET',
                'empty_data' => '',
                'attr' => ['placeholder' => 'Numéro SIRET'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('nbEmployees', ChoiceType::class, [
                'label' =>  'Nombre de salariés',
                'empty_data' => '',
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
            ->add('phoneNumber', TextType::class, [
                'label' => 'Numéro de téléphone',
                'empty_data' => '',
                'attr' => ['placeholder' => 'Numéro de téléphone'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => "Mail",
                'empty_data' => '',
                'attr' => ['placeholder' => ' '],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                    new Email([
                        'message' => 'Please enter an email valid',
                    ]),
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'attr'  =>  ['class' => ''],
                'empty_data' => '',
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
            ->add('user', EntityType::class, [
                'label' => 'Adhérent/partenaire',
                'empty_data' => '',
                'class' => User::class,
                'query_builder' => function (UserRepository $getusers) {
                    return $getusers->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'email',
            ])
            ->add('description', CKEditorType::class, [
                'required' => false,
                'empty_data' => '',
                'label' => 'Descrire la société',
                /*'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ])
                ],*/
            ])
            ->add('metier', CKEditorType::class, [
                'required' => false,
                'empty_data' => '',
                /*'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ])
                ],*/
            ])
            ->add('services', CKEditorType::class, [
                'required' => false,
                'empty_data' => '',
                /*'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ])
                ],*/
            ])
            ->add('agency', EntityType::class, [
                'mapped' => false,
                'empty_data' => '',
                'label' =>  'Comptoir',
                'class' => Agency::class,
                'placeholder' => 'Selectionnez',
                    //'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            /*->add('contract', EntityType::class, [
                'class' => Contract::class,
                'query_builder' => function (ContractRepository $getcontract) {
                    return $getcontract->createQueryBuilder('c')
                        ->orderBy('c.id', 'DESC');
                },
                'choice_label' => 'commercial',
            ])*/
        ;

        $builder->get('agency')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {

                $form = $event->getForm();

                $this->addAdressFields($form->getParent(), $form->getData());
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {

                $data = $event->getData();
                /* @var $adress AgenceAdress */
                $adress = $data->getAgenceadresse(); //dd(adress);

                $form = $event->getForm();

                if ($adress){

                    $agency = $adress->getAgence();

                    $this->addAdressFields($form, $agency);

                    $form->get('agency')->setData($agency);
                    $form->get('agenceadresse')->setData($adress);

                }else{
                    $this->addAdressFields($form, null);
                }
            }
        );
    }

    /**
     * Rajoute un champ de type AdressType au formulaire
     * 
     * @param Agency $agence
     * @return void
     */
    private function addAdressFields($form, ?Agency $agence){

        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'agenceadresse', 
            EntityType::class, 
            null,
            [
                'label' => 'Agence',
                'class' =>  AgenceAdress::class,
                'auto_initialize'   =>  false,
                'placeholder'   =>  $agence ? 'Sélectionnez une agence' : 'Sélectionnez un comptoir',
                'choices'   =>  $agence ? $agence->getAgenceAdresses() : [],
                'choice_label' => 'street',
            ]
        );

        $form->add($builder->getForm());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
