<?php

namespace App\Form\Admin;

use App\Entity\Lot;
use App\Entity\Species;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('entryDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'data' => $builder->getData() && $builder->getData()->getId() ? $builder->getData()->getEntryDate() : (new \DateTime())
            ])
            ->add('lotPictures', CollectionType::class, [
                'mapped' => false,
                'label' => false,
                'entry_type' => LotPictureType::class,
                'entry_options' => ['label' => false],
                'data' => $builder->getData()->getLotPictures(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false
            ])
            ->add('place')
            ->add('city')
            ->add('postalCode')
            ->add('country')
            ->add('ageRecovery', NumberType::class, [
                'help' => 'L\'âge lors de la récupération',
                'required' => false
            ])
            ->add('recoveryCause', TextareaType::class, [
                'required' => false,
                'attr' => ['rows' => 5]
            ])
            ->add('nursery')
            ->add('recoveryType')
            ->add('species', EntityType::class, [
                'class' => Species::class,
                'multiple' => true,
                'required' => false,
                'attr' => ['class' => 'selectpicker'],
                'label' => 'Espèces',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.validate = 1')
                        ->orderBy('s.name', 'ASC');
                },
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lot::class,
        ]);
    }
}
