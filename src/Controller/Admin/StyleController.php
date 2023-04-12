<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\Style;
use App\Form\Admin\StyleType;
use App\Manager\StyleManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/styles")
 */
class StyleController extends GeneralController
{
    /**
     * @Route("/list", name="app_admin_style_list")
     * @IsGranted("ROLE_MANAGER")
     */
    public function listAction(StyleManager $styleManager): Response
    {
        return $this->render('admin/data/styles/list.html.twig', [
            'styles' => $styleManager->getStyles()
        ]);
    }

    /**
     * @Route("/add", name="app_admin_style_add")
     * @IsGranted("ROLE_MANAGER")
     */
    public function addAction(Request $request, StyleManager $styleManager): Response
    {
        $style = new Style();

        $form = $this->createForm(StyleType::class, $style);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $styleManager->save($style);
            $this->flashSuccess('Le style a bien été ajouté');

            return $this->redirectToRoute('app_admin_style_list');
        }

        return $this->render('admin/data/styles/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_style_edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, Style $style, StyleManager $styleManager): Response
    {
        $form = $this->createForm(StyleType::class, $style);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $styleManager->save($style);
            $this->flashSuccess('Le style a bien été modifié');

            return $this->redirectToRoute('app_admin_style_list');
        }

        return $this->render('admin/data/styles/edit.html.twig', [
            'form' => $form->createView(),
            'style' => $style
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_style_delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(Style $style, StyleManager $styleManager)
    {
        $styleManager->delete($style);

        $this->flashSuccess('Le style a bien été supprimé');

        return $this->redirectToRoute('app_admin_style_list');
    }
}
