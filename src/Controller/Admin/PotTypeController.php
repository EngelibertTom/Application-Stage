<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\PotType;
use App\Entity\Style;
use App\Form\Admin\PotTypeType;
use App\Form\Admin\StyleType;
use App\Manager\PotTypeManager;
use App\Manager\StyleManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/pot-types", name="app_admin_pottype_")
 */
class PotTypeController extends GeneralController
{
    /**
     * @Route("/list", name="list")
     * @IsGranted("ROLE_MANAGER")
     */
    public function listAction(PotTypeManager $potTypeManager): Response
    {
        return $this->render('admin/data/potType/list.html.twig', [
            'potTypes' => $potTypeManager->getPotTypes()
        ]);
    }

    /**
     * @Route("/add", name="add")
     * @IsGranted("ROLE_MANAGER")
     */
    public function addAction(Request $request, PotTypeManager $potTypeManager): Response
    {
        $potType = new PotType();

        $form = $this->createForm(PotTypeType::class, $potType);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $potTypeManager->save($potType);
            $this->flashSuccess('Le type de pot a bien été ajouté');

            return $this->redirectToRoute('app_admin_pottype_list');
        }

        return $this->render('admin/data/potType/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, PotType $potType, PotTypeManager $potTypeManager): Response
    {
        $form = $this->createForm(PotTypeType::class, $potType);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $potTypeManager->save($potType);
            $this->flashSuccess('Le type de pot a bien été modifié');

            return $this->redirectToRoute('app_admin_pottype_list');
        }

        return $this->render('admin/data/potType/edit.html.twig', [
            'form' => $form->createView(),
            'potType' => $potType
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(PotType $potType, PotTypeManager $potTypeManager)
    {
        $potTypeManager->delete($potType);

        $this->flashSuccess('Le type de pot a bien été supprimé');

        return $this->redirectToRoute('app_admin_pottype_list');
    }
}
