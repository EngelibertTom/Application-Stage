<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class UserType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, [
                'required' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répéter les mots de passe'],
            ])
            ->add('save', SubmitType::class)
        ;

        if ($options['full'])
        {
            $roles = [
                'Bénévole' => 'ROLE_VOLUNTEER',
                'Gérant de pépinière' => 'ROLE_MANAGER',
                'Gérant des espèces' => 'ROLE_SPECIES_MANAGER'
            ];

            if ($this->security->isGranted('ROLE_SUPER_ADMIN'))
            {
                $roles['Super Admin'] = 'ROLE_SUPER_ADMIN';
            }

            $builder
                ->add('active', CheckboxType::class, [
                    'label' => 'Activer',
                    'required' => false
                ])
                ->add('roles', ChoiceType::class, [
                    'multiple' => true,
                    'data' => $builder->getData()->getRoles() ?: ['ROLE_VOLUNTEER'],
                    'choices' => $roles,
                    'attr' => [
                        'class' => 'selectpicker',
                        'data-placeholder' => 'Liste des rôles',
                    ]
                ])
                ->add('managementNurseries', CollectionType::class, [
                    'label' => false,
                    'entry_type' => ManagementNurseryType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'full' => true
        ]);
    }
}
