<?php

namespace App\Form\Admin;

use App\Entity\CultureTable;
use App\Entity\Greenhouse;
use App\Entity\Location;
use App\Entity\User;
use App\Manager\TreeStatusManager;
use App\Repository\LocationRepository;
use App\Service\NurseryService;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class GreenhouseType extends AbstractType
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
            ->add('name')
            ->add('nursery')
            ->add('cultureTables', EntityType::class, [
                'class' => CultureTable::class,
                'multiple' => true,
                'required' => false,
                'attr' => ['class' => 'selectpicker'],
                'label' => 'Tables',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
                },
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'label' => 'Aire',
                'required' => false,
                'query_builder' => function (LocationRepository $er) use ($builder) {
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Greenhouse::class,
        ]);
    }
}
