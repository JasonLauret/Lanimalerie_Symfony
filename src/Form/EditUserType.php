<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', TextType::class, [
            'label' => 'Email',
        ])
        ->add('firstName', TextType::class, [
            'label' => 'Prénon',
        ])
        ->add('lastName', TextType::class, [
            'label' => 'Nom',
        ])
        ->add('birthDate', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Date de naissance',
        ])
        ->add('civility', ChoiceType::class,
        [
            'choices'  => 
            [
                'Monsieur' => "Monsieur", //1er: Se qui sera afficher dans le formulaire  2eme: se qui sera afficher en bdd
                'Madame' => "Madame"
            ], 
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
