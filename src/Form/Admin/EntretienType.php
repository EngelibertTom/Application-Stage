<?php

namespace App\Form\Admin;

use App\Entity\Entretien;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntretienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateRealisation', DateType::class, [
        'widget' => 'single_text',
        'data' => $builder->getData() && $builder->getData()->getId() ? $builder->getData()->getDateRealisation() : (new \DateTime())
    ])
            ->add('observation')
            ->add('nom')
            ->add('equipement')
            ->add('type')
            ->add('planifier', ChoiceType::class, [
                'choices'=> [
                    'semaine' => '1',
                    'mois' => '2',
                    'annee' => '3'

                ]
            ])
            ->add('urgence', ChoiceType::class, [
                'choices' => [
                    'Faible' => '1',
                    'Moyen' => '2',
                    'Fort' => '3',
                    ]
                             ])
            ->add('utilisateur',EntityType::class, [
            'class'=>User::class,])

          ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entretien::class,
        ]);
    }
}
