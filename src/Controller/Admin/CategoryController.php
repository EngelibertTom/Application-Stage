<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\Category;
use App\Form\Admin\CategoryType;
use App\Manager\CategoryManager;
use App\Manager\StyleManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/categories")
 */
class CategoryController extends GeneralController
{
    /**
     * @Route("/list", name="app_admin_category_list")
     * @IsGranted("ROLE_MANAGER")
     */
    public function listAction(CategoryManager $categoryManager): Response
    {
        return $this->render('admin/data/categories/list.html.twig', [
            'categories' => $categoryManager->getCategories()
        ]);
    }

    /**
     * @Route("/add", name="app_admin_category_add")
     * @IsGranted("ROLE_MANAGER")
     */
    public function addAction(Request $request, CategoryManager $categoryManager): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categoryManager->save($category);
            $this->flashSuccess('La catégorie a bien été ajoutée');

            return $this->redirectToRoute('app_admin_category_list');
        }

        return $this->render('admin/data/categories/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_category_edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, Category $category, CategoryManager $categoryManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $categoryManager->save($category);
            $this->flashSuccess('La catégorie a bien été modifiée');

            return $this->redirectToRoute('app_admin_category_list');
        }

        return $this->render('admin/data/categories/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_category_delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(Category $category, CategoryManager $categoryManager)
    {
        $categoryManager->delete($category);

        $this->flashSuccess('Le catégorie a bien été supprimée');

        return $this->redirectToRoute('app_admin_category_list');
    }
}
