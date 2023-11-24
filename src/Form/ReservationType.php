<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('operationAt', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
                'label' => 'Date de Operation',
                'attr' => [
                    'class' => 'form-control'
                ]


            ])
            ->add('time', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Heurs',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('nbPassager')
            ->add('nbBagage')
            ->add('flight')
            ->add('remarque');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
