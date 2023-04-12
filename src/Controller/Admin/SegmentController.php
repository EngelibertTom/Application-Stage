<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\Segment;
use App\Entity\User;
use App\Form\Admin\SegmentType;
use App\Manager\SegmentManager;
use App\Repository\CultureTableRepository;
use App\Repository\SegmentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/segments", name="app_admin_segment_")
 */
class SegmentController extends GeneralController
{
    /**
     * @Route("/list", name="list")
     * @IsGranted("ROLE_MANAGER")
     */
    public function listAction(CultureTableRepository $cultureTableRepository, SegmentRepository $segmentRepository): Response
    {
        return $this->render('admin/position/segment/list.html.twig', [
            'nbSegment' => $segmentRepository->count([]),
            'nbCultureTable' => $cultureTableRepository->count([])
        ]);
    }

    /**
     * @Route("/add", name="add")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function addAction(Request $request, SegmentManager $segmentManager): Response
    {
        $segment = new Segment();

        $form = $this->createForm(SegmentType::class, $segment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $segmentManager->save($segment);
            $this->flashSuccess('Le segment a bien été ajouté');

            return $this->redirectToRoute('app_admin_segment_list');
        }

        return $this->render('admin/position/segment/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, Segment $segment, SegmentManager $segmentManager): Response
    {
        $form = $this->createForm(SegmentType::class, $segment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $segmentManager->save($segment);
            $this->flashSuccess('Le segment a bien été modifié');

            return $this->redirectToRoute('app_admin_segment_list');
        }

        return $this->render('admin/position/segment/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/datatable", name="datatable", methods={"POST"})
     * @IsGranted("ROLE_MANAGER")
     */
    public function datatable(Request $request, SegmentManager $segmentManager)
    {
        $start = (int)$request->get('start');
        $length = (int)$request->get('length');
        $draw = $request->get('draw');
        $search = $request->get('search')['value'];
        $order = $request->get('order')[0] ?? null;

        // todo: Pouvoir rechercher chaque type séparement.
        $filter = empty($search) ? [] : [
            's.name' => $search
        ];

        $allSegments = $segmentManager->getSegments($filter, $segmentManager->manageOrderList($order));
        $segments = array_slice($allSegments, $start, $length);
        $countSegment = count($allSegments);

        $arraySegment = [];

        /** @var Segment $segment */
        foreach ($segments as $segment)
        {
            $arraySegment[] = [
                'id'            => $segment->getId(),
                'name'          => $segment->getName(),
                'actions'    => $this->renderView('admin/position/segment/_action_buttons.html.twig', ['segment' => $segment])
            ];
        }

        return $this->json([
            "draw"              => $draw,
            "recordsTotal"      => $countSegment,
            "recordsFiltered"   => $countSegment,
            "data"              => $arraySegment
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function delete(Segment $segment, SegmentManager $segmentManager)
    {
        $segmentManager->delete($segment);

        $this->flashSuccess('Le segment a bien été supprimé');

        return $this->redirectToRoute('app_admin_segment_list');
    }
}
