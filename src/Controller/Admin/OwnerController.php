<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Form\Admin\OwnerType;
use App\Entity\Owner;
use App\Manager\OwnerManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/owners", name="app_admin_owner_")
 */
class OwnerController extends GeneralController
{
    /**
     * @Route("/list", name="list")
     */
    public function listAction(OwnerManager $ownerManager): Response
    {
        return $this->render('admin/owner/list.html.twig', [
            'owners' => $ownerManager->getOwners()
        ]);
    }

    /**
     * @Route("/add", name="add")
     * @IsGranted("ROLE_MANAGER")
     */
    public function addAction(Request $request, OwnerManager $ownerManager): Response
    {
        $owner = new Owner();

        $form = $this->createForm(OwnerType::class, $owner);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ownerManager->save($owner);
            $this->flashSuccess('L\'Adoptant à bien été ajouté');

            return $this->redirectToRoute('app_admin_owner_list');
        }

        return $this->render('admin/owner/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, Owner $owner, OwnerManager $ownerManager)
    {
        $form = $this->createForm(OwnerType::class, $owner);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ownerManager->save($owner);
            $this->flashSuccess('L\'Adoptant à bien été modifié');

            return $this->redirectToRoute('app_admin_owner_list');
        }

        return $this->render('admin/owner/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/delete/{id}", name="delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(Owner $owner, OwnerManager $ownerManager): Response
    {
        $ownerManager->delete($owner);

        $this->flashSuccess('L\'Adoptant a bien été supprimé');

        return $this->redirectToRoute('app_admin_owner_list');
    }

    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detail(Owner $owner, OwnerManager $ownerManager): Response
    {
        return $this->render("admin/owner/detail.html.twig", [
            "owner" => $owner
        ]);
    }

    /**
     * @Route("/datatable", name="app_admin_owner_datatable", methods={"POST"})
     */
    public function datatable(Request $request, OwnerManager $ownerManager)
    {
        $start = (int)$request->get('start');
        $length = (int)$request->get('length');
        $draw = $request->get('draw');
        $search = $request->get('search')['value'];
        $order = $request->get('order')[0] ?? null;

        $allOwners = $ownerManager->getOwners($ownerManager->manageOrderList($order), $search);
        $owners = array_slice($allOwners, $start, $length);
        $countOwner = count($allOwners);

        $arrayOwner = [];

        /** @var Owner $owner */
        foreach ($owners as $owner) {

            $arrayOwner[] = [
                'name' => $owner->getName(),
                'email' => $owner->getEmail(),
                'phone' => $owner->getPhone(),
                'nbAdoption' => $owner->getAdoptions()->count(),
                'postalCode' => $owner->getPostalCode(),
                'species' => $owner->getListAdoptionSpecies(),
                'actions' => $this->renderView('admin/owner/_action_buttons.html.twig', ['owner' => $owner])
            ];
        }

        return $this->json([
            "draw" => $draw,
            "recordsTotal" => $countOwner,
            "recordsFiltered" => $countOwner,
            "data" => $arrayOwner
        ]);
    }

    public function test(OwnerManager $ownerManager)
    {
        $owner = new Owner();
        $ownerManager->save($owner);
    }

}



