<?php

namespace App\Form;

use App\Entity\Alcool;
use App\Entity\Sober;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sober', EntityType::class, [
                'label' => 'Ajouter un jour de sobriété',
                'class' => Alcool::class,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('date', DateType::class, [
                'label' => 'Date de la conso évitée',
                'attr' => [
                    'class' => 'input'
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'custom-btn'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sober::class,
        ]);
    }
}
