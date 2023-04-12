<?php

namespace App\Manager;

use App\Entity\Segment;
use App\Entity\User;
use App\Repository\SegmentRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class SegmentManager
{
    private $segmentRepository;
    private $userService;
    private $security;
    private $em;

    public function __construct(SegmentRepository $segmentRepository, EntityManagerInterface $entityManager,
                                UserService $userService, Security $security)
    {
        $this->segmentRepository = $segmentRepository;
        $this->userService = $userService;
        $this->security = $security;
        $this->em = $entityManager;
    }

    public function save(Segment $segment): void
    {
        $this->em->persist($segment);
        $this->em->flush();
    }

    public function getSegment(int $id): ?Segment
    {
        return $this->segmentRepository->find($id);
    }

    public function getSegments(array $filter = [], $order = null, $start = null, $limit = null): array
    {
        return $this->segmentRepository->findPaginator($filter, $order, $start, $limit);
    }

    public function find(array $filter): ?Segment
    {
        return $this->segmentRepository->findOneBy($filter);
    }

    /**
     * Retourne un tableau formaté pour gérer le trie des tableaux.
     *
     * @param array $order
     * @return array
     */
    public function manageOrderList(array $order): ?array
    {
        $field = null;
        $manageOrder = null;

        if ($order && isset($order['column'], $order['dir'])) {
            switch ($order['column'])
            {
                case 0:
                    $field = 's.id';
                    break;

                case 1:
                    $field = 's.name';
                    break;

            }

            if ($field)
            {
                $manageOrder = [
                    'field' => $field,
                    'dir' => $order['dir']
                ];
            }
        }

        return $manageOrder;
    }

    public function delete(Segment $segment): void
    {
        $this->em->remove($segment);
        $this->em->flush();
    }
}
