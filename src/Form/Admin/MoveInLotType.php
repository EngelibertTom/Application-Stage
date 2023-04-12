<?php

namespace App\Form\Admin;

use App\Entity\Lot;
use App\Entity\Nursery;
use App\Entity\Tree;
use App\Entity\User;
use App\Manager\TreeStatusManager;
use App\Repository\LotRepository;
use App\Repository\TreeRepository;
use App\Service\NurseryService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class MoveInLotType extends AbstractType
{
    private $nuseryCurrent;
    private $security;

    public function __construct(Security $security, NurseryService $nurseryService)
    {
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
            ->add('tree', EntityType::class, [
                'class' => Tree::class,
                'multiple' => true,
                'required' => false,
                'attr' => ['class' => 'selectpicker'],
                'query_builder' => function (TreeRepository $er) use ($builder) {
                    $qb = $er->createQueryBuilder('t');

                    if ($this->nuseryCurrent && !$this->security->isGranted('ROLE_SUPER_ADMIN'))
                    {
                        $qb->where('t.nursery = :nursery')
                            ->setParameter('nursery', $this->nuseryCurrent)
                        ;
                    }

                    return $qb;
                }
            ])
            ->add('lot', EntityType::class, [
                'class' => Lot::class,
                'required' => false,
                'query_builder' => function (LotRepository $er) use ($builder) {
                    $qb = $er->createQueryBuilder('l')
                        ->orderBy('l.name', 'ASC');

                    if ($this->nuseryCurrent && !$this->security->isGranted('ROLE_SUPER_ADMIN'))
                    {
                        $qb->where('l.nursery = :nursery')
                            ->setParameter('nursery', $this->nuseryCurrent)
                        ;
                    }

                    return $qb;
                }
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Déplacer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([ ]);
    }
}
