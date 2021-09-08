<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class)
            ->add('firstName', TextType::class)
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions avant de continuer.',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [])
            ->add('confirmPassword', PasswordType::class)
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('civility', ChoiceType::class,
                [
                    'choices'  => 
                    [
                        'Monsieur' => "Monsieur",
                        'Madame' => "Madame"
                    ],
                    'expanded' => true,
                ])
            ->add('country', TextType::class)
            ->add('city', TextType::class)
            ->add('postalCode', TextType::class)
            ->add('address', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
