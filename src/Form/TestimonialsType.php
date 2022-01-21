<?php

namespace App\Form;

use App\Entity\Testimonials;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestimonialsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'class' => 'input'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'message',
                'attr' => [
                    'class' => 'input-textarea'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Publier',
                'attr' => [
                    'class' => 'custom-btn'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testimonials::class,
        ]);
    }
}
