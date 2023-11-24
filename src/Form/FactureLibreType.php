<?php

namespace App\Form;

use App\Entity\FactureLibre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FactureLibreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
                'label' => 'Date*',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Date'
                ]
            ])
            ->add('name', null, [
                'label' => 'Votre nom*',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Martin'
                ]
            ])
            ->add('company', null, [
                'label' => 'Societe',
                'attr' => [
                    'class' => 'form-control',

                ]



            ])
            ->add('adress', null, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '12 Rue de Paris'
                ]
            ])
            ->add('zipCode', null, [
                'label' => 'Code Postal',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '75007'
                ]
            ])
            ->add('city', null, [
                'label' => 'Ville',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Paris'
                ]
            ])
            ->add('country', null, [
                'label' => 'Pays',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'France'
                ]
            ])

            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir une adresse email'
                    ])
                ],
                'required' => true,
                'label' => 'Email client',
                'attr' => [
                    'class' => 'form-control'
                ]

            ])
            ///////////////////////////////Article////////////////////////////
            ->add('article1', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Paris-transfert'
                ]
            ])
            ->add('article2', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'

                ]
            ])
            ->add('article3', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'

                ]
            ])
            ->add('article4', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'

                ]
            ])
            ->add('price1', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '60'

                ]
            ])
            ->add('price2', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'

                ]
            ])
            ->add('price3', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'

                ]
            ])
            ->add('price4', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'

                ]
            ])
            ->add('quantite1', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '1'

                ]
            ])
            ->add('quantite2', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'

                ]
            ])
            ->add('quantite3', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'

                ]
            ])
            ->add('quantite4', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'

                ]
            ])
            ->add('tva1', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '10'

                ]
            ])
            ->add('tva2', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'

                ]
            ])
            ->add('tva3', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'

                ]
            ])
            ->add('tva4', null, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'

                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FactureLibre::class,
        ]);
    }
}
