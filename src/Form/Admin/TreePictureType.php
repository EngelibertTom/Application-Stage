<?php

namespace App\Form\Admin;

use App\Entity\TreePicture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TreePictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', FileType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'image inputFileHidden']
            ])
            ->add('featured', null, [
                'label' => 'Princip.',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TreePicture::class,
        ]);
    }
}
