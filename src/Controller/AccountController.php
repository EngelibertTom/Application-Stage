<?php

namespace App\Controller;

use App\Entity\Adoption;
use App\Entity\Owner;
use App\Form\OwnerType;
use App\Manager\AdoptionManager;
use App\Manager\OwnerManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mon-compte", name="app_account_")
 */
class AccountController extends GeneralController
{
    /**
     * @Route("/arbres", name="trees")
     */
    public function listTreeAction(): Response
    {
        return $this->render('account/trees.html.twig');
    }

    /**
     * @Route("/delete_adoption/{id}", name="delete_adoption")
     */
    public function deleteAdoptionAction(Adoption $adoption, AdoptionManager $adoptionManager): Response
    {
        $adoptionManager->delete($adoption);

        $this->flashSuccess('L\'arbre a bien été supprimé');

        return $this->redirectToRoute('app_account_trees');
    }

    /**
     * @Route("/", name="edit")
     */
    public function editAction(Request $request, OwnerManager $ownerManager): Response
    {
        /** @var Owner $owner */
        $owner = $this->getUser();

        $form = $this->createForm(OwnerType::class, $owner, ['edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $ownerManager->save($owner);

            $this->flashSuccess('Les informations ont bien été mise à jour');

            return $this->redirectToRoute('app_account_edit');
        }

        return $this->render('account/edit.html.twig', [
            'formOwner' => $form->createView()
        ]);
    }
}
