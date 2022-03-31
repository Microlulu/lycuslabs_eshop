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
                'mapped' => false,
                'label' => 'Choose your delivery address',
                'required' => true,
                'class' => Adresse::class,
                'choices' => $user->getAdresses(),
                'multiple' => false,
                'expanded' => true
            ])
        ->add('submit', SubmitType::class, [
            'label' => "Checkout",
            'attr' => [
                'class' => 'button'
            ]
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'user' => array()
        ]);
    }
}
