<?php

namespace App\Form;

use App\Entity\Carousel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CarouselType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'data_class' => null,
                'required' => false,
                'label'=> 'Upload an image',
                'attr'=> [
                    'class' => "input_forms"]
            ])
            ->add('shortcut',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Shortcut",
                    'class' => "input_forms"]
            ])
            ->add('title',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Title",
                    'class' => "input_forms"]
            ])
            ->add('text',TextareaType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Text",
                    'class' => "formContactMessage"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carousel::class,
        ]);
    }
}
