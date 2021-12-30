<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AlcoolTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sex', ChoiceType::class, [
                'label' => 'Êtes-vous un homme ou une femme ?',
                'choices' => [
                    'Femme' => 0,
                    'Homme' => 1
                ],
                'required' => true
            ]);

        $builder
            ->add('age', ChoiceType::class, [
                'label' => "Sélectionnez votre tranche d'âge",
                'choices' => [
                    '18-24 ans' => 0,
                    '25-30 ans' => 1,
                    '31-35 ans' => 2,
                    '36-40 ans' => 3,
                    '41-45 ans' => 4,
                    '46-50ans'  => 5,
                    '51-55 ans' => 6,
                    '56-60 ans' => 7,
                    '61-65 ans' => 8,
                    '66-70 ans' => 9,
                    '+ de 71 ans' => 10
                ],
                'required' => true
            ]);

        $builder
            ->add('weight', ChoiceType::class, [
                'label' => 'Quel-est votre poids (en kg) ?',
                'choices' => [35 =>0, 36 =>1, 37 =>2, 38 =>3, 39 =>4, 40 =>5, 41 =>6, 42 =>7, 43 =>8, 44 =>9, 45 =>10, 46 =>11, 47 =>12, 48 =>13, 49 =>14, 50 =>15, 51 =>16, 52 =>17, 53 =>18, 54 =>19, 55 =>20, 56 =>21, 57 =>22, 58 =>23, 59 =>24, 60 =>25, 61 =>26, 62 =>27, 63 =>28, 64 =>29, 65 =>30, 66 =>31, 67 =>32, 68 =>33, 69 =>34, 70 =>35, 71 =>36, 72 =>37, 73 =>38, 74 =>39, 75 =>40, 76 =>41, 78 =>42, 79 =>43, 80 =>44, 81 =>45, 82 =>46, 83 =>47, 84 =>48, 85 =>49, 86 =>50, 87 =>51, 88 =>52, 89 =>53, 90 =>54, 91 =>55, 92 =>56, 93 =>57, 94 =>58, 95 =>59, 96 =>60, 97 =>61, 98 =>62, 99 =>63, 100 =>64, 101 =>65, 102 =>66, 103 =>67, 104 =>68, 105 =>69, 106 =>70, 107 =>71, 108 =>72, 109 =>73, 110 =>74, 111 =>75, 112 =>76, 113 =>77, 114 =>78, 115 =>79, 116 =>80, 117 =>81, 118 =>82, 119 =>83, 120 =>84, 121 =>85, 122 =>85, 123 =>86, 124 =>87, 125 =>88, 126 =>89, 127 =>90, 128 =>91, 129 =>92, 130 =>93],
                'required' => true
            ]);

        $builder
            ->add('lastDay', ChoiceType::class, [
                'label' => "Quel(s) jour(s) de la semaine précédente avez-vous consommé ?",
                'choices' => [
                    'lundi' => 0,
                    'mardi' => 1,
                    'mercredi' => 2,
                    'jeudi' => 3,
                    'vendredi' => 4,
                    'samedi' => 5,
                    'dimanche' => 6
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => true
            ]);

        $builder
            ->add('lastDrink', IntegerType::class, [
                'label' => "Combien de verres avez-vous bu(s) au cours de la semaine précédente ?",

            ]);

        $builder
            ->add('money', IntegerType::class, [
                'label' => "Combien d'argent (en euros) avez-vous dépensé en alcool la semaine précédente ?",
            ]);

        $builder
        ->add('submit', SubmitType::class, [
            'attr' => ['class' => 'btn btn-secondary'],
        ]);
    }
}
