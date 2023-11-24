<?php

namespace App\Form;

use App\Entity\ReservationByClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationByClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('phone')
            ->add('email')
            ->add('date')
            ->add('time')
            ->add('starAdress')
            ->add('endAdress')
            ->add('service')
            ->add('driverId')
            ->add('createdAt')
            ->add('nbPassagers')
            ->add('nbBagages')
            ->add('numFlight')
            ->add('messages')
            ->add('userId')
            ->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationByClient::class,
        ]);
    }
}
