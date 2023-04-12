<?php

namespace App\Form\Admin;

use App\Entity\Equipement;
use App\Entity\PretEquipement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PretType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'data' => $builder->getData() && $builder->getData()->getId() ? $builder->getData()->getDate() : (new \DateTime())  ])
            ->add('utilisateur')
            ->add('commentaire')
            ->add('equipement', EntityType::class, [
                'class'=>Equipement::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PretEquipement::class,
        ]);
    }
}
