<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\Lot;
use App\Entity\Tree;
use App\Entity\TreePicture;
use App\Form\Admin\GenerateTreeQrCodeType;
use App\Form\Admin\MoveInLotType;
use App\Form\Admin\TreeType;
use App\Manager\TreeManager;
use App\Manager\TreePictureManager;
use App\Manager\TreeStatusManager;
use App\Repository\NurseryRepository;
use App\Repository\TreeRepository;
use App\Service\Datatable\TreeDT;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/trees", name="app_admin_tree_")
 */
class TreeController extends GeneralController
{
    /**
     * @Route("/list", name="list")
     */
    public function listAction(TreeRepository $treeRepository, NurseryRepository $nurseryRepository): Response
    {
        return $this->render('admin/tree/list.html.twig', [
            'nbTree' => $treeRepository->count([]),
            'nbNursery' => $nurseryRepository->count([])
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function addAction(Request $request, TreeManager $treeManager): Response
    {
        $tree = new Tree();

        $form = $this->createForm(TreeType::class, $tree);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $treeManager->save($tree);
            $this->flashSuccess('L\'arbre a bien été ajouté');

            return $this->redirectToRoute('app_admin_tree_list');
        }

        return $this->render('admin/tree/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function editAction(Request $request, Tree $tree, TreeManager $treeManager): Response
    {
        $form = $this->createForm(TreeType::class, $tree);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $treeManager->save($tree);
            $this->flashSuccess('L\'arbre a bien été modifié');

            return $this->redirectToRoute('app_admin_tree_list');
        }

        return $this->render('admin/tree/edit.html.twig', [
            'form' => $form->createView(),
            'tree' => $tree
        ]);
    }

    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function detailAction(Tree $tree): Response
    {
        return $this->render('admin/tree/detail.html.twig', [
            'tree' => $tree
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteAction(Tree $tree, TreeManager $treeManager): Response
    {
        $treeManager->delete($tree);

        $this->flashSuccess('L\'arbre a bien été supprimé');

        return $this->redirectToRoute('app_admin_tree_list');
    }

    /**
     * @Route("/multiDelete", name="multidelete", methods={"POST"})
     */
    public function multiDelete(Request $request, TreeManager $treeManager): Response
    {
        if ($request->isXmlHttpRequest())
        {
            $idTrees = $request->get('trees');
            $trees = [];

            foreach ($idTrees as $idTree)
            {
                if ($idTree && $tree = $treeManager->getTree($idTree))
                {
                    $trees[] = $tree;
                }
            }

            foreach ($trees as $tree)
            {
                $treeManager->delete($tree);
            }

            $this->flashSuccess('Les arbres ont bien été supprimés');
        }

        return new JsonResponse(null);
    }

    /**
     * @Route("/regenerateQrcodes", name="regenerateqrcode", methods={"GET"})
     */
    public function regenerateQrcodes(Request $request, TreeManager $treeManager, Pdf $knpSnappyPdf): Response
    {
            $idTrees = explode(',', $request->get('trees'));
            $trees = [];
            $qrCodes = [];

            foreach ($idTrees as $idTree)
            {
                if ($tree = $treeManager->getTree((int)$idTree))
                {
                    $trees[] = $tree;
                }
            }

            foreach ($trees as $tree)
            {
                $qrCodes[] = [
                    'path'  => $tree->getQrCode(),
                    'id'    => $tree->getId()
                ];
            }

            $html = $this->renderView('admin/pdf/qrCodes.html.twig', array(
                'qrCodes'  => $qrCodes
            ));

            $options = [
                'margin-top'    => 9,
                'margin-right'  => 9,
                'margin-bottom' => 9,
                'margin-left'   => 9,
            ];

            return new PdfResponse(
                $knpSnappyPdf->getOutputFromHtml($html, $options),
                'qrCodes.pdf'
            );

    }

    /**
     * @Route("/generateqrcode", name="generateqrcode")
     */
    public function generateQrCodesAction(Request $request, TreeManager $treeManager, Pdf $knpSnappyPdf): Response
    {
        $form = $this->createForm(GenerateTreeQrCodeType::class, [], [
            'action' => $this->generateUrl('app_admin_tree_generateqrcode')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $nbQrCode = (int)$form->get('nbQrCode')->getData();
            $nursery = $form->get('nursery')->getData();

            /** @var Lot $lot */
            $lot = $form->get('lot')->getData();

            $qrCodes = [];

            for ($i = 0; $i < $nbQrCode; $i++)
            {
                $tree = new Tree();
                $tree->setNursery($nursery);
                $tree->setLot($lot);

                if ($lot)
                {
                    $tree->setAgeRecovery($lot->getAgeRecovery());
                }

                $treeManager->save($tree);

                $qrCodes[] = [
                    'path'  => $tree->getQrCode(),
                    'id'    => $tree->getId()
                ];
            }

            $html = $this->renderView('admin/pdf/qrCodes.html.twig', array(
                'qrCodes'  => $qrCodes
            ));

            $options = [
                'margin-top'    => 9,
                'margin-right'  => 9,
                'margin-bottom' => 9,
                'margin-left'   => 9,
            ];

            return new PdfResponse(
                $knpSnappyPdf->getOutputFromHtml($html, $options),
                'qrCodes.pdf'
            );
        }

        return $this->render('admin/tree/modalGenerateQrCode.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/datatable", name="datatable", methods={"POST"})
     */
    public function datatable(Request $request, TreeDT $treeDT, TreeStatusManager $treeStatusManager)
    {
        $start = (int)$request->get('start');
        $length = (int)$request->get('length');
        $draw = $request->get('draw');
        $search = $request->get('search')['value'];
        $columns = $request->get('columns');
        $order = $request->get('order')[0] ?? null;

        $allTrees = $treeDT->all($columns, $order, $search);

        $trees = array_slice($allTrees, $start, $length);
        $countTree = count($allTrees);

        $arrayTree = [];

        /** @var Tree $tree */
        foreach ($trees as $tree)
        {
            $arrayTree[] = [
                'active'        => false,
                'id'            => $tree->getId(),
                'status'        => $treeStatusManager->getIcon($tree->getStatus()),
                'name'          => $tree->getSpecies() ? (string) $tree->getSpecies()->getName() : '',
                'greenhouse'    => $tree->getGreenhouse() ? sprintf('<a href="%s">%s</a>',
                    $this->generateUrl('app_admin_greenhouse_detail', ['id' => $tree->getGreenhouse()->getId(), 'show' => true]),
                    $tree->getGreenhouse()
                ) : '',
                'cultureTable'  => $tree->getCultureTable() ? (string) $tree->getCultureTable() : '',
                'segment'       => $tree->getSegment() ? (string) $tree->getSegment() : '',
                'tableColumn'   => $tree->getTableColumn() ? (string) $tree->getTableColumn() : '',
                'columnRow'     => $tree->getColumnRow() ? (string) $tree->getColumnRow() : '',
                'actions'       => $this->renderView('admin/tree/_action_buttons.html.twig', ['tree' => $tree])
            ];
        }

        return $this->json([
            "draw"              => $draw,
            "recordsTotal"      => $countTree,
            "recordsFiltered"   => $countTree,
            "data"              => $arrayTree
        ]);
    }

    /**
     * @Route("/datatable/filter", name="datatable_filter", methods={"GET"})
     */
    public function datatableFilter(Request $request, TreeDT $treeDT): Response
    {
        $data = [];

        if ($request->isXmlHttpRequest()) {
            $data = $treeDT->getFilterList($request->get('type'));
        }

        return $this->json($data);
    }

    /**
     * @Route("/uploadPicture/{id}/{treePicture}", defaults={ "treePicture" = null }, name="uploadpicture", methods={"POST"})
     */
    public function uploadPicture(Request $request, Tree $tree, ?TreePicture $treePicture, TreePictureManager $treePictureManager): Response
    {
        $file = $request->get('picture');
        $file = str_replace('data:image/png;base64,', '', $file);
        $file = str_replace(' ', '+', $file);
        $data = base64_decode($file);

        $original = $request->get('original');
        $original = str_replace('data:image/png;base64,', '', $original);
        $original = str_replace(' ', '+', $original);
        $originalData = base64_decode($original);

        $folder = $tree->getId();

        $filename = 'upload/tree/' . $folder . '/' .  uniqid(mt_rand(), true). '.png';
        $filenameOriginal = 'upload/tree/' . $folder . '/' .  uniqid(mt_rand(), true). '.png';

        if (!$treePicture)
        {
            $treePicture = new TreePicture();
            $treePicture->setTree($tree);
            $treePicture->setPathOriginal($filenameOriginal);
            $treePicture->setPath($filename);

            $tree->addTreePicture($treePicture);

            $treePictureManager->save($treePicture);
        }

        $path = $this->getParameter('kernel.project_dir') . '/public/';

        if ($folder)
        {
            if (!file_exists('upload/tree/' . $folder) &&
                !mkdir('upload/tree/' . $folder, 0777, true) &&
                !is_dir('upload/tree/' . $folder))
            {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', 'path/to/directory'));
            }
        }

        $o = new \stdClass();

        if (($lastPicture = $treePicture->getPath()) && file_exists($path . $lastPicture))
        {
            unlink($path . $lastPicture);
        }

        if (($lastPicture = $treePicture->getPathOriginal()) && file_exists($path . $lastPicture))
        {
            unlink($path . $lastPicture);
        }

        file_put_contents($path . $filename, $data);
        file_put_contents($path . $filenameOriginal, $originalData);

        $o->name = $filename;
        $o->content = '<img src="/'. $filename .'"/>';

        $o->id = $treePicture->getId();
        $treePicture->setPath($filename);
        $treePictureManager->save($treePicture);

        return $this->json($o);
    }

    /**
     * @Route("/removePicture/{id}", name="removepicture")
     */
    public function removePicture(Request $request, TreePicture $treePicture, TreePictureManager $treePictureManager): Response
    {
        if ($request->isXmlHttpRequest())
        {
            $treePictureManager->delete($treePicture);
        }

        return $this->json(true);
    }

    /**
     * @Route("/moveInLot", name="moveinlot")
     */
    public function moveInLot(Request $request, TreeManager $treeManager): Response
    {
        $form = $this->createForm(MoveInLotType::class, [], [
            'action' => $this->generateUrl('app_admin_tree_moveinlot')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var Lot $lot */
            $lot = $form->get('lot')->getData();
            $trees = $form->get('tree')->getData();

            /** @var Tree $tree */
            foreach ($trees as $tree)
            {
                $tree->setLot($lot);
                $treeManager->save($tree);
            }

            $this->flashSuccess('Les arbres ont bien été déplacés dans le lot ' . $lot);

            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }

        return $this->render('admin/tree/moveInLot.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/removeTreeGreenhouse", name="removetreegreenhouse", methods={"POST"})
     */
    public function removeTreeGreenhouse(Request $request, TreeManager $treeManager): Response
    {
        if ($request->isXmlHttpRequest())
        {
            $idTrees = $request->get('trees');
            $trees = [];

            foreach ($idTrees as $idTree)
            {
                if ($idTree && $tree = $treeManager->getTree($idTree))
                {
                    $trees[] = $tree;
                }
            }

            foreach ($trees as $tree)
            {
                $tree->setGreenhouse(null);
                $tree->setSegment(null);
                $tree->setColumnRow(null);
                $tree->setTableColumn(null);
                $tree->setCultureTable(null);

                $treeManager->save($tree);
            }

            $this->flashSuccess('Les arbres ont bien été retirés');
        }

        return new JsonResponse(null);
    }
}
