<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/admin")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_admin_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_admin_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/access_denied", name="app_admin_accessdenied")
     */
    public function accessDeniedAction(Request $request): Response
    {
        $referer = $request->headers->get('referer');
        $message = $request->get('message');

        return $this->render('admin/security/accessDenied.html.twig', [
            'back' => $referer,
            'message' => $message
        ]);
    }

    /**
     * @Route("/not_found", name="app_admin_notfound")
     */
    public function notFoundAction(Request $request): Response
    {
        return $this->render('admin/security/notFound.html.twig');
    }
}
