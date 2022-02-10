<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Voucher;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
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
            ->add('title')
            ->add('shortcut')
            ->add('description', TextareaType::class,['label'=> 'Description of the product'])
            ->add('descriptionadd', TextareaType::class,['label'=> 'Additionnal information of the product'])
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
            ->add('voucher', EntityType::class, [
                'label'=> "discount",
                'class'=> Voucher::class,
                'choice_name'=> "discount",
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
