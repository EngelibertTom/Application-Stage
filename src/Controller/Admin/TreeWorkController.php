<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\Lot;
use App\Entity\TreeWork;
use App\Form\Admin\GenerateLotQrCodeType;
use App\Form\Admin\LotType;
use App\Form\Admin\TreeWorkType;
use App\Manager\LotManager;
use App\Manager\TreeWorkManager;
use App\Repository\LotRepository;
use App\Repository\NurseryRepository;
use App\Repository\TreeRepository;
use App\Repository\TreeWorkRepository;
use App\Service\Datatable\TreeWorksDT;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tree-works")
 */
class TreeWorkController extends GeneralController
{
    /**
     * @Route("/list", name="app_admin_tree_work_list")
     */
    public function listAction(Request $request, TreeWorkRepository $treeWorkRepository, TreeRepository $treeRepository): Response
    {
        $todo = $request->get('todo') ?? false;

        return $this->render('admin/treeWork/list.html.twig', [
            'nbWorks' => $treeWorkRepository->count( [ 'todo' => $todo ] ),
            'nbTree' => $treeRepository->count([]),
            'todo' => $todo
        ]);
    }

    /**
     * @Route("/add", name="app_admin_tree_work_add")
     */
    public function addAction(Request $request, TreeWorkManager $treeWorkManager): Response
    {
        $treeWork = new TreeWork();

        $form = $this->createForm(TreeWorkType::class, $treeWork);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $treeWorkManager->save($treeWork);
            $this->flashSuccess('Le travaux a bien été ajouté');

            return $this->redirectToRoute('app_admin_tree_work_list');
        }

        return $this->render('admin/treeWork/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_admin_tree_work_edit")
     */
    public function editAction(Request $request, TreeWork $treeWork, TreeWorkManager $treeWorkManager): Response
    {
        $form = $this->createForm(TreeWorkType::class, $treeWork);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $treeWorkManager->save($treeWork);
            $this->flashSuccess('Le travaux a bien été modifié');

            return $this->redirectToRoute('app_admin_tree_work_list');
        }

        return $this->render('admin/treeWork/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_admin_tree_work_delete")
     */
    public function deleteAction(TreeWork $treeWork, TreeWorkManager $treeWorkManager): Response
    {
        $treeWorkManager->delete($treeWork);

        $this->flashSuccess('Le travaux a bien été supprimé');

        return $this->redirectToRoute('app_admin_tree_work_list');
    }

    /**
     * @Route("/datatable", name="app_admin_tree_work_datatable", methods={"POST"})
     */
    public function datatable(Request $request, TreeWorksDT $treeWorksDT)
    {
        $start = $request->get('start');
        $length = $request->get('length');
        $draw = $request->get('draw');
        $columns = $request->get('columns');
        $search = $request->get('search')['value'];
        $order = $request->get('order')[0] ?? null;

        $todo = $request->get('todo') ?? false;

        $columns[] = [
            'search' => [
                'value' => $todo,
                'regex' => 'true'
            ],
            'data' => 'todo'
        ];

        $allTreeWork = $treeWorksDT->all($columns, $order, $search);

        $treeWorks = array_slice($allTreeWork, $start, $length);
        $countTreeWork = count($allTreeWork);

        $arrayTreeWork = [];

        /** @var TreeWork $treeWork */
        foreach ($treeWorks as $treeWork)
        {
            $arrayTreeWork[] = [
                'date'          => $treeWork->getDate()->format('d/m/Y'),
                'tree'          => '<a href="'.$this->generateUrl('app_tree_detail', ['id' => $treeWork->getTree()->getId(), 'show' => true]).'"> ' . $treeWork->getTree()->__toString() .' </a>',
                'work'          => $treeWork->getWork()->getName(),
                'species'       => $treeWork->getTree()->getSpecies() ? (string) $treeWork->getTree()->getSpecies() : '-',
                'greenhouse'    => $treeWork->getTree()->getGreenhouse() ? (string) $treeWork->getTree()->getGreenhouse() : '-',
                'user'          => (string)$treeWork->getUser(),
                'actions'       => $this->renderView('admin/treeWork/_action_buttons.html.twig', ['treeWork' => $treeWork])
            ];
        }

        return $this->json([
            "draw"              => $draw,
            "recordsTotal"      => $countTreeWork,
            "recordsFiltered"   => $countTreeWork,
            "data"              => $arrayTreeWork
        ]);
    }
}
