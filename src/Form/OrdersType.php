<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('user')
            ->add('delivery', EntityType::class, 
                array(
                    'class' => Adress::class,
                    // 'choices' => $user->getAdresses(),
                    'choice_label' => 'country',
                    'required' => false,
                    'expanded' => true,
                    'multiple' => false,
                )
            )
            ->add('payment_method', ChoiceType::class,
            [
                'choices'  => 
                [
                    'Carte Bancaire' => 'Carte Bancaire',
                    'Paypal' => 'Paypal'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'user' => array()
        ]);
    }
}
