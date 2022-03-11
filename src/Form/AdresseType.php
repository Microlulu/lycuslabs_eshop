<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Firstname",
                    'class' => "input_forms"]
            ])
            ->add('lastname',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Lastname",
                    'class' => "input_forms"]
            ])
            ->add('company',TextType::class, options: [
                'label' => false,
                'required' => false,
                'attr'=> [
                    'placeholder'=> "Company",
                    'class' => "input_forms"]
            ])
            ->add('vat_number',TextType::class, options: [
                'label' => false,
                'required' => false,
                'attr'=> [
                    'placeholder'=> "Vat number",
                    'class' => "input_forms"]
            ])
            ->add('adresse',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Address",
                    'class' => "input_forms"]
            ])
            ->add('zipcode',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Zipcode",
                    'class' => "input_forms"]
            ])
            ->add('city',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "City",
                    'class' => "input_forms"]
            ])
            ->add('country', CountryType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Country",
                    'class' => "input_forms"]
            ])
            ->add('telephone',TextType::class, options: [
                'label' => false,
                'required' => false,
                'attr'=> [
                    'placeholder'=> "Phone number",
                    'class' => "input_forms"]
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
