<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\CultureTable;
use App\Entity\TableColumn;
use App\Entity\User;
use App\Form\Admin\CultureTableType;
use App\Form\Admin\TableColumnType;
use App\Manager\CultureTableManager;
use App\Manager\TableColumnManager;
use App\Repository\CultureTableRepository;
use App\Repository\GreenhouseRepository;
use App\Repository\SegmentRepository;
use App\Repository\TableColumnRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/table-columns", name="app_admin_tablecolumn_")
 */
class TableColumnController extends GeneralController
{
    /**
     * @Route("/list", name="list")
     * @IsGranted("ROLE_MANAGER")
     */
    public function listAction(TableColumnRepository $tableColumnRepository, SegmentRepository $segmentRepository): Response
    {
        return $this->render('admin/position/tableColumn/list.html.twig', [
            'nbTableColumn' => $tableColumnRepository->count([]),
            'nbSegment' => $segmentRepository->count([])
        ]);
    }

    /**
     * @Route("/add", name="add")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function addAction(Request $request, TableColumnManager $tableColumnManager, SegmentRepository $segmentRepository): Response
    {
        $tableColumn = new TableColumn();

        $form = $this->createForm(TableColumnType::class, $tableColumn);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $tableColumnManager->save($tableColumn);
            $this->flashSuccess('La colonne a bien été ajoutée');

            return $this->redirectToRoute('app_admin_tablecolumn_list');
        }

        return $this->render('admin/position/tableColumn/edit.html.twig', [
            'form' => $form->createView(),
            'nbSegment' => $segmentRepository->count([])
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, TableColumn $tablecolumn, TableColumnManager $tableColumnManager): Response
    {
        $form = $this->createForm(TableColumnType::class, $tablecolumn);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $tableColumnManager->save($tablecolumn);
            $this->flashSuccess('La colonne a bien été modifiée');

            return $this->redirectToRoute('app_admin_tablecolumn_list');
        }

        return $this->render('admin/position/tableColumn/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/datatable", name="datatable", methods={"POST"})
     * @IsGranted("ROLE_MANAGER")
     */
    public function datatable(Request $request, TableColumnManager $tableColumnManager)
    {
        $start = (int)$request->get('start');
        $length = (int)$request->get('length');
        $draw = $request->get('draw');
        $search = $request->get('search')['value'];
        $order = $request->get('order')[0] ?? null;

        // todo: Pouvoir rechercher chaque type séparement.
        $filter = empty($search) ? [] : [
            't.name' => $search
        ];

        $allTableColumns = $tableColumnManager->getTableColumns($filter, $tableColumnManager->manageOrderList($order));
        $tableColumns = array_slice($allTableColumns, $start, $length);
        $countTableColumn = count($allTableColumns);

        $arrayTableColumn = [];

        /** @var TableColumn $tableColumn */
        foreach ($tableColumns as $tableColumn)
        {
            $arrayTableColumn[] = [
                'id'            => $tableColumn->getId(),
                'name'          => $tableColumn->getName(),
                'actions'    => $this->renderView('admin/position/tableColumn/_action_buttons.html.twig', ['tableColumn' => $tableColumn])
            ];
        }

        return $this->json([
            "draw"              => $draw,
            "recordsTotal"      => $countTableColumn,
            "recordsFiltered"   => $countTableColumn,
            "data"              => $arrayTableColumn
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(TableColumn $tableColumn, TableColumnManager $tableColumnManager)
    {
        $tableColumnManager->delete($tableColumn);

        $this->flashSuccess('La colonne a bien été supprimée');

        return $this->redirectToRoute('app_admin_tablecolumn_list');
    }
}
