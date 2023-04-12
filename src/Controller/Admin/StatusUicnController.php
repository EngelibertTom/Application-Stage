<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\StatusUicn;
use App\Form\Admin\StatusUicnType;
use App\Manager\StatusUicnManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/status-uicn")
 */
class StatusUicnController extends GeneralController
{
    /**
     * @Route("/list", name="app_admin_statusuicn_list")
     * @IsGranted("ROLE_MANAGER")
     */
    public function listAction(StatusUicnManager $statusUicnManager): Response
    {
        return $this->render('admin/data/statusUicn/list.html.twig', [
            'statusUicn' => $statusUicnManager->getStatusUicn()
        ]);
    }

    /**
     * @Route("/add", name="app_admin_statusuicn_add")
     * @IsGranted("ROLE_MANAGER")
     */
    public function addAction(Request $request, StatusUicnManager $statusUicnManager): Response
    {
        $statusUicn = new StatusUicn();

        $form = $this->createForm(StatusUicnType::class, $statusUicn);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $statusUicnManager->save($statusUicn);
            $this->flashSuccess('Le statut UICN a bien été ajouté');

            return $this->redirectToRoute('app_admin_statusuicn_list');
        }

        return $this->render('admin/data/statusUicn/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_statusuicn_edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, StatusUicn $statusUicn, StatusUicnManager $statusUicnManager): Response
    {
        $form = $this->createForm(StatusUicnType::class, $statusUicn);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $statusUicnManager->save($statusUicn);
            $this->flashSuccess('Le statut UICN a bien été modifié');

            return $this->redirectToRoute('app_admin_statusuicn_list');
        }

        return $this->render('admin/data/statusUicn/edit.html.twig', [
            'form' => $form->createView(),
            'statusUicn' => $statusUicn
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_statusuicn_delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(StatusUicn $statusUicn, StatusUicnManager $statusUicnManager)
    {
        $statusUicnManager->delete($statusUicn);

        $this->flashSuccess('Le statut a bien été supprimé');

        return $this->redirectToRoute('app_admin_statusuicn_list');
    }
}
