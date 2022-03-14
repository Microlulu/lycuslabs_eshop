<?php

namespace App\Form;

use App\Entity\Messages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// ce formulaire est reserver au membre dans leur espace profil en cas de probleme avec une commande
class MessagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('object',TextType::class, options: [
                'label' => false,
                'attr'=>[
                        'placeholder'=> "Object",
                        'class' => "profile_message_input"
                ]
    ])

            ->add('reference',TextType::class, options: [
                'label' => false,
                'attr'=>[
                        'placeholder'=> "Reference",
                        'class' => "profile_message_input"
                ]
            ])
            ->add('message', TextareaType::class, options: [
                'label' => false,
                'attr'=>[
                        'placeholder'=>"Message",
                        'class' => "formProfileMessage"
                ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Messages::class,
        ]);
    }
}
