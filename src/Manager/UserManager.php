<?php

namespace App\Manager;

use App\Entity\ManagementNursery;
use App\Entity\Nursery;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private $userRepository;
    private $passwordEncoder;
    private $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->em = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function save(User $user): void
    {
        $user = $this->updatePassword($user);

        foreach ($user->getManagementNurseries() as $managementNursery)
        {
            $managementNursery->setUser($user);
        }

        $this->em->persist($user);
        $this->em->flush();
    }

    public function getUsers($filter = []): array
    {
        return $this->userRepository->findBy($filter);
    }

    public function getUsersByRole(array $roles): array
    {
        return $this->userRepository->findByRole($roles);
    }

    public function delete(User $user): void
    {

        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * Modifie le mot de passe utilisateur.
     *
     * @param User $user
     * @return User
     */
    public function updatePassword(User $user): User
    {
        if ($user->getPlainPassword()) {
            $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
        }

        return $user;
    }

    /**
     * Changer la pépinière par défaut d'un utilisateur.
     *
     * @param User $user
     * @param Nursery $nursery
     */
    public function changeMainNursery(User $user, Nursery $nursery): void
    {
        $managementNurseries = $user->getManagementNurseries();

        /** @var ManagementNursery $managementNursery */
        $searchManagementNursery = $managementNurseries->filter(static function(ManagementNursery $managementNursery) use ($nursery) {
            return $managementNursery->getNursery() === $nursery;
        })->first();

        if ($searchManagementNursery)
        {
            foreach ($managementNurseries as $managementNursery)
            {
                $managementNursery->setDefaultNursery(false);
                $this->em->persist($managementNursery);
            }

            $searchManagementNursery->setDefaultNursery(true);
            $this->em->persist($searchManagementNursery);

            $this->em->flush();
        }
    }
}
