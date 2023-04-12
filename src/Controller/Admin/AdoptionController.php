<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\Adoption;
use App\Entity\Nursery;
use App\Form\Admin\NurseryType;
use App\Manager\AdoptionManager;
use App\Manager\NurseryManager;
use App\Service\DashboardService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/adoptions", name="app_admin_adoption_")
 */
class AdoptionController extends GeneralController
{
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, Adoption $adoption, AdoptionManager $adoptionManager)
    {
        $adoptionManager->delete($adoption);

        $this->flashSuccess('L\'adoption a bien été supprimée');

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    
}