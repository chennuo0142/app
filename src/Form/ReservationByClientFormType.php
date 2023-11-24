<?php

namespace App\Form;

use App\Entity\ReservationByClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReservationByClientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom'
            ])
            ->add('phone', null, [
                'label' => 'Numéros de téléphone'
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir une adresse email'
                    ])
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]

            ])
            ->add('service', ChoiceType::class, [
                'choices'  => [
                    'Transfert Paris Aeroport' => 'Transfert Paris Aeroport',
                    'Transfert Paris' => 'Transfert Paris',
                    'Autres' => 'Autres',
                ],
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
                'label' => 'Date',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Date'
                ]


            ])
            ->add('time', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heurs',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('nbPassagers', null, [
                'label' => 'Nombre de passagers'
            ])
            ->add('nbBagages', null, [
                'label' => 'Nombre de bagages'
            ])
            ->add('numFlight', null, [
                'label' => 'Numéros de vol / train'
            ])
            ->add('starAdress', null, [
                'label' => 'Adresse de départ'
            ])
            ->add('endAdress', null, [
                'label' => 'Adresse d\'arrivé'
            ])
            ->add('messages', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'tinymce'],
                'label' => 'Messages (optionel)'
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationByClient::class,
        ]);
    }
}
