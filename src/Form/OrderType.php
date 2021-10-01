<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Order;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class OrderType extends AbstractType
{
    // private $security;

    // public function __construct(Security $security)
    // {
    //     $this->security = $security;
    // }

    // public function buildForm(FormBuilderInterface $builder, array $options)
    // {
    //     $user = $this->security->getUser();

    //     $builder
    //         ->add('tags', EntityType::class, array(
    //             'class' => Adress::class,
    //             // 'query_builder' => function (EntityRepository $er) use ($user) {
    //             //     return $er->createQueryBuilder('u')
    //             //         ->where('u.user = :user')
    //             //         ->setParameter('user', $user->getId())
    //             //     ;
    //             // },
    //             'expanded' => true,
    //             'multiple' => true
    //         ))
    //         ->add('valider', SubmitType::class)
    //     ;
    // }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $user = $options['user'];
        $builder
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
