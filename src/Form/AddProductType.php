<?php 

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("name", TextType::class)
            ->add("description", TextType::class)
            ->add("price", TextType::class)
            ->add('brand', EntityType::class, 
                array(
                'class' => Brand::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false
                )
            )
            ->add('category', EntityType::class, 
                array(
                'class' => Category::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true
                )
            )
            ->add("valider", SubmitType::class)
        ;
    }

    public function configureOption(OptionsResolver $resolver)
    {
       // $resolver->setDefaults([]);
    }
}
