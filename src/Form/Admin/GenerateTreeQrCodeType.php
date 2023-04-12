<?php

namespace App\Form\Admin;

use App\Entity\Lot;
use App\Entity\Nursery;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerateTreeQrCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbQrCode', NumberType::class, [
                'label' => 'Nombre',
                'help' => 'Nb de QrCode à générer',
                'data' => 1
            ])
            ->add('nursery', EntityType::class, [
                'class' => Nursery::class,
            ])
            ->add('lot', EntityType::class, [
                'class' => Lot::class,
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Générer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([ ]);
    }
}
