<?php

namespace App\Controller\Admin;

use App\Repository\GreenhouseRepository;
use App\Repository\NurseryRepository;
use App\Repository\OwnerRepository;
use App\Repository\TreeRepository;
use App\Repository\UserRepository;
use App\Service\DashboardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_dashboard")
     */
    public function dashboard(TreeRepository $treeRepository, NurseryRepository $nurseryRepository,
                              GreenhouseRepository $greenhouseRepository, UserRepository $userRepository,
                              OwnerRepository $ownerRepository, DashboardService $dashboardService): Response
    {
        if (!$this->getUser())
        {
            return $this->redirectToRoute('app_admin_login');
        }

        $annualAdoptionStat = $dashboardService->annualAdoptionStat();
        $annualDeadTreeStat = $dashboardService->annualDeadTreeStat();
        $statusTreeStat = $dashboardService->statusTreeStat();

        return $this->render('admin/dashboard/index.html.twig', [
            'nbTree' => $treeRepository->count([]),
            'nbNursery' => $nurseryRepository->count([]),
            'nbGreenhouse' => $greenhouseRepository->count([]),
            'nbUser' => $userRepository->count([]),
            'nbOwner' => $ownerRepository->count([]),
            'annualAdoptionStat' => $annualAdoptionStat,
            'annualDeadTreeStat' => $annualDeadTreeStat,
            'statusTreeStat' => $statusTreeStat
        ]);
    }
}
