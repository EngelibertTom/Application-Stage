<?php

namespace App\Form\Admin;

use App\Entity\Nursery;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerateLotQrCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbQrCode', NumberType::class, [
                'label' => 'Nombre',
                'help' => 'Nombre de QrCode à générer',
                'data' => 1
            ])
            ->add('nursery', EntityType::class, [
                'class' => Nursery::class,
                'help' => 'Pour quelle pépinière ?',
            ])
            ->add('entryDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'data' => new \DateTime()
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
