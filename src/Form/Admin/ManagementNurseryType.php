<?php

namespace App\Form\Admin;

use App\Entity\ManagementNursery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManagementNurseryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('defaultNursery', null, [
                'label' => 'Principale'
            ])
            ->add('nursery')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ManagementNursery::class,
        ]);
    }
}
