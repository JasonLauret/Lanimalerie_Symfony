<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class,
            [
                'choices'  => 
                [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER'
                ], 
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('firstName')
            ->add('lastName')
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('civility', ChoiceType::class,
            [
                'choices'  => 
                [
                    'Mr' => "H",
                    'Mme' => "F"
                ], 
                'expanded' => true,
            ])
            ->add('country')
            ->add('city')
            ->add('postalCode')
            ->add('address')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
