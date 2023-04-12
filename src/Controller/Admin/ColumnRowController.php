<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\ColumnRow;
use App\Entity\CultureTable;
use App\Entity\TableColumn;
use App\Entity\User;
use App\Form\Admin\ColumnRowType;
use App\Form\Admin\CultureTableType;
use App\Form\Admin\TableColumnType;
use App\Manager\ColumnRowManager;
use App\Manager\CultureTableManager;
use App\Manager\TableColumnManager;
use App\Repository\ColumnRowRepository;
use App\Repository\CultureTableRepository;
use App\Repository\GreenhouseRepository;
use App\Repository\SegmentRepository;
use App\Repository\TableColumnRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/column-rows", name="app_admin_columnrow_")
 */
class ColumnRowController extends GeneralController
{
    /**
     * @Route("/list", name="list")
     * @IsGranted("ROLE_MANAGER")
     */
    public function listAction(ColumnRowRepository $columnRowRepository, TableColumnRepository $tableColumnRepository): Response
    {
        return $this->render('admin/position/columnRow/list.html.twig', [
            'nbColumnRows' => $columnRowRepository->count([]),
            'nbTableColumn' => $tableColumnRepository->count([])
        ]);
    }

    /**
     * @Route("/add", name="add")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function addAction(Request $request, ColumnRowManager $columnRowManager, TableColumnRepository $tableColumnRepository): Response
    {
        $columnRow = new ColumnRow();

        $form = $this->createForm(ColumnRowType::class, $columnRow);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $columnRowManager->save($columnRow);
            $this->flashSuccess('Le rang a bien été ajouté');

            return $this->redirectToRoute('app_admin_columnrow_list');
        }

        return $this->render('admin/position/columnRow/edit.html.twig', [
            'form' => $form->createView(),
            'nbTableColumn' => $tableColumnRepository->count([])
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, ColumnRow $columnRow, ColumnRowManager $columnRowManager): Response
    {
        $form = $this->createForm(ColumnRowType::class, $columnRow);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $columnRowManager->save($columnRow);
            $this->flashSuccess('Le rang a bien été modifiée');

            return $this->redirectToRoute('app_admin_columnrow_list');
        }

        return $this->render('admin/position/columnRow/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/datatable", name="datatable", methods={"POST"})
     * @IsGranted("ROLE_MANAGER")
     */
    public function datatable(Request $request, ColumnRowManager $columnRowManager)
    {
        $start = (int)$request->get('start');
        $length = (int)$request->get('length');
        $draw = $request->get('draw');
        $search = $request->get('search')['value'];
        $order = $request->get('order')[0] ?? null;

        // todo: Pouvoir rechercher chaque type séparement.
        $filter = empty($search) ? [] : [
            'c.name' => $search
        ];

        $allColumnRows = $columnRowManager->getColumnRows($filter, $columnRowManager->manageOrderList($order));
        $columnRows = array_slice($allColumnRows, $start, $length);
        $countColumnRow = count($allColumnRows);

        $arrayColumnRow = [];

        /** @var ColumnRow $columnRow */
        foreach ($columnRows as $columnRow)
        {
            $arrayColumnRow[] = [
                'id'            => $columnRow->getId(),
                'name'          => $columnRow->getName(),
                'actions'    => $this->renderView('admin/position/columnRow/_action_buttons.html.twig', ['columnRow' => $columnRow])
            ];
        }

        return $this->json([
            "draw"              => $draw,
            "recordsTotal"      => $countColumnRow,
            "recordsFiltered"   => $countColumnRow,
            "data"              => $arrayColumnRow
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(ColumnRow $columnRow, ColumnRowManager $columnRowManager)
    {
        $columnRowManager->delete($columnRow);

        $this->flashSuccess('La rang a bien été supprimé');

        return $this->redirectToRoute('app_admin_columnrow_list');
    }
}
