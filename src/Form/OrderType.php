<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
            ->add('date')
            ->add('adress', EntityType::class, 
                array(
                    'class' => Adress::class,
                    'choices' => $user->getAdresses(),
                )
            )
            // ->add('adresses', EntityType::class,[
            //     'label' => 'Choisissez votre adresse de livraison:',
            //     'required' => true,
            //     'multiple' => false,
            //     'class' => Adress::class,
            //     'choices' => $user->getAdresses(),
            //     'expanded' => true
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'user' => array()
        ]);
    }
}
