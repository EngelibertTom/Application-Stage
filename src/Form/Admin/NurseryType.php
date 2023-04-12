<?php

namespace App\Form\Admin;

use App\Entity\Nursery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NurseryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('city')
            ->add('postalCode')
            ->add('email')
            ->add('phone')
            ->add('locations', CollectionType::class, [
                'label' => false,
                'entry_type' => LocationType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Nursery::class,
        ]);
    }
}
