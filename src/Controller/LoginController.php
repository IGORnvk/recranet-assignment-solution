<?php

namespace App\Controller;

use App\Form\LogInFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request): Response
    {
        // create form for logging in
        $form = $this->createForm(LogInFormType::class);
        $form->handleRequest($request);

        return $this->render('auth/login.html.twig', [
          'form' => $form,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
