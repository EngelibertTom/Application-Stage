<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\CultureTable;
use App\Entity\Greenhouse;
use App\Entity\Tree;
use App\Entity\User;
use App\Form\Admin\GreenhouseType;
use App\Manager\GreenhouseManager;
use App\Repository\NurseryRepository;
use App\Service\GreenhouseService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/admin/greenhouses")
 */
class GreenhouseController extends GeneralController
{
    /**
     * @Route("/list", name="app_admin_greenhouse_list")
     * @IsGranted("ROLE_MANAGER")
     */
    public function listAction(GreenhouseManager $greenhouseManager, NurseryRepository $nurseryRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $greenhouses = $greenhouseManager->getNurseryGreenhouses($user);

        return $this->render('admin/position/greenhouse/list.html.twig', [
            'greenhouses' => $greenhouses,
            'nbNursery' => $nurseryRepository->count([])
        ]);
    }

    /**
     * @Route("/add", name="app_admin_greenhouse_add")
     * @IsGranted("ROLE_MANAGER")
     */
    public function addAction(Request $request, GreenHouseManager $greenHouseManager, NurseryRepository $nurseryRepository): Response
    {
        $greenhouse = new Greenhouse();

        $form = $this->createForm(GreenhouseType::class, $greenhouse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $greenHouseManager->save($greenhouse);
            $this->flashSuccess('La serre a bien été ajoutée');

            return $this->redirectToRoute('app_admin_greenhouse_list');
        }

        return $this->render('admin/position/greenhouse/edit.html.twig', [
            'form' => $form->createView(),
            'nbNursery' => $nurseryRepository->count([])
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_greenhouse_edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, Greenhouse $greenhouse, GreenhouseManager $greenHouseManager,
                               GreenhouseService $greenhouseService): Response
    {
        $this->accessGranted($greenhouseService, $greenhouse);

        $form = $this->createForm(GreenhouseType::class, $greenhouse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $greenHouseManager->save($greenhouse);
            $this->flashSuccess('La serre a bien été modifiée');

            return $this->redirectToRoute('app_admin_greenhouse_list');
        }

        return $this->render('admin/position/greenhouse/edit.html.twig', [
            'form' => $form->createView(),
            'greenhouse' => $greenhouse
        ]);
    }

    /**
     * @Route("/detail/{id}", name="app_admin_greenhouse_detail")
     * @IsGranted("ROLE_MANAGER")
     */
    public function detailAction(Request $request, Greenhouse $greenhouse, GreenhouseService $greenhouseService): Response
    {
        $this->accessGranted($greenhouseService, $greenhouse);

        return $this->render('admin/position/greenhouse/detail.html.twig', [
            'greenhouse' => $greenhouse
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_greenhouse_delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(Greenhouse $greenhouse, GreenhouseManager $greenhouseManager, GreenhouseService $greenhouseService)
    {
        $this->accessGranted($greenhouseService, $greenhouse);

        $greenhouseManager->delete($greenhouse);

        $this->flashSuccess('La serre a bien été supprimée');

        return $this->redirectToRoute('app_admin_greenhouse_list');
    }

    /**
     * @Route("/schema_table/{id}/{cultureTable}", name="app_admin_greenhouse_schematable")
     */
    public function schemaTable(Request $request, Greenhouse $greenhouse, CultureTable $cultureTable, GreenhouseService $greenhouseService): Response
    {
        $this->accessGranted($greenhouseService, $greenhouse);

        $infoCultureTable = $greenhouseService->getInfoSchemaCultureTable($greenhouse, $cultureTable);

        return $this->render('admin/position/greenhouse/_schema_table.html.twig', [
            'trees' => $infoCultureTable['trees'],
            'schemaCultureTable' => $infoCultureTable['schemaCultureTable'],
            'spaceAvailable' => $infoCultureTable['spaceAvailable'],
        ]);
    }

    /**
     * @Route("/diagram_table/{id}/{cultureTable}", name="app_admin_greenhouse_schematable")
     */
    public function diagramTable(Request $request, Greenhouse $greenhouse, CultureTable $cultureTable, GreenhouseService $greenhouseService): Response
    {
        $this->accessGranted($greenhouseService, $greenhouse);

        $infoCultureTable = $greenhouseService->getInfoDiagramCultureTable($greenhouse, $cultureTable);

        return $this->render('admin/position/greenhouse/_diagram_table.html.twig', [
            'cultureTable' => $cultureTable,
            'infoCultureTable' => $infoCultureTable
        ]);
    }

    /**
     * Vérifie les droits d'accès à la serre.
     *
     * @param GreenhouseService $greenhouseService
     * @param Greenhouse $greenhouse
     */
    private function accessGranted(GreenhouseService $greenhouseService, Greenhouse $greenhouse): void
    {
        /** @var User $user */
        $user = $this->getUser();

        // Si l'utilisateur n'a pas les droits sur cette serre alors on le redirige vers une page 500;
        if (!$greenhouseService->userGrantedGreenhouse($user, $greenhouse))
        {
            throw new AccessDeniedException('Vous n\'avez pas les droits sur cette pépinière.');
        }
    }
}
