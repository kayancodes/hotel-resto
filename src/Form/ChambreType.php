<?php

namespace App\Form;

use App\Entity\Chambre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            //TITRE AVEC MENU DEROULANT 
            // ->add('titre', ChoiceType::class , [ // menu deroulant dans les form 
            //     'choices' => [
            //         'Test1' => 'Test1',
            //         'Test2' => 'Test2',
            //         'Test3' => 'Test3'
            //     ]
            // ])
            ->add('titre')
            ->add('descriptionCourte')
            ->add('descriptionLongue')
            ->add('photo', FileType::class, ["mapped" => false, "required" => false])
            ->add('prix', MoneyType::class)
            // ->add('createdAt')
            ->add("save", SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
        ]);
    }
}
