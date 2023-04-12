<?php

namespace App\Controller;

use App\Entity\Owner;
use App\Entity\User;
use App\Form\OwnerType;
use App\Manager\OwnerManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/", name="app_")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if (($user = $this->getUser()) && !$user instanceof User) {
            return $this->redirectToRoute('app_user_home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/inscription", name="register")
     */
    public function register(OwnerManager $ownerManager, Request $request): Response
    {
        $owner= new Owner;

        $form= $this->createForm(OwnerType::class, $owner);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $owner->setRoles(['ROLE_USER']);

            $ownerManager->save($owner);

            return $this->redirectToRoute('app_login');
            
        }
        
        return $this->render('security/register.html.twig', [
            'formowner' => $form->createView()
        ]);
    }

}
