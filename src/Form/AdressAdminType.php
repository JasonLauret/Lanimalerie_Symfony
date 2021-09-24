<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', TextType::class, [
                'label' => 'Pays',
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
            ])
            ->add('postal_code', TextType::class, [
                'label' => 'Code postal',
            ])
            ->add('adress', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('user', EntityType::class, 
                array(
                    'label' => 'Utilisateur',
                    'class' => User::class,
                    'expanded' => false,
                    'multiple' => false,
                    'choice_label' => 
                        function ($allChoices){
                            return $allChoices->getFirstName() . " " . $allChoices->getLastName();
                        },
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
