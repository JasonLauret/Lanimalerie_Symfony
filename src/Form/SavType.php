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


class SavType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("NumeroDeCommande", TextType::class)
            ->add("email", EmailType::class)
            ->add('motif', ChoiceType::class,
                [
                    'choices' => 
                    [
                        'Produit défectueux' => 1,
                        'Produit incomplet' => 2,
                        'Pièce cassée' => 3,
                        'Produit périmé' => 4,
                    ],
                    'expanded' => false
                ])
            ->add("message", TextareaType::class, ['attr' => ['rows' => 5]])
            ->add("envoyer", SubmitType::class)
            ;
    }

    public function configureOption(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
