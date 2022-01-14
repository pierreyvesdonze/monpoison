<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'input'
                ]
                ])
            ->add('content', CKEditorType::class, [
                    'attr' => [
                        'class' => "input-textarea"
                    ]
                ])
            ->add('date', DateType::class)
            ->add('isPublished', ChoiceType::class, [
                'choices' => [
                    'Oui' => 0,
                    'Non' => 1
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'custom-btn'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
