<?php

namespace App\Form\Admin;

use App\Entity\Category;
use App\Entity\Greenhouse;
use App\Entity\Location;
use App\Entity\Lot;
use App\Entity\OutputType;
use App\Entity\PotType;
use App\Entity\Species;
use App\Entity\Style;
use App\Entity\Tree;
use App\Entity\User;
use App\Manager\TreeStatusManager;
use App\Repository\CategoryRepository;
use App\Repository\GreenhouseRepository;
use App\Repository\LocationRepository;
use App\Repository\LotRepository;
use App\Repository\SpeciesRepository;
use App\Service\NurseryService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class TreeType extends AbstractType
{
    private $treeStatusManager;
    private $nuseryCurrent;
    private $security;

    public function __construct(TreeStatusManager $treeStatusManager, Security $security,
                                NurseryService $nurseryService)
    {
        $this->treeStatusManager = $treeStatusManager;

        /** @var User $user */
        $user = $security->getUser();
        $this->security = $security;

        if ($user)
        {
            $managementNurseries = $user->getManagementNurseries()->toArray();
            $this->nuseryCurrent = $nurseryService->getMainNursery($managementNurseries);
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('treePictures', CollectionType::class, [
                'mapped' => false,
                'label' => false,
                'entry_type' => TreePictureType::class,
                'entry_options' => ['label' => false],
                'data' => $builder->getData()->getTreePictures(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false
            ])
            ->add('ageRecovery', NumberType::class, [
                'help' => 'L\'âge lors de la récupération',
                'required' => false
            ])
            ->add('output', null, [
                'label' => 'Sortie',
                'required' => false
            ])
            ->add('species', EntityType::class, [
                'class' => Species::class,
                'required' => false,
                'query_builder' => function (SpeciesRepository $er) use ($builder) {
                    $qb = $er->createQueryBuilder('s')
                        ->where('s.validate = 1')
                        ->orderBy('s.name', 'ASC');

                    /** @var Lot $lot */
                    if ($builder->getData() && $lot = $builder->getData()->getLot())
                    {
                        $qb->innerJoin('s.lots', 'l')
                            ->andWhere('l.id = :lot_id')
                            ->setParameter('lot_id', $lot->getId())
                        ;
                    }

                    return $qb;
                },
            ])
            ->add('greenhouse', EntityType::class, [
                'class' => Greenhouse::class,
                'required' => false,
                'query_builder' => function (GreenhouseRepository $er) use ($builder) {
                    $qb = $er->createQueryBuilder('g')
                        ->orderBy('g.name', 'ASC');

                    if ($this->nuseryCurrent && !$this->security->isGranted('ROLE_SUPER_ADMIN'))
                    {
                        $qb->where('g.nursery = :nursery')
                            ->setParameter('nursery', $this->nuseryCurrent)
                        ;
                    }

                    return $qb;
                }
            ])
            ->add('cultureTable')
            ->add('segment')
            ->add('tableColumn')
            ->add('columnRow')
            ->add('potType', EntityType::class, [
                'class' => PotType::class,
                'required' => false,
                'choice_attr' => function(?PotType $potType) {
                    return $potType ? ['style' => 'color:' . $potType->getColor()] : [];
                },
            ])
            ->add('outputType', EntityType::class, [
                'class' => OutputType::class,
                'required' => false
            ])
            ->add('workingYear', ChoiceType::class, [
                'label' => 'Année de travail',
                'choices' => [
                    '-5 ans' => 1,
                    '5-10 ans' => 2,
                    '+10 ans' => 3
                ],
                'required' => false
            ])
            ->add('potentialStyles', EntityType::class, [
                'class' => Style::class,
                'multiple' => true,
                'help' => 'Vers quel style peut-il se diriger',
                'required' => false,
                'attr' => ['class' => 'selectpicker']
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'required' => false,
                'multiple' => true,
                'query_builder' => function (CategoryRepository $er) use ($builder) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'attr' => ['class' => 'selectpicker']
            ])
            ->add('adoption', AdoptionType::class, [
                'required' => false
            ])
            ->add('deadTree', DeadTreeType::class, [
                'required' => false
            ])
            ->add('lot', EntityType::class, [
                'class' => Lot::class,
                'required' => false,
                'query_builder' => function (LotRepository $er) use ($builder) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.name', 'ASC');
                }
            ])
            ->add('works', CollectionType::class, [
                'label' => false,
                'entry_type' => TreeWorkType::class,
                'entry_options' => [ 'label' => false, 'existTree' => true ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true
            ])
            ->add('observations', CollectionType::class, [
                'label' => false,
                'entry_type' => ObservationType::class,
                'entry_options' => [ 'label' => false, 'existTree' => true ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true
            ])
            ->add('height', NumberType::class, [
                'label' => 'Hauteur de l\'arbre',
                'help' => 'Hauteur en cm',
                'required' => false
            ])
            ->add('nebariDiameter', NumberType::class, [
                'label' => 'Diamètre du nébari',
                'help' => 'Diamètre en cm',
                'required' => false
            ])
            ->add('trunkDiameter', NumberType::class, [
                'label' => 'Diamètre du tronc',
                'help' => 'Diamètre en cm',
                'required' => false

            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => $this->treeStatusManager->getList(true),
                'required' => true
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tree::class,
        ]);
    }
}
