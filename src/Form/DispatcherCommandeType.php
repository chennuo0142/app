<?php

namespace App\Form;

use App\Entity\DispatcherCommande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class DispatcherCommandeType extends AbstractType
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
            ->add('article')
            ->add('price')
            ->add('quantity')
            ->add('comment');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DispatcherCommande::class,
        ]);
    }
}
