<?php

namespace App\Form;

use App\Entity\Eleve;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('fonction')
            ->add('username')
            ->add('confirmPassword')
            ->add('imageName')
            ->add('updatedAt')
            ->add('nom')
            ->add('prenom')
            ->add('date_naissance')
            ->add('lieu_naissance')
            ->add('adress')
            ->add('security')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }
}
