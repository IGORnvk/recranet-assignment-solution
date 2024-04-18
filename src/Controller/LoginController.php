<?php

namespace App\Controller;

use App\Form\LogInFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // if already authenticated, redirect home
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_home');
        }

        // create form for logging in
        $form = $this->createForm(LogInFormType::class);
        $form->handleRequest($request);

        return $this->render('auth/login.html.twig', [
            'form' => $form,
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(Security $security): Response
    {
        // if not authenticated, redirect to login page
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_login');
        }
        
        $security->logout(false);

        return $this->redirectToRoute('app_login');
    }
}
