<?php

namespace App\Form;

use App\Entity\Voucher;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class VoucherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('discount',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Discount %",
                    'class' => "input_forms"]
            ])
            ->add('name',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Name",
                    'class' => "input_forms"]
            ])
            ->add('date_start', DateType::class,[
                'widget' => 'single_text',
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Date-start",
                    'class' => "input_forms"]
            ])
            ->add('date_end', DateType::class,[
                'widget' => 'single_text',
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Date_end",
                    'class' => "input_forms"]
            ])
            ->add('couponcode',TextType::class, options: [
                'label' => "OR ",
                'attr'=> [
                    'placeholder'=> "Coupon Code for User",
                    'class' => "input_forms"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voucher::class,
        ]);
    }
}
