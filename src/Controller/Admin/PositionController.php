<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Service\PositionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/admin/positions")
 */
class PositionController extends GeneralController
{
    /**
     * @Route("/list", name="app_admin_position_list", methods={"POST"})
     */
    public function listAction(Request $request, PositionService $positionService): Response
    {
        if($request->isXmlHttpRequest()) {
            $type = $request->get('type');
            $filter = $request->get('filter');

            if ($filter) {
                $positionService->refactorFilters($filter);
            }

            $list = $positionService->loadList($type, $filter);

            $serializer = new Serializer(array(new ObjectNormalizer()));
            $list = $serializer->normalize($list, null, array('attributes' => array('id', 'name')));

            return $this->json($list);
        }

        return $this->redirect($this->generateUrl('app_admin_accessdenied'));
    }

    /**
     * @Route("/load", name="app_admin_position_load", methods={"POST"})
     */
    public function loadAction(Request $request, PositionService $positionService): Response
    {
        if($request->isXmlHttpRequest()) {
            $type = $request->get('type');
            $id = $request->get('id');

            $data = $positionService->loadPosition($id, $type);

            return $this->json($data);
        }

        return $this->redirect($this->generateUrl('app_admin_accessdenied'));
    }
}
