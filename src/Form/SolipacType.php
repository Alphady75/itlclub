<?php

namespace App\Form;

use App\Entity\Agency;
use App\Entity\Solipac;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class SolipacType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('agency', EntityType::class, [
                'label' =>  "Choix de l’agence",
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
            ->add('conseiller', TextType::class, [
                'label' => 'Conseiller',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('societe', TextType::class, [
                'label' => 'Nom de la société',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('societeTel', TextType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('societeEmail', EmailType::class, [
                'label' => 'Mail',
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
            ->add('societeAdresse', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('societeSiret', TextType::class, [
                'label' => 'SIRET',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('societeDateCreation', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de creation de la société',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('societeEffectif', NumberType::class, [
                'label' => 'Effectif',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('societeQualification', ChoiceType::class, [
                'label' =>  'Qualification',
                'choices'   =>  [
                    'Capacité fluides' =>  'capacite-fluides',
                    'QUALI PAC' =>  'QUALI-PAC',
                    'QUALI PV' =>  'QUALI-PV',
                    'QUALI BOIS' =>  'QUALI-BOIS',
                ],
                'expanded' => true,
                'multiple' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('zoneGeoInter', TextType::class, [
                'label' => 'Zone géographique d\'intervention ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('gerant', TextType::class, [
                'label' => 'Nom du Gérant',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('gerantTel', TextType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('gerantMail', EmailType::class, [
                'label' => 'Mail',
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
            ->add('ape', TextType::class, [
                'label' => 'APE',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('ca2021', NumberType::class, [
                'label' => 'CA 2021',
                //'currency' => 'EUR',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('composition', TextType::class, [
                'label' => 'Composition',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('pacAirEau', CheckboxType::class, [
                'label' => 'PAC Air/Eau',
                'required' => false,
            ])
            ->add('pacAirEauMarque', TextType::class, [
                'label' => 'Marque',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('pacAirEauVolume', TextType::class, [
                'label' => 'Volume',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('pacAirAir', CheckboxType::class, [
                'label' => 'PAC Air/Aire',
                'required' => false,
            ])
            ->add('pacAirAirMarque', TextType::class, [
                'label' => 'Marque',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('pacAirAirVolume', TextType::class, [
                'label' => 'Volume',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('ballonThermo', CheckboxType::class, [
                'label' => 'Ballon Thermo',
                'required' => false,
            ])
            ->add('ballonThermoMarque', TextType::class, [
                'label' => 'Marque',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('ballonThermoVolume', TextType::class, [
                'label' => 'Volume',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('biomasse', CheckboxType::class, [
                'label' => 'Biomasse',
                'required' => false,
            ])
            ->add('biomasseMarque', TextType::class, [
                'label' => 'Marque',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('biomasseVolume', NumberType::class, [
                'label' => 'Volume',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('planChauffant', CheckboxType::class, [
                'label' => 'Plancher chauffant',
                'required' => false,
            ])
            ->add('planChauffantMarque', TextType::class, [
                'label' => 'Marque',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('planChauffantVolume', NumberType::class, [
                'label' => 'Volume',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('adoucisseur', CheckboxType::class, [
                'label' => 'Adoucisseur',
                'required' => false,
            ])
            ->add('adoucisseurMarque', TextType::class, [
                'label' => 'Marque',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('adoucisseurVolume', NumberType::class, [
                'label' => 'Volume',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('ventilation', CheckboxType::class, [
                'label' => 'Ventilation',
                'required' => false,
            ])
            ->add('ventilationMarque', TextType::class, [
                'label' => 'Marque',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('ventilationVolume', NumberType::class, [
                'label' => 'Vollumer',
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('renovation', CheckboxType::class, [
                'label' => 'Renovation',
                'required' => false,
            ])
            ->add('renovPourcentage', NumberType::class, [
                'label' => '%',
                'required' => false,
            ])
            ->add('neuf', CheckboxType::class, [
                'label' => 'Neuf',
                'required' => false,
            ])
            ->add('neufPourcentage', NumberType::class, [
                'label' => '%',
                'required' => false,
            ])
            ->add('volumeAchat', NumberType::class, [
                'label' => "Volume d'achat (TTC/MOIS) €",
                //'currency' => 'EUR',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('nbJours', ChoiceType::class, [
                'label' =>  false,
                'choices'   =>  [
                    '30 jours net (coef 1,5)' =>  '30 jours net (coef 1,5)',
                    '45 jours net (coef 2)' =>  '45 jours net (coef 2)',
                    '30 jours FDM (coef 2,5)' =>  '30 jours FDM (coef 2,5)',
                    '30 jours FDM le 15 (coef 3)' => '30 jours FDM le 15 (coef 3)',
                ],
                'expanded' => true,
                'multiple' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('encoursDemande', NumberType::class, [
                'label' => "Encours demande (AxB)",
                //'currency' => 'EUR',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('demandeMethode', ChoiceType::class, [
                'label' =>  false,
                'choices'   =>  [
                    'LCR directe' =>  'LCR directe',
                    'Virement' =>  'Virement',
                    'Comptant' =>  'Comptant',
                ],
                'expanded' => true,
                'multiple' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('formationCom', ChoiceType::class, [
                'label' =>  'FORMATIONS COMMERCIALES',
                'choices'   =>  [
                    'Contexte énergétique' =>  'Contexte énergétique',
                    'PAC Air/Eau' =>  'PAC Air/Eau',
                    'Pac Air/Air' =>  'Pac Air/Air',
                    'Photovoltaïque' =>  'Photovoltaïque',
                    'Biomasse' =>  'Biomasse'
                ],
                'expanded' => true,
                'required' => 'false',
                'multiple' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('formationQua', ChoiceType::class, [
                'label' =>  'FORMATIONS QUALIFIANTES',
                'choices'   =>  [
                    'Capacités fluides' =>  'Capacités fluides',
                    'Quali PAC' =>  'Quali PAC',
                    'Quali PV' =>  'Quali PV',
                    'Quali bois' =>  'Quali bois',
                    'Prélude Yutaki (extension garantie 5ans)' =>  'Prélude Yutaki (extension garantie 5ans)',
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('accompTech', ChoiceType::class, [
                'label' =>  'ACCOMPAGNEMENT TECHNIQUE',
                'choices'   =>  [
                    'Mise en service' =>  'Mise en service',
                    'Sous traitance - pose' =>  'Sous traitance - pose',
                    'Dépannage' =>  'Dépannage',
                    'Maintenance - entretien' =>  'Maintenance - entretien',
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('accompAdmin', ChoiceType::class, [
                'label' =>  'ACCOMPAGNEMENT ADMINISTRATIF',
                'choices'   =>  [
                    'Dossier CEE' =>  'Dossier CEE',
                    'Démarches PV' =>  'Démarches PV',
                    'Mise en conformité' =>  'Mise en conformité',
                    'Audit et affiliation' =>  'Audit et affiliation',
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('accompAssurance', ChoiceType::class, [
                'label' =>  'ACCOMPAGNEMENT ASSURANCE',
                'choices'   =>  [
                    'Décennale PV' =>  'Décennale PV',
                    'Audit assurance' =>  'Audit assurance',
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('besoinApprenti', ChoiceType::class, [
                'label' =>  'BESOIN D’UN APPRENTI',
                'choices'   =>  [
                    'Apprenti technique' =>  'Apprenti technique',
                    'Apprenti commercial' =>  'Apprenti commercial',
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => 'false',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('objClient', TextareaType::class, [
                'label' => 'OBJECTIF AVEC LE CLIENT :',
                'required' => false,
            ])
            ->add('appreciationGen', TextareaType::class, [
                'label' => 'APPRÉCIATION GÉNÉRALE',
                'required' => false,
            ])
            ->add('dateRdv1', DateType::class, [
                'widget' => 'single_text',
                'label' => 'DATE DU 1ER RDV',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('dateRdv2', DateType::class, [
                'widget' => 'single_text',
                'label' => 'DATE RDV2',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('origineContact', ChoiceType::class, [
                'label' =>  'ORIGINE DU CONTACT',
                'choices'   =>  [
                    'Prospection' =>  'Prospection',
                    'Recommandation client' =>  'Recommandation client',
                    'Client Hitachi' =>  'Client Hitachi',
                    'Intellia' =>  'Intellia',
                ],
                'expanded' => true,
                'required' => 'false',
                'multiple' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est requis',
                    ]),
                ],
            ])
            ->add('autreCommentatire', TextareaType::class, [
                'label' => 'AUTRE COMMENTAIRE :',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Solipac::class,
        ]);
    }
}
