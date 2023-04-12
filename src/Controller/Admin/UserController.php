<?php

namespace App\Controller\Admin;

use App\Controller\GeneralController;
use App\Entity\Nursery;
use App\Entity\Tree;
use App\Entity\User;
use App\Form\Admin\UserType;
use App\Manager\UserManager;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/users", name="app_admin_user_")
 */
class UserController extends GeneralController
{
    /**
     * @Route("/list", name="list")
     * @IsGranted("ROLE_MANAGER")
     */
    public function listAction(Request $request, UserManager $userManager): Response
    {
        $active = $request->get('active') ?? true;

        return $this->render('admin/user/list.html.twig', [
            'users' => $userManager->getUsers([ 'active' => $active ]),
            'active' => $active
        ]);
    }

    /**
     * @Route("/add", name="add")
     * @IsGranted("ROLE_MANAGER")
     */
    public function addAction(Request $request, UserManager $userManager): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $userManager->save($user);
            $this->flashSuccess('L\'utilisateur a bien été ajouté');

            return $this->redirectToRoute('app_admin_user_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @IsGranted("ROLE_MANAGER")
     */
    public function editAction(Request $request, User $user, UserManager $userManager): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $userManager->save($user);
            $this->flashSuccess('L\'utilisateur a bien été modifié');

            return $this->redirectToRoute('app_admin_user_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @IsGranted("ROLE_MANAGER")
     */
    public function delete(User $user, UserManager $userManager)
    {
        $userManager->delete($user);

        $this->flashSuccess('L\'utilisateur a bien été supprimé');

        return $this->redirectToRoute('app_admin_user_list');
    }

    /**
     * @Route("/my-account", name="my_account")
     */
    public function myAccountAction(Request $request, UserManager $userManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user, ['full' => false]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $userManager->save($user);
            $this->flashSuccess('Votre compte a bien été modifié');
        }

        return $this->render('admin/user/myAccount.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/change-main-nursery/{id}", name="changemainnusery")
     */
    public function changeMainNursery(Request $request, Nursery $nursery, UserManager $userManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $userManager->changeMainNursery($user, $nursery);

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}
