<?php

namespace App\Form;

use App\Entity\UserSetting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('showBank', CheckboxType::class, [
                'label' => 'Affichage de <RIB>',
                'required' => false,
            ])
            ->add('tva', CheckboxType::class, [
                'label' => 'TVA applicable',
                'required' => false,
            ])
            ->add('noTvaText', CheckboxType::class, [
                'label' => 'Afficher TVA non Appliquable',
                'required' => false,
            ])
            ->add('textLawTva', TextType::class, [
                'label' => 'Text de loi regissant la TVA',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserSetting::class,
        ]);
    }
}
