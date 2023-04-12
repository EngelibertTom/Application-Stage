<?php

namespace App\Manager;

use App\Entity\Greenhouse;
use App\Entity\User;
use App\Repository\GreenhouseRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class GreenhouseManager
{
    private $greenhouseRepository;
    private $userService;
    private $security;
    private $em;

    public function __construct(GreenhouseRepository $greenhouseRepository, EntityManagerInterface $entityManager,
                                UserService $userService, Security $security)
    {
        $this->greenhouseRepository = $greenhouseRepository;
        $this->userService = $userService;
        $this->security = $security;
        $this->em = $entityManager;
    }

    public function save(Greenhouse $greenhouse): void
    {
        $this->em->persist($greenhouse);
        $this->em->flush();
    }

    public function getGreenhouse(int $id): ?Greenhouse
    {
        return $this->greenhouseRepository->find($id);
    }

    public function getGreenhouses(array $filter = []): array
    {
        return $this->greenhouseRepository->findBy($filter, ['name' => 'ASC']);
    }

    /**
     * Retourne seulement les serres dont l'utilisateur est responsable.
     */
    public function getNurseryGreenhouses(User $user, array $filter = []): array
    {
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->getGreenhouses($filter);
        }

        $nurseries = $this->userService->getNurseries($user);

        return $this->greenhouseRepository->findByNurseries($nurseries, $filter);
    }

    public function find(array $filter): ?Greenhouse
    {
        return $this->greenhouseRepository->findOneBy($filter);
    }

    public function delete(Greenhouse $greenhouse): void
    {
        $this->em->remove($greenhouse);
        $this->em->flush();
    }
}
