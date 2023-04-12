<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\OutputType;
use App\Form\Admin\OutputTypeType;
use App\Manager\OutputTypeManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/output-types")
 */
class OutputTypeController extends GeneralController
{
    /**
     * @Route("/list", name="app_admin_outputtype_list")
     * @IsGranted("ROLE_MANAGER")
     */
    public function listAction(OutputTypeManager $outputTypeManager): Response
    {
        return $this->render('admin/data/outputType/list.html.twig', [
            'outputTypes' => $outputTypeManager->getOutputTypes()
        ]);
    }

    /**
     * @Route("/add", name="app_admin_outputtype_add")
     * @IsGranted("ROLE_MANAGER")
     */
    public function addAction(Request $request, OutputTypeManager $outputTypeManager): Response
    {
        $outputType = new OutputType();

        $form = $this->createForm(OutputTypeType::class, $outputType);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $outputTypeManager->save($outputType);
            $this->flashSuccess('Le type de sortie a bien été ajouté');

            return $this->redirectToRoute('app_admin_outputtype_list');
        }

        return $this->render('admin/data/outputType/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_outputtype_edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, OutputType $outputType, OutputTypeManager $outputTypeManager): Response
    {
        $form = $this->createForm(OutputTypeType::class, $outputType);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $outputTypeManager->save($outputType);
            $this->flashSuccess('Le type de sortie a bien été modifié');

            return $this->redirectToRoute('app_admin_outputtype_list');
        }

        return $this->render('admin/data/outputType/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_outputtype_delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(OutputType $outputType, OutputTypeManager $outputTypeManager)
    {
        $outputTypeManager->delete($outputType);

        $this->flashSuccess('Le type de sortie a bien été supprimé');

        return $this->redirectToRoute('app_admin_outputtype_list');
    }
}
