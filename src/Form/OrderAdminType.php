<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $user = $options['user'];
        $builder
            ->add('date')
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
