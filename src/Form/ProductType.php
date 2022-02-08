<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('shortcut')
            ->add('description', TextareaType::class,['label'=> 'Description of the product'])
            ->add('price')
            ->add('image',FileType::class,[
                'label'=> 'Upload an image',
                'required' => false,
                'data_class'=>null
            ])
            ->add('category_id', EntityType::class, [
                'class'=> Category::class,
                'choice_label'=>'name'
            ])
            ->add('voucher', CheckboxType::class, [
                'label'=> "En promotion",
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
