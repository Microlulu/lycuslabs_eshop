<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, options: [
            'attr'=>['placeholder'=> "Firstname"]
        ])
        ->add('lastname', TextType::class, [
            'attr'=>['placeholder'=> "Lastname"]
        ])
        ->add('email', EmailType::class, options: [
        'attr'=> [
            'placeholder'=> "Email"
            ]
        ])
        ->add('message', TextareaType::class, options: [
        'attr'=> [
            'placeholder'=> "Message",
            'class' => "formContactMessage"
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
