<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\Lot;
use App\Entity\Observation;
use App\Entity\TreeWork;
use App\Form\Admin\GenerateLotQrCodeType;
use App\Form\Admin\LotType;
use App\Form\Admin\ObservationType;
use App\Form\Admin\TreeWorkType;
use App\Manager\LotManager;
use App\Manager\ObservationManager;
use App\Manager\TreeWorkManager;
use App\Repository\LotRepository;
use App\Repository\NurseryRepository;
use App\Repository\ObservationRepository;
use App\Repository\TreeRepository;
use App\Repository\TreeWorkRepository;
use App\Service\Datatable\ObservationDT;
use App\Service\Datatable\TreeWorksDT;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/observations", name="app_admin_observation_")
 */
class ObservationController extends GeneralController
{
    /**
     * @Route("/list", name="list")
     */
    public function listAction(ObservationRepository $observationRepository, TreeRepository $treeRepository): Response
    {
        return $this->render('admin/observation/list.html.twig', [
            'nbObservation' => $observationRepository->count([]),
            'nbTree' => $treeRepository->count([])
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function addAction(Request $request, ObservationManager $observationManager): Response
    {
        $observation = new Observation();

        $form = $this->createForm(ObservationType::class, $observation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $observationManager->save($observation);
            $this->flashSuccess('L\'observation a bien été ajoutée');

            return $this->redirectToRoute('app_admin_observation_list');
        }

        return $this->render('admin/observation/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function editAction(Request $request, Observation $observation, ObservationManager $observationManager): Response
    {
        $form = $this->createForm(ObservationType::class, $observation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $observationManager->save($observation);
            $this->flashSuccess('L\'observation a bien été modifiée');

            return $this->redirectToRoute('app_admin_observation_list');
        }

        return $this->render('admin/observation/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteAction(Observation $observation, ObservationManager $observationManager): Response
    {
        $observationManager->delete($observation);

        $this->flashSuccess('L\'observation a bien été supprimée');

        return $this->redirectToRoute('app_admin_observation_list');
    }

    /**
     * @Route("/datatable", name="datatable", methods={"POST"})
     */
    public function datatable(Request $request, ObservationDT $observationDT)
    {
        $start = $request->get('start');
        $length = $request->get('length');
        $draw = $request->get('draw');
        $columns = $request->get('columns');
        $search = $request->get('search')['value'];
        $order = $request->get('order')[0] ?? null;

        $allObservation = $observationDT->all($columns, $order, $search);

        $observations = array_slice($allObservation, $start, $length);
        $countObservation = count($allObservation);

        $arrayObservation = [];

        /** @var Observation $observation */
        foreach ($observations as $observation)
        {
            $arrayObservation[] = [
                'date'          => $observation->getDate()->format('d/m/Y'),
                'tree'          => '<a href="'.$this->generateUrl('app_tree_detail', ['id' => $observation->getTree()->getId(), 'show' => true]).'"> ' . $observation->getTree() .' </a>',
                'species'       => $observation->getTree()->getSpecies() ? (string) $observation->getTree()->getSpecies() : '-',
                'greenhouse'    => $observation->getTree()->getGreenhouse() ? (string) $observation->getTree()->getGreenhouse() : '-',
                'user'          => (string) $observation->getUser(),
                'comment'       => $observation->getComment(),
                'actions'       => $this->renderView('admin/observation/_action_buttons.html.twig', ['observation' => $observation])
            ];
        }

        return $this->json([
            "draw"              => $draw,
            "recordsTotal"      => $countObservation,
            "recordsFiltered"   => $countObservation,
            "data"              => $arrayObservation
        ]);
    }
}
