<?php

namespace App\Form\Admin;

use App\Entity\HistoryTree;
use App\Entity\TypeHistoryTree;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HistoryTreeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EntityType::class, [
                'class' => TypeHistoryTree::class,
                'required' => true
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'data' => $builder->getData() && $builder->getData()->getId() ? $builder->getData()->getDate() : (new \DateTime())
            ])
            ->add('content', TextareaType::class, [
                'attr' => ['rows' => 3]
            ])
            ->add('visiblePublic', CheckboxType::class, [
                'label' => 'Publique',
                'required' => false
            ])

            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HistoryTree::class,
        ]);
    }
}
