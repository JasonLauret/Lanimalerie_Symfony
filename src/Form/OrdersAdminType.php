<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, 
                array(
                    'class' => User::class,
                    'label' => 'Utilisateur',
                    "choice_label" => function (User $user) {
                        return " ".$user->getLastName(). " " .$user->getFirstName();
                    },
                )
            )
            ->add('date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
            ])
            ->add('delivery', TextType::class, [
                'label' => 'Livraison',
            ])
            ->add('payment', ChoiceType::class,
            [
                'choices'  => 
                [
                    'Carte Bancaire' => 'Carte Bancaire',
                    'Paypal' => 'Paypal'
                ],
                'expanded' => false,
                'label' => 'Paiement',
                'multiple' => false
            ])
            ->add('total', TextType::class, [
                'label' => 'Prix (en €)',
            ])
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
