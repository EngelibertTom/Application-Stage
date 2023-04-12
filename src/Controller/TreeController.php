<?php

namespace App\Controller;

use App\Entity\Adoption;
use App\Entity\Owner;
use App\Entity\Tree;
use App\Manager\AdoptionManager;
use App\Manager\TreeManager;
use App\Manager\TreeStatusManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/arbres", name="app_")
 */
class TreeController extends GeneralController
{
    /**
     * @Route("", name="tree_list")
     */
    public function listAction(Request $request, TreeManager $treeManager, PaginatorInterface $paginator): Response
    {
        $trees = $treeManager->getAdoptableTrees();

        $paginatorTrees = $paginator->paginate(
            $trees,
            $request->query->getInt('page', 1),
            16 // Nombre de résultats par page
        );

        return $this->render('tree/list.html.twig', [
            'trees' => $paginatorTrees,
            'numberTrees' => count($trees)
        ]);
    }

    /**
     * @Route("/detail/{id}", name="tree_detail")
     */
    public function detailAction(Request $request, Tree $tree): Response
    {
        $show = $request->get('show');

        if (!$show && $this->getUser()) {
            return $this->redirectToRoute('app_admin_tree_edit', [
                'id' => $tree->getId()
            ]);
        }

        return $this->render('tree/detail.html.twig', [
            'tree' => $tree
        ]);
    }

    /**
     * @Route("/{id}/adoption", name="tree_adoption")
     */
    public function adopt(Tree $tree, AdoptionManager $adoptionManager): Response
    {
        if (!$this->getUser())
        {
            return $this->redirectToRoute('app_login');
        }

        /** @var Owner $owner */
        $owner = $this->getUser();

        $adoption = new Adoption();
        $adoption->setTree($tree);
        $adoption->setOwner($owner);
        $tree->setStatus(TreeStatusManager::ADOPT);

        $adoptionManager->save($adoption);

        return $this->redirectToRoute('app_tree_list');
    }
}
