<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\Nursery;
use App\Form\Admin\NurseryType;
use App\Manager\NurseryManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/nurseries")
 */
class NurseryController extends GeneralController
{
    /**
     * @Route("/list", name="app_admin_nursery_list")
     * @IsGranted("ROLE_MANAGER")
     */
    public function listAction(NurseryManager $nurseryManager): Response
    {
        return $this->render('admin/nursery/list.html.twig', [
            'nurseries' => $nurseryManager->getNurseries()
        ]);
    }

    /**
     * @Route("/add", name="app_admin_nursery_add")
     * @IsGranted("ROLE_MANAGER")
     */
    public function addAction(Request $request, NurseryManager $nurseryManager): Response
    {
        $nursery = new Nursery();

        $form = $this->createForm(NurseryType::class, $nursery);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $nurseryManager->save($nursery);
            $this->flashSuccess('La pépinière a bien été ajoutée');

            return $this->redirectToRoute('app_admin_nursery_list');
        }

        return $this->render('admin/nursery/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_nursery_edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, Nursery $nursery, NurseryManager $nurseryManager): Response
    {
        $form = $this->createForm(NurseryType::class, $nursery);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $nurseryManager->save($nursery);
            $this->flashSuccess('La pépinière a bien été modifiée');

            return $this->redirectToRoute('app_admin_nursery_list');
        }

        return $this->render('admin/nursery/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_nursery_delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(Nursery $nursery, NurseryManager $nurseryManager)
    {
        $nurseryManager->delete($nursery);

        $this->flashSuccess('La pépinière a bien été supprimée');

        return $this->redirectToRoute('app_admin_nursery_list');
    }
}
