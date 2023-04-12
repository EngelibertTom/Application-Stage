<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\Species;
use App\Entity\User;
use App\Form\Admin\SpeciesType;
use App\Manager\SpeciesManager;
use App\Repository\SpeciesRepository;
use App\Service\Datatable\SpeciesDT;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/species", name="app_admin_species_")
 */
class SpeciesController extends GeneralController
{
    /**
     * @Route("/list", name="list")
     */
    public function listAction(SpeciesRepository $speciesRepository): Response
    {
        return $this->render('admin/data/species/list.html.twig', [
            'nbSpecies' => $speciesRepository->count([]),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function addAction(Request $request, SpeciesManager $speciesManager): Response
    {
        $species = new Species();

        $form = $this->createForm(SpeciesType::class, $species);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($this->isGranted('ROLE_SPECIES_MANAGER')) {
                $species->setValidate(true);
            }

            $speciesManager->save($species);
            $this->flashSuccess('L\'espèce a bien été ajoutée');

            return $this->redirectToRoute('app_admin_species_list');
        }

        return $this->render('admin/data/species/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function editAction(Request $request, Species $species, SpeciesManager $speciesManager): Response
    {
        $form = $this->createForm(SpeciesType::class, $species);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $speciesManager->save($species);
            $this->flashSuccess('L\'espèce a bien été modifié');

            return $this->redirectToRoute('app_admin_species_list');
        }

        return $this->render('admin/data/species/edit.html.twig', [
            'form' => $form->createView(),
            'species' => $species
        ]);
    }

    /**
     * @Route("/datatable", name="datatable", methods={"POST"})
     */
    public function datatable(Request $request, SpeciesDT $speciesDT)
    {
        $start = $request->get('start');
        $length = $request->get('length');
        $draw = $request->get('draw');
        $columns = $request->get('columns');
        $search = $request->get('search')['value'];
        $order = $request->get('order')[0] ?? null;

        $allSpecies = $speciesDT->all($columns, $order, $search);

        $species = array_slice($allSpecies, $start, $length);
        $countSpecies = count($allSpecies);

        $arraySpecies = [];

        /** @var Species $specie */
        foreach ($species as $specie)
        {
            $arraySpecies[] = [
                'name'          => $specie->getName(),
                'latinName'     => $specie->getLatinName(),
                'leafType'      => (string) $specie->getLeafType(),
                'statusUicn'    => (string) $specie->getStatusUicn(),
                'validate'      => $specie->getValidate() ? '<div class="mb-2 mr-2 p-2 badge badge-success"> Validé </div>' : '<div class="mb-2 mr-2 p-2 badge badge-warning"> En attente </div>',
                'actions'       => $this->renderView('admin/data/species/_action_buttons.html.twig', ['species' => $specie])
            ];
        }

        return $this->json([
            "draw"              => $draw,
            "recordsTotal"      => $countSpecies,
            "recordsFiltered"   => $countSpecies,
            "data"              => $arraySpecies
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Species $species, SpeciesManager $speciesManager): Response
    {
        $speciesManager->delete($species);

        $this->flashSuccess('L\'espèce a bien été supprimée');

        return $this->redirectToRoute('app_admin_species_list');
    }

    /**
     * @Route("/validate/{id}", name="validate")
     * @IsGranted("ROLE_SPECIES_MANAGER")
     */
    public function validate(Species $species, SpeciesManager $speciesManager): Response
    {
        $species->setValidate(true);
        $speciesManager->save($species);

        $this->flashSuccess('L\'espèce a bien été validée');

        return $this->redirectToRoute('app_admin_species_edit', [ 'id' => $species->getId() ]);
    }
}
