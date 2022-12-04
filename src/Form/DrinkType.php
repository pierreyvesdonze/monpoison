<?php

namespace App\Form;

use App\Entity\Alcool;
use App\Entity\Drink;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DrinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('alcool', EntityType::class, [
                'label' => 'Alcool consommé',
                'class' => Alcool::class,
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('quantity', IntegerType::class, [
                'label' => "Quantité (en unités d'alcool)",
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('date', DateType::class, [
                'attr' => [
                    'class' => 'input'
                ],
            'data' => new \DateTime('now'),
            'widget' => 'single_text'
            ])
            ->add('cost', NumberType::class, [
                'scale' => 2,
                'label' => 'Argent dépensé',
                'attr' => [
                    'class' => 'input',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'custom-btn'
                ]
            ])
            ->add('word', TextType::class, [
                'label' => 'Ecrivez juste un seul mot',
                'attr' => [
                    'maxlength' => 32
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Drink::class,
        ]);
    }
}
