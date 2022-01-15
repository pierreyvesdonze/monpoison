<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EthylotestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sex', ChoiceType::class, [
                'label' => 'Êtes-vous',
                'choices' => [
                    'Un homme' => 0,
                    'Une femme' => 1
                ]
            ])
            ->add('weight', IntegerType::class, [
                'label' => 'Quel-est votre poids ?',
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quelle quantité en cl avez-vous consommé ?',
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('degree', NumberType::class, [
                'label' => "Quel était le taux d'alcool (en degrés) contenu dans votre boisson ?",
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'custom-btn'
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
