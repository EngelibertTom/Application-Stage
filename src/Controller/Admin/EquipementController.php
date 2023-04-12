<?php

namespace App\Controller\Admin;

use App\Entity\Entretien;
use App\Entity\Equipement;
use App\Entity\Maintenance;
use App\Entity\PretEquipement;
use App\Form\Admin\EntretienType;
use App\Form\Admin\EquipementType;
use App\Form\Admin\PretType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/equipement", name="app_admin_")
 */

class EquipementController extends AbstractController
{
    private $doctrine;

    /**
     * @Route("/equipement", name="equipement")
     */
    public function index(): Response
    {
        return $this->render('admin/equipement/listeequipement.html.twig', [
            'controller_name' => 'EquipementController',
        ]);
    }

    /**
     * @Route("/equipement2", name="equipement2")
     */

    public function listeequipement2(): Response
    {
        return $this->render('admin/equipement/listeequipement2.html.twig',[
            'controller_name' =>'EquipementController',
        ]);

    }

    /**
     * @Route("/pretequipement", name="pretequipement")
     */

    public function pretequipement(): Response
    {
        return $this->render('admin/equipement/pretequipement.html.twig', [
            'controller_name' => 'EquipementController',
        ]);
    }
    /**
     * @Route("/ajoutequipement", name="ajoutequipement")
     */

    public function ajoutform(Request  $request) {
        $equipement = new Equipement();

        $form = $this->createForm(EquipementType::class, $equipement);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($equipement);
            $em->flush();

        }



        return $this->render('admin/equipement/ajoutequipement.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function pageajout(): Response
    {
        return $this->render('admin/equipement/ajoutequipement.html.twig', [
            'controller_name' => 'EquipementController',

        ]);




    }

    /**
     * @Route("/equipement2", name="equipement2")
     */


    public function affichagedonnees()
    {
        $donnees = $this->getDoctrine()->getRepository(Equipement::class)->findAll();

        return $this->render('admin/equipement/listeequipement2.html.twig', [
            'controller_name' => 'ArticlesController',
            'donnees' => $donnees,
            ]);
    }


    /**
     * @Route("/ajoutpretequipement", name="ajoutpretequipement")
     */


    public function pagepret(Request $request):Response

    {
        $pret = new PretEquipement();

        $form = $this->createForm(PretType::class, $pret);


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pret);
            $em->flush();

        }


        return $this->render('admin/equipement/ajoutepretequipement.html.twig', [
            'controller_name' => 'EquipementController',
            'form' => $form->createView()

        ]);
    }



    /**
     * @Route("/pretequipement", name="pretequipement")
     */


    public function affichagedonnees2()
    {
        $donnees = $this->getDoctrine()->getRepository(PretEquipement::class)->findAll();


        return $this->render('admin/equipement/pretequipement.html.twig', [
            'donnees' => $donnees,

        ]);
    }


    /**
     * @Route("/edit2/{id}", name="edit2")
     */
    public function editAction(Request $request, Equipement $equipement): Response
    {
        $form = $this->createForm(EquipementType::class, $equipement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($equipement);
            $em->flush();


            return $this->redirectToRoute('app_admin_equipement2');
        }

        return $this->render('admin/equipement/edit.html.twig', [
            'form' => $form->createView(),
            'equipement' => $equipement
        ]);
    }

    /**
     * @Route("/editpret/{id}", name="editpret")
     */

    public function editPret(Request $request, PretEquipement $pret):Response

    {


        $form = $this->createForm(PretType::class, $pret);


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pret);
            $em->flush();


            return $this->redirectToRoute('app_admin_pretequipement');
        }


        return $this->render('admin/equipement/editpret.html.twig', [
            'form' => $form->createView(),
            'pret' => $pret

        ]);
    }

    /**
     * @Route("/deleteequip/{id}", name="deleteequip")
     */
    public function deleteAction(Equipement $equipement): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($equipement);
        $em->flush();



        return $this->redirectToRoute('app_admin_equipement2');
    }

    /**
     * @Route("/deletepret/{id}", name="deletepret")
     */

    public function deleteAction2(PretEquipement $pret): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($pret);
        $em->flush();



        return $this->redirectToRoute('app_admin_pretequipement');
    }

    /**
     * @Route("/deleteentretien/{id}", name="deleteentretien")
     */

    public function deleteAction3(Entretien $entretien): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entretien);
        $em->flush();



        return $this->redirectToRoute('app_admin_entretien');
    }

    /**
     * @Route("/entretien", name="entretien")
     */

    public function entretien(): Response
    {
        $donnees = $this->getDoctrine()->getRepository(Entretien::class)->findAll();

        return $this->render('admin/equipement/entretien.html.twig',[
            'controller_name' =>'EquipementController',
            'donnees'=>$donnees,

        ]);

    }

    /**
     * @Route("/ajoutentretien", name="ajoutentretien")
     */

    public function ajoutentretien(Request $request): Response
    {
        $entretien = new Entretien();

        $form = $this->createForm(EntretienType::class, $entretien);


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entretien);
            $em->flush();

        }
        return $this->render('admin/equipement/ajoutentretien.html.twig', [
            'controller_name' => 'EquipementController',
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/editentretien/{id}", name="editentretien")
     */

    public function editentretien(Request $request, Entretien $entretien): Response
    {


        $form = $this->createForm(EntretienType::class, $entretien);


        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entretien);
            $em->flush();

            return $this->redirectToRoute('app_admin_entretien');
        }



        return $this->render('admin/equipement/editentretien.html.twig', [
            'form' => $form->createView(),
            'entretien'=> $entretien,
        ]);


    }

    /**
     * @Route("/operations/{id}", name="operation")
     */

    public function operation(Equipement $equipement): Response

    {



        return $this->render('admin/equipement/operation.html.twig',[
            'controller_name' =>'EquipementController',
            'equipement' => $equipement,
            'entretiens' =>  $equipement->getEntretiens(),
            'maintenances' =>  $equipement->getMaintenances(),





        ]);

    }






}



