<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class UserOptionsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('homepage', ChoiceType::class, [
                'label' => "Choix de la page d'accueil",
                'choices' => [
                    'Accueil' => 0,
                    'Consommations' => 1,
                    'Mon profil' => 2,
                    'Tableau de bord' => 3
                ]
            ]);

        // $builder
        //     ->add('autoSober', ChoiceType::class, [
        //         'label' => 'Ajouter les jours de sobriété automatiquement (en bêta test pour le moment)',
        //         'choices' => [
        //             'Oui' => 0,
        //             'Non' => 1
        //         ]
        //     ]);

        $builder
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Enregistrer',
                    'attr' => [
                        'class' => 'custom-btn'
                    ]
                ]
            );
    }
}
