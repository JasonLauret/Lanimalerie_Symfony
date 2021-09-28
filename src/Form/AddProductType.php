<?php 

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\SubCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class AddProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            // ->add('quantity')
            // ->add('isActive')
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
            ->add('subCategory', EntityType::class, 
                array(
                'class' => SubCategory::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false
                )
            )
            ->add('picture', FileType::class, [
                'label' => 'Picture',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png', 
                            'image/jpeg', 
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Merci de charger une image valide',
                    ])
                ]

            ])
            
            ->add("valider", SubmitType::class)
        ;
    }

    public function configureOption(OptionsResolver $resolver)
    {
       // $resolver->setDefaults([]);
    }
}
