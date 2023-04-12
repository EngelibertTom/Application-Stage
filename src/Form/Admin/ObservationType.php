<?php

namespace App\Form\Admin;

use App\Entity\Observation;
use App\Entity\Tree;
use App\Entity\User;
use App\Service\NurseryService;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ObservationType extends AbstractType
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
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('comment', TextType::class, [
                'attr' => [ 'rows' => 1 ],
                'required' => false
            ])
        ;

        if (!$options['existTree'])
        {
            $builder
                ->add('tree', EntityType::class, [
                    'class' => Tree::class,
                    'query_builder' => function (EntityRepository $er) {
                        $qb = $er->createQueryBuilder('t');

                        if ($this->nuseryCurrent && !$this->security->isGranted('ROLE_SUPER_ADMIN'))
                        {
                            $qb->leftJoin('t.nursery', 'n')
                                ->where('n = :nursery')
                                ->setParameter('nursery', $this->nuseryCurrent)
                            ;
                        }

                        return $qb;
                    },
                    'attr' => [ 'class' => 'selectpicker', 'data-live-search' => true ] // https://codepen.io/Vadammt/pen/NgNVQx
                ])
                ->add('save', SubmitType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Observation::class,
            'existTree' => false
        ]);
    }
}
