<?php

namespace App\Form;

use App\Entity\Team;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Firstname",
                    'class' => "input_forms"]])
            ->add('lastname',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Lastname",
                    'class' => "input_forms"]])
            ->add('photo', FileType::class, [
                'data_class' => null,
                'required' => false,
                'label'=> 'upload a photo of a member',
                'attr'=> [
                    'class' => "input_forms"]])
            ->add('shortcut',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Shortcut",
                    'class' => "input_forms"]])
            ->add('job',TextType::class, options: [
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Job",
                    'class' => "input_forms"]])
            ->add('description', TextareaType::class,[
                'label' => false,
                'attr'=> [
                    'placeholder'=> "Small resume of the member",
                    'class' => "formContactMessage"]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
