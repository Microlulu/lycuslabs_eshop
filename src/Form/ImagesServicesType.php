<?php

namespace App\Form;

use App\Entity\ImagesServices;
use App\Entity\Product;
use App\Entity\Services;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImagesServicesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image',FileType::class,[
                'label'=> 'Upload an image',
                'required' => false,
                'data_class'=>null,
                'attr'=> [
                    'class' => "input_forms"]
            ])
            ->add('alt',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Shortcut",
                    'class' => "input_forms"]
            ])
            ->add('service_id', EntityType::class, [
                'class'=> Services::class,
                'choice_label'=>'name',
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Shortcut",
                    'class' => "input_forms"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ImagesServices::class,
        ]);
    }
}
