<?php 

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;


class CateroryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("name", TextType::class)
            ->add("description", TextType::class)
            ->add("valider", SubmitType::class)
        ;
    }

    public function configureOption(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
