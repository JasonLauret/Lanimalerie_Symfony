<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email')
        ->add('firstName')
        ->add('lastName')
        ->add('birthDate', DateType::class, [
            'widget' => 'single_text'
        ])
        ->add('civility', ChoiceType::class,
        [
            'choices'  => 
            [
                'Monsieur' => "Monsieur", //1er: Se qui sera afficher dans le formulaire  2eme: se qui sera afficher en bdd
                'Madame' => "Madame"
            ], 
            'expanded' => true,
        ])
        ->add('country')
        ->add('city')
        ->add('postalCode')
        ->add('address')
        ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
