<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\LeafType;
use App\Form\Admin\LeafTypeType;
use App\Manager\LeafTypeManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/leaf-types")
 */
class LeafTypeController extends GeneralController
{
    /**
     * @Route("/list", name="app_admin_leaftype_list")
     * @IsGranted("ROLE_MANAGER")
     */
    public function listAction(LeafTypeManager $leafTypeManager): Response
    {
        return $this->render('admin/data/leafType/list.html.twig', [
            'leafTypes' => $leafTypeManager->getLeafTypes()
        ]);
    }

    /**
     * @Route("/add", name="app_admin_leaftype_add")
     * @IsGranted("ROLE_MANAGER")
     */
    public function addAction(Request $request, LeafTypeManager $leafTypeManager): Response
    {
        $leafType = new LeafType();

        $form = $this->createForm(LeafTypeType::class, $leafType);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $leafTypeManager->save($leafType);
            $this->flashSuccess('Le type de feuille a bien été ajouté');

            return $this->redirectToRoute('app_admin_leaftype_list');
        }

        return $this->render('admin/data/leafType/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_leaftype_edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, LeafType $leafType, LeafTypeManager $leafTypeManager): Response
    {
        $form = $this->createForm(LeafTypeType::class, $leafType);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $leafTypeManager->save($leafType);
            $this->flashSuccess('Le type de feuille a bien été modifié');

            return $this->redirectToRoute('app_admin_leaftype_list');
        }

        return $this->render('admin/data/leafType/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_leaftype_delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(LeafType $leafType, LeafTypeManager $leafTypeManager)
    {
        $leafTypeManager->delete($leafType);

        $this->flashSuccess('Le type de feuille a bien été supprimé');

        return $this->redirectToRoute('app_admin_leaftype_list');
    }
}
