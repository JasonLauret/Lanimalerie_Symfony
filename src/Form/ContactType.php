<?php 

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("nom", TextType::class)
            ->add("prenom", TextType::class)
            ->add("email", EmailType::class)
            ->add("message", TextareaType::class)
            ->add('civilite', ChoiceType::class,
                [
                    'choices'  => 
                    [
                        'Homme' => "H",
                        'Femme' => "F"
                    ], 
                    'expanded' => true,
                ])
            ->add("valider", SubmitType::class)
        ;
    }

    public function configureOption(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
