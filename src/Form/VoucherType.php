<?php

namespace App\Form;

use App\Entity\Voucher;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class VoucherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('discount')
            ->add('name')
            ->add('date_start', DateType::class,[
                'widget' => 'single_text'
            ])
            ->add('date_end', DateType::class,[
                'widget' => 'single_text'
            ])
            ->add('couponcode')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voucher::class,
        ]);
    }
}
