<?php

namespace App\Form;

use App\Entity\PaymentMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryPayment extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', ChoiceType::class,
            [
                'choices'  => 
                [
                    'Carte Bancaire' => 'Carte Bancaire',
                    'Paypal' => 'Paypal'
                ], 
                'expanded' => true,
                'multiple' => false
            ])
            ->add("valider", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PaymentMethod::class,
        ]);
    }


    // public function buildForm(FormBuilderInterface $builder, array $options)
    // {
    //     $builder
    //         ->add('payment_method', EntityType::class,
    //         [
    //             'class' => PaymentMethod::class
    //         ])
    //         ->add("valider", SubmitType::class)
    //     ;
    // }

    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Order::class,
    //     ]);
    // }
}
