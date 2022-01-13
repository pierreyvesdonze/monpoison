<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Gregwar\CaptchaBundle\Type\CaptchaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'required' => true,
                'label' => "Email",
                'attr' => [
                    'class' => 'input',
                ]
            ])
            ->add('subject', TextType::class, [
                'empty_data' => 'Sujet',
                'label' => 'Sujet',
                'attr' => [
                    'class' => 'input',
                ],
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Message',
                'empty_data' => 'Texte',
                'attr' => [
                    'class' => 'input-textarea',
                ],
            ]);
        $builder->add('captcha', CaptchaType::class);

        $builder->add(
            'save',
            SubmitType::class,
            [
                "label" => "Envoyer",
                'attr' => [
                    'class' => 'custom-btn',
                ],
            ]
        );
    }
}
