<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AlcoolTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('frequencyComsumption', ChoiceType::class, [
                'label' => "Quelle est la fréquence de votre consommation d'alcool ?",
                'choices' => [
                    'jamais' => 0,
                    '1 fois par mois ou moins' => 1,
                    '2 à 4 fois par mois' => 2,
                    '2 à 3 fois par semaine' => 3,
                    'au moins 4 fois par semaine' => 4
                ],
                'required' => true
            ]);

        $builder
            ->add('drinkByDay', ChoiceType::class, [
                'label' => "Combien de verres d'alcool consommez-vous un jour où vous buvez ?",
                'choices' => [
                    '1 ou 2' => 0,
                    '3 ou 4' => 1,
                    '5 ou 6' => 2,
                    '7 ou 8' => 3,
                    '10 ou plus' => 4
                ],
                'required' => true
            ]);
        $builder
            ->add('fiveDrinksFrequency', ChoiceType::class, [
                'label' => "A quelle fréquence buvez-vous 5 verres ou plus lors d'une occasion ?",
                'choices' => [
                    'jamais' => 0,
                    "moins d'une fois par mois" => 1,
                    'une fois par mois' => 2,
                    'une fois par semaine' => 3,
                    'tous les jours ou presque' => 4
                ],
                'required' => true
            ]);

        $builder
            ->add('stopControl', ChoiceType::class, [
                'label' => "Au cours de l'année écoulée, combien de fois avez-vous constaté que vous n'arriviez plus à vous arrêter après avoir commencé à boire ?",
                'choices' => [
                    'jamais' => 0,
                    "moins d'une fois par mois" => 1,
                    "une fois par mois" => 2,
                    "une fois par semaine" => 3,
                    "tous les jours ou presque" => 4
                ],
                'required' => true
            ]);

        $builder
            ->add('failAttempt', ChoiceType::class, [
                'label' => "Au cours de l'année écoulée, combien de fois votre consommation d'alcool vous a-t-elle empêché de faire ce qui était attendu de vous ?",
                'choices' => [
                    'jamais' => 0,
                    "moins d'une fois par mois" => 1,
                    "une fois par mois" => 2,
                    "une fois par semaine" => 3,
                    "tous les jours ou presque" => 4
                ],
                'required' => true
            ]);

        $builder
            ->add('needFirstDrink', ChoiceType::class, [
                'label' => "Au cours de l'année écoulée, combien de fois avez-vous eu besoin d'un premier verre pour démarrer la journée ?",
                'choices' => [
                    'jamais' => 0,
                    "moins d'une fois par mois" => 1,
                    "une fois par mois" => 2,
                    "une fois par semaine" => 3,
                    "tous les jours ou presque" => 4
                ],
                'required' => true
            ]);

        $builder
            ->add('regrets', ChoiceType::class, [
                'label' => "Au cours de l'année écoulée, combien de fois avez-vous eu des remords après avoir bu ?",
                'choices' => [
                    'jamais' => 0,
                    "moins d'une fois par mois" => 1,
                    "une fois par mois" => 2,
                    "une fois par semaine" => 3,
                    "tous les jours ou presque" => 4
                ],
                'required' => true
            ]);

        $builder
            ->add('noMemory', ChoiceType::class, [
                'label' => "Au cours de l'année écoulée, combien de fois avez-vous été incapable de vous souvenir de la soirée précédente car vous aviez bu ?",
                'choices' => [
                    'jamais' => 0,
                    "moins d'une fois par mois" => 1,
                    "une fois par mois" => 2,
                    "une fois par semaine" => 3,
                    "tous les jours ou presque" => 4
                ],
                'required' => true
            ]);

        $builder
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'custom-btn'],
                'label' => 'Enregistrer'
            ]);
    }
}
