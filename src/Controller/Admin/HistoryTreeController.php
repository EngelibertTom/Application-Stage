<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\HistoryTree;
use App\Entity\Tree;
use App\Entity\User;
use App\Form\Admin\HistoryTreeType;
use App\Manager\HistoryTreeManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/history-trees", name="app_admin_historytree_")
 */
class HistoryTreeController extends GeneralController
{
    /**
     * @Route("/add/{id}", name="add")
     * @IsGranted("ROLE_MANAGER")
     */
    public function addAction(Request $request, Tree $tree, HistoryTreeManager $historyTreeManager): Response
    {
        $historyTree = new HistoryTree();
        $historyTree->setTree($tree);

        /** @var User $user */
        $user = $this->getUser();
        $historyTree->setUser($user);

        $form = $this->createForm(HistoryTreeType::class, $historyTree, [
            'action' => $this->generateUrl('app_admin_historytree_add', [ 'id' => $tree->getId() ])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $historyTreeManager->save($historyTree);

            $this->flashSuccess('L\'historique a bien été ajouté');

            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }

        return $this->render('admin/tree/modalEditHistory.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, HistoryTree $historyTree, HistoryTreeManager $historyTreeManager): Response
    {
        $form = $this->createForm(HistoryTreeType::class, $historyTree, [
            'action' => $this->generateUrl('app_admin_historytree_edit', [ 'id' => $historyTree->getId() ])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $historyTreeManager->save($historyTree);

            $this->flashSuccess('L\'historique a bien été modifié');

            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }

        return $this->render('admin/tree/modalEditHistory.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(Request $request, HistoryTree $historyTree, HistoryTreeManager $historyTreeManager)
    {
        $historyTreeManager->delete($historyTree);

        $this->flashSuccess('L\'historique a bien été supprimé');

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}
