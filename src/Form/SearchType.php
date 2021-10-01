<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\SubCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('searchText', TextType::class, [
                'required' => false,
                'attr' => [
                    'class'=> 'form-control'
                ]
            ])
            ->add('brand', EntityType::class,
                array(
                'class' => Brand::class,
                'choice_label' => 'name',
                'required' => false,
                'expanded' => false,
                'multiple' => false
                )
            )
            ->add('subCategory', EntityType::class,
                array(
                'class' => SubCategory::class,
                'choice_label' => 'name',
                'required' => false,
                'expanded' => false,
                'multiple' => false
                )
            )
            ->add('valider', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
