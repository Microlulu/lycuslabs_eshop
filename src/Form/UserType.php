<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Firstname",
                    'class' => "input_forms"]
                ])
            ->add('lastname',TextType::class, options: [
        'label' => false,
        'attr'=> [
            'placeholder'=> "Lastname",
            'class' => "input_forms"]
            ])
            ->add('password', PasswordType::class, options: [
                'label'=> false,
                'attr' => [
                    'placeholder' => "Password",
                    'class' => "input_forms"
                ]
            ])
            ->add('email', EmailType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Email",
                    'class' => "input_forms"]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
