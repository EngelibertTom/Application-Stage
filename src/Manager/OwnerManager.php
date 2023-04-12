<?php

namespace App\Manager;

use App\Entity\Owner;
use App\Repository\OwnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class OwnerManager
{
    private $ownerRepository;
    private $em;
    private $passwordEncoder;

    public function __construct(OwnerRepository $ownerRepository, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->ownerRepository = $ownerRepository;
        $this->em = $entityManager;
        $this->passwordEncoder = $passwordEncoder;

    }

    public function save(Owner $owner): void
    {
        $owner = $this->updatePassword($owner);
        $this->em->persist($owner);
        $this->em->flush();
    }

    public function getOwner(array $filter): ?Owner
    {
        return $this->ownerRepository->findOneBy($filter);
    }

    public function getOwners($order = null, $search = null, $start = null, $limit = null): array
    {
        return $this->ownerRepository->findPaginator($order, $search, $start, $limit);
    }

    public function delete(Owner $owner): void
    {

        $this->em->remove($owner);
        $this->em->flush();
    }

        /**
     * Modifie le mot de passe utilisateur.
     *
     * @param Owner $owner
     * @return Owner
     */
    public function updatePassword(Owner $owner): Owner
    {
        if($owner->getPlainPassword()){
            $password = $this->passwordEncoder->encodePassword($owner, $owner->getPlainPassword());
            $owner->setPassword($password);
        }
        return $owner;
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
                    $field = 'o.name';
                    break;

                case 1:
                    $field = 'o.email';
                    break;

                case 2:
                    $field = 'o.phone';
                    break;

                case 3:
                    $field = 'o.lastConnection';
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

}
