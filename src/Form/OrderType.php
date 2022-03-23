<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Order;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Options;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add ('adresses', EntityType::class, [
                'label' => 'Choose your delivery address',
                'required' => true,
                'class' => Adresse::class,
                'choices' => $user->getAdresses(),
                'multiple' => false,
                'expanded' => true
            ])
            ->add('fullname', options:[
                'label' => 'Or create a new one',
                'attr'=> [
                    'placeholder' => 'Fullname'
                ]
            ])
            ->add('vat_number', options: [
                'label' => false,
                'attr'=> [
                'placeholder' => 'VAT Number'
                    ]
            ])
            ->add('adresse', options: [
                'label' => false,
                'attr'=> [
                    'placeholder' => 'Address'
                ]
            ])
            ->add('zipcode', options: [
                'label' => false,
                'attr'=> [
                    'placeholder' => 'Zipcode'
                ]
            ])
            ->add('city', options: [
                'label' => false,
                'attr'=> [
                    'placeholder' => 'City'
                ]
            ])
            ->add('country', options: [
                'label' => false,
                'attr'=> [
                    'placeholder' => 'Country'
                ]
            ])
            ->add('telephone', options: [
                'label' => false,
                'attr'=> [
                    'placeholder' => 'Phone Number'
                ]
            ])
            ->add('submit', SubmitType::class, options: [
                'label' => false,
                'attr'=> [
                    'class' => "button_inset"
                ]
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'user' => array()
        ]);
    }
}
