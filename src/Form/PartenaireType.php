<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Agency;
use App\Entity\AgenceAdress;
use App\Repository\AgencyRepository;
use App\Repository\AgenceAdressRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\DataCollector\EventListener;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PartenaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('conseiller', TextType::class, [
            'label' => false,
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est requis',
                ]),
            ],
        ])
        ->add('societe', TextType::class, [
            'label' => false,
            'attr' => ['placeholder' => ''],
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est requis',
                ]),
            ],
        ])
        ->add('agency', EntityType::class, [
            'mapped' => false,
            'label' =>  false,
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
        ->add('numsiret', TextType::class, [
            'label' => false,
            'attr' => ['placeholder' => ' '],
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est requis',
                ]),
            ],
        ])
        ->add('postaladresse', TextType::class, [
            'label' => false,
            'attr' => ['placeholder' => ''],
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est requis',
                ]),
            ],
        ])
        ->add('postalcode', IntegerType::class, [
            'label' => false,
            'attr' => ['placeholder' => ' '],
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est requis',
                ]),
            ],
        ])
        ->add('ville', TextType::class, [
            'label' => false,
            'attr' => ['placeholder' => ' '],
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est requis',
                ]),
            ],
        ])
        ->add('salaries', ChoiceType::class, [
            'mapped' => false,
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
        ->add('name', TextType::class, [
            'label' => false,
            'attr' => ['placeholder' => ' '],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est requis',
                ]),
            ],
        ])
        ->add('lastname', TextType::class, [
            'label' => false,
            'attr' => ['placeholder' => ' '],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est requis',
                ]),
            ],
        ])
        ->add('telephone', TextType::class, [
            'label' => false,
            'attr' => ['placeholder' => ' '],
            'constraints' => [
                new NotBlank([
                    'message' => 'Ce champ est requis',
                ]),
            ],
        ])
        ->add('email', EmailType::class, [
            'label' => false,
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
        ->add('company_email', EmailType::class, [
            'label' => false,
            'mapped' => false,
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
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Nouveau mot de passe...'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit avoir en minimum {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'label' => false,
            ],
            'second_options' => [
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Confirmation du mot de passe...'],
                'label' => false,
            ],
            'invalid_message' => 'Les deux mots de passe ne correspondent pas.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
            'mapped' => false,
        ])
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
                'label' => false,
                'class' =>  AgenceAdress::class,
                'auto_initialize'   =>  false,
                'placeholder'   =>  $agence ? 'Sélectionnez une agence' : 'Sélectionnez un comptoir',
                'choices'   =>  $agence ? $agence->getAgenceAdresses() : [],
                'choice_label' => 'street',
                'required'  =>  false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ]
        );

        $form->add($builder->getForm());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}