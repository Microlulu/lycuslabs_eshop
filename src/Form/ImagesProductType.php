<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\ImagesProduct;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImagesProductType extends AbstractType
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
            ->add('product_id', EntityType::class, [
                'class'=> Product::class,
                'choice_label'=>'image',
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
            'data_class' => ImagesProduct::class,
        ]);
    }
}
