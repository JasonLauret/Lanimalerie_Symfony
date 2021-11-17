<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Order;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('delivery', EntityType::class, 
                array(
                    'class' => Adress::class,
                    'query_builder' => function (EntityRepository $er) use ($options) {
                        return $er->createQueryBuilder('adresse')
                            ->where('adresse.user = :user')
                            ->setParameter('user', $options['user']);
                    },
                    "choice_label" => function (Adress $adress) {
                        return " ".$adress->getAdress(). ", " .
                                    $adress->getPostalCode().", " .
                                    $adress->getCity().", ".
                                    $adress->getCountry();
                    },
                    'label' => 'Livraison à l\'adresse suivante:',
                    'required' => true,
                    'expanded' => false,
                    'multiple' => false,
                )
            )
            
            ->add('payment', ChoiceType::class,
            [
                'choices'  => 
                [
                    'Carte Bancaire' => 'Carte Bancaire',
                    'Paypal' => 'Paypal'
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'Paiement:'
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
