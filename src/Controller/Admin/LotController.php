<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\Lot;
use App\Entity\LotPicture;
use App\Form\Admin\GenerateLotQrCodeType;
use App\Form\Admin\LotType;
use App\Manager\LotManager;
use App\Manager\LotPictureManager;
use App\Repository\LotRepository;
use App\Repository\NurseryRepository;
use App\Service\Datatable\LotDT;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/lots", name="app_admin_lot_")
 */
class LotController extends GeneralController
{
    /**
     * @Route("/list", name="list")
     */
    public function listAction(LotRepository $lotRepository, NurseryRepository $nurseryRepository): Response
    {
        return $this->render('admin/lot/list.html.twig', [
            'nbLot' => $lotRepository->count([]),
            'nbNursery' => $nurseryRepository->count([])
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function addAction(Request $request, LotManager $lotManager): Response
    {
        $lot = new Lot();

        $form = $this->createForm(LotType::class, $lot);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $lotManager->save($lot);
            $this->flashSuccess('Le lot a bien été ajouté');

            return $this->redirectToRoute('app_admin_lot_list');
        }

        return $this->render('admin/lot/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function editAction(Request $request, Lot $lot, LotManager $lotManager): Response
    {
        $form = $this->createForm(LotType::class, $lot);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $lotManager->save($lot);
            $this->flashSuccess('Le lot a bien été modifié');

            return $this->redirectToRoute('app_admin_lot_list');
        }

        return $this->render('admin/lot/edit.html.twig', [
            'form' => $form->createView(),
            'lot' => $lot
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteAction(Lot $lot, LotManager $lotManager): Response
    {
        $lotManager->delete($lot);

        $this->flashSuccess('Le lot a bien été supprimé');

        return $this->redirectToRoute('app_admin_lot_list');
    }

    /**
     * @Route("/multiDelete", name="app_admin_lot_multidelete", methods={"POST"})
     */
    public function multiDelete(Request $request, LotManager $lotManager): Response
    {
        if ($request->isXmlHttpRequest())
        {
            $idLots = $request->get('lots');
            $lots = [];

            foreach ($idLots as $idLot)
            {
                if ($idLot && $lot = $lotManager->getLot($idLot))
                {
                    $lots[] = $lot;
                }
            }

            foreach ($lots as $lot)
            {
                $lotManager->delete($lot);
            }

            $this->flashSuccess('Les lots ont bien été supprimés');
        }

        return new JsonResponse(null);
    }

    /**
     * @Route("/regenerateQrcodes", name="regenerateqrcode", methods={"GET"})
     */
    public function regenerateQrcodes(Request $request, LotManager $lotManager, Pdf $knpSnappyPdf): Response
    {
        $idLots = explode(',', $request->get('lots'));
        $lots = [];
        $qrCodes = [];

        foreach ($idLots as $idLot)
        {
            if ($lot = $lotManager->getLot((int)$idLot))
            {
                $lots[] = $lot;
            }
        }

        foreach ($lots as $lot)
        {
            $qrCodes[] = [
                'path'  => $lot->getQrCode(),
                'id'    => $lot->getId()
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
    public function generateQrCodesAction(Request $request, LotManager $lotManager, Pdf $knpSnappyPdf): Response
    {
        $form = $this->createForm(GenerateLotQrCodeType::class, [], [
            'action' => $this->generateUrl('app_admin_lot_generateqrcode')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $nbQrCode = (int)$form->get('nbQrCode')->getData();
            $nursery = $form->get('nursery')->getData();
            $entryDate = $form->get('entryDate')->getData();

            $qrCodes = [];

            for ($i = 0; $i < $nbQrCode; $i++)
            {
                $lot = new Lot();
                $lot->setNursery($nursery);
                $lot->setEntryDate($entryDate);

                $lotManager->save($lot);

                $qrCodes[] = [
                    'path'  => $lot->getQrCode(),
                    'id'    => $lot->getId()
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

        return $this->render('admin/lot/modalGenerateQrCode.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/datatable", name="datatable", methods={"POST"})
     */
    public function datatable(Request $request, LotDT $lotDT)
    {
        $start = $request->get('start');
        $length = $request->get('length');
        $draw = $request->get('draw');
        $columns = $request->get('columns');
        $search = $request->get('search')['value'];
        $order = $request->get('order')[0] ?? null;

        $allLots = $lotDT->all($columns, $order, $search);

        $lots = array_slice($allLots, $start, $length);
        $countLot = count($allLots);

        $arrayLot = [];

        /** @var Lot $lot */
        foreach ($lots as $lot)
        {
            $arrayLot[] = [
                'active'        => false,
                'id'            => $lot->getId(),
                'name'          => $lot->getName(),
                'place'         => $lot->getPlace(),
                'postalCode'    => $lot->getPostalCode(),
                'city'          => $lot->getCity(),
                'actions'       => $this->renderView('admin/lot/_action_buttons.html.twig', ['lot' => $lot])
            ];
        }

        return $this->json([
            "draw"              => $draw,
            "recordsTotal"      => $countLot,
            "recordsFiltered"   => $countLot,
            "data"              => $arrayLot
        ]);
    }

    /**
     * @Route("/uploadPicture/{id}/{lotPicture}", defaults={ "lotPicture" = null }, name="uploadpicture", methods={"POST"})
     */
    public function uploadPicture(Request $request, Lot $lot, ?LotPicture $lotPicture, LotPictureManager $lotPictureManager): Response
    {
        $file = $request->get('picture');
        $file = str_replace('data:image/png;base64,', '', $file);
        $file = str_replace(' ', '+', $file);
        $data = base64_decode($file);

        $original = $request->get('original');
        $original = str_replace('data:image/png;base64,', '', $original);
        $original = str_replace(' ', '+', $original);
        $originalData = base64_decode($original);

        $folder = $lot->getId();

        $filename = 'upload/lot/' . $folder . '/' .  uniqid(mt_rand(), true). '.png';
        $filenameOriginal = 'upload/lot/' . $folder . '/' .  uniqid(mt_rand(), true). '.png';

        if (!$lotPicture)
        {
            $lotPicture = new LotPicture();
            $lotPicture->setLot($lot);
            $lotPicture->setPathOriginal($filenameOriginal);
            $lotPicture->setPath($filename);

            $lot->addLotPicture($lotPicture);

            $lotPictureManager->save($lotPicture);
        }

        $path = $this->getParameter('kernel.project_dir') . '/public/';

        if ($folder)
        {
            if (!file_exists('upload/lot/' . $folder) &&
                !mkdir('upload/lot/' . $folder, 0777, true) &&
                !is_dir('upload/lot/' . $folder))
            {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', 'path/to/directory'));
            }
        }

        $o = new \stdClass();

        if (($lastPicture = $lotPicture->getPath()) && file_exists($path . $lastPicture))
        {
            unlink($path . $lastPicture);
        }

        if (($lastPicture = $lotPicture->getPathOriginal()) && file_exists($path . $lastPicture))
        {
            unlink($path . $lastPicture);
        }

        file_put_contents($path . $filename, $data);
        file_put_contents($path . $filenameOriginal, $originalData);

        $o->name = $filename;
        $o->content = '<img src="/'. $filename .'"/>';

        $o->id = $lotPicture->getId();
        $lotPicture->setPath($filename);
        $lotPictureManager->save($lotPicture);

        return $this->json($o);
    }

    /**
     * @Route("/removePicture/{id}", name="removepicture")
     */
    public function removePicture(Request $request, LotPicture $lotPicture, LotPictureManager $lotPictureManager): Response
    {
        if ($request->isXmlHttpRequest())
        {
            $lotPictureManager->delete($lotPicture);
        }

        return $this->json(true);
    }

}
