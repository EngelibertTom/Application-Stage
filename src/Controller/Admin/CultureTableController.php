<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\CultureTable;
use App\Entity\User;
use App\Form\Admin\CultureTableType;
use App\Manager\CultureTableManager;
use App\Repository\CultureTableRepository;
use App\Repository\GreenhouseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/culture-tables", name="app_admin_culturetable_")
 */
class CultureTableController extends GeneralController
{
    /**
     * @Route("/list", name="list")
     * @IsGranted("ROLE_MANAGER")
     */
    public function listAction(GreenhouseRepository $greenhouseRepository, CultureTableRepository $cultureTableRepository): Response
    {
        return $this->render('admin/position/cultureTable/list.html.twig', [
            'nbGreenHouse' => $greenhouseRepository->count([]),
            'nbCultureTable' => $cultureTableRepository->count([])
        ]);
    }

    /**
     * @Route("/add", name="add")
     * @IsGranted("ROLE_MANAGER")
     */
    public function addAction(Request $request, CultureTableManager $cultureTableManager, GreenhouseRepository $greenhouseRepository): Response
    {
        $cultureTable = new CultureTable();

        $form = $this->createForm(CultureTableType::class, $cultureTable);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $cultureTableManager->save($cultureTable);
            $this->flashSuccess('La table a bien été ajoutée');

            return $this->redirectToRoute('app_admin_culturetable_list');
        }

        return $this->render('admin/position/cultureTable/edit.html.twig', [
            'form' => $form->createView(),
            'nbGreenHouse' => $greenhouseRepository->count([])
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, CultureTable $culturetable, CultureTableManager $cultureTableManager): Response
    {
        $form = $this->createForm(CultureTableType::class, $culturetable);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $cultureTableManager->save($culturetable);
            $this->flashSuccess('La table a bien été modifiée');

            return $this->redirectToRoute('app_admin_culturetable_list');
        }

        return $this->render('admin/position/cultureTable/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/datatable", name="datatable", methods={"POST"})
     * @IsGranted("ROLE_MANAGER")
     */
    public function datatable(Request $request, CultureTableManager $cultureTableManager)
    {
        $start = (int)$request->get('start');
        $length = (int)$request->get('length');
        $draw = $request->get('draw');
        $search = $request->get('search')['value'];
        $order = $request->get('order')[0] ?? null;

        // todo: Pouvoir rechercher chaque type séparement.
        $filter = empty($search) ? [] : [
            'ct.name' => $search
        ];

        $cultureTables = $cultureTableManager->getCultureTables($filter, $cultureTableManager->manageOrderList($order));

        $cultureTables = array_slice($cultureTables, $start, $length);
        $countCultureTable = count($cultureTables);

        $arrayCultureTable = [];

        /** @var CultureTable $cultureTable */
        foreach ($cultureTables as $cultureTable)
        {
            $arrayCultureTable[] = [
                'id'            => $cultureTable->getId(),
                'name'          => $cultureTable->getName(),
                'actions'    => $this->renderView('admin/position/cultureTable/_action_buttons.html.twig', ['cultureTable' => $cultureTable])
            ];
        }

        return $this->json([
            "draw"              => $draw,
            "recordsTotal"      => $countCultureTable,
            "recordsFiltered"   => $countCultureTable,
            "data"              => $arrayCultureTable
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(CultureTable $cultureTable, CultureTableManager $cultureTableManager)
    {
        $cultureTableManager->delete($cultureTable);

        $this->flashSuccess('La table a bien été supprimée');

        return $this->redirectToRoute('app_admin_culturetable_list');
    }
}
