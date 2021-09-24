<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $user = $options['user'];
        $builder
            ->add('date')
            ->add('delivery', EntityType::class, 
                array(
                    'class' => Adress::class,
                    // 'choices' => $user->getAdresses(),
                    'choice_label' => 'country',
                    'expanded' => true,
                    'multiple' => false,
                    // 'choice_value' => 
                    //     function (Adress $entity = null) {
                    //         return $entity ? $entity->getCountry() : ' ';
                    //     },
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
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'user' => array()
        ]);
    }
}
