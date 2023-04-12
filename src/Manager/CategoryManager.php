<?php

namespace App\Manager;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryManager
{
    private $categoryRepository;
    private $em;

    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $this->categoryRepository = $categoryRepository;
        $this->em = $entityManager;
    }

    public function save(Category $category): void
    {
        $this->em->persist($category);
        $this->em->flush();
    }

    public function getCategories(): array
    {
        return $this->categoryRepository->findAll();
    }

    public function delete(Category $category): void
    {
        $this->em->remove($category);
        $this->em->flush();
    }
}
