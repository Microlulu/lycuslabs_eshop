<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Voucher;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Titre",
                    'class' => "input_forms"]])
            ->add('shortcut',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Shortcut",
                    'class' => "input_forms"]])
            ->add('description',TextareaType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Main Description",
                    'class' => "formContactMessage"]
            ])
            ->add('descriptionadd',TextareaType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Optional Description",
                    'class' => "formContactMessage"]
            ])
            ->add('price',TextType::class, options: [
                'label' => "Price ",
                'attr'=> [
                    'class' => "input_forms"]])
            ->add('image',FileType::class,[
                'label'=> 'Upload an image',
                'required' => false,
                'data_class'=>null,
                'attr'=> [
                    'class' => "input_forms"]
            ])
            ->add('category_id', EntityType::class, [
                'class'=> Category::class,
                'choice_label'=>'name',
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Shortcut",
                    'class' => "input_forms"]
            ])
            ->add('voucher', EntityType::class, [
                'label'=> "Discount ",
                'class'=> Voucher::class,
                'choice_name'=> "discount",
                'required' => false,
                'attr'=> [
                    'class' => "input_forms"]
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
