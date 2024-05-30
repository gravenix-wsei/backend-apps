<?php

namespace App\Controller\Front\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LogoutControllerPhpController extends AbstractController
{
    #[Route('/user/logout', name: 'front.user.logout')]
    public function index(Security $security): Response
    {
        unset($_COOKIE['authorizationBearer']);
        setcookie('authorizationBearer', '', -1, '/');
        $security->logout(false);

        return $this->redirectToRoute('front.home');
    }
}
