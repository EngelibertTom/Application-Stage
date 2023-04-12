<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\Work;
use App\Form\Admin\WorkType;
use App\Manager\WorkManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/works")
 */
class WorkController extends GeneralController
{
    /**
     * @Route("/list", name="app_admin_work_list")
     * @IsGranted("ROLE_MANAGER")
     */
    public function listAction(WorkManager $workManager): Response
    {
        return $this->render('admin/data/works/list.html.twig', [
            'works' => $workManager->getWorks()
        ]);
    }

    /**
     * @Route("/add", name="app_admin_work_add")
     * @IsGranted("ROLE_MANAGER")
     */
    public function addAction(Request $request, WorkManager $workManager): Response
    {
        $work = new Work();

        $form = $this->createForm(WorkType::class, $work);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $workManager->save($work);
            $this->flashSuccess('Le travaux a bien été ajouté');

            return $this->redirectToRoute('app_admin_work_list');
        }

        return $this->render('admin/data/works/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_work_edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, Work $work, WorkManager $workManager): Response
    {
        $form = $this->createForm(WorkType::class, $work);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $workManager->save($work);
            $this->flashSuccess('Le travaux a bien été modifié');

            return $this->redirectToRoute('app_admin_work_list');
        }

        return $this->render('admin/data/works/edit.html.twig', [
            'form' => $form->createView(),
            'work' => $work
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_work_delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(Work $work, WorkManager $workManager)
    {
        $workManager->delete($work);

        $this->flashSuccess('Le travaux a bien été supprimé');

        return $this->redirectToRoute('app_admin_work_list');
    }
}
