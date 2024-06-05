<?php

namespace App\Controller\Front\Group;

use App\Controller\Api\Group\CreateGroupRoute;
use App\Form\CreateGroupFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

class CreateGroupController extends AbstractController
{
    public function __construct(
        private readonly CreateGroupRoute $createGroupRoute,
        private readonly RouterInterface $router
    )
    {
    }

    #[Route('/create/group', name: 'front.group.create', methods: ['GET', 'POST'])]
    public function index(Request $request, Security $security): Response
    {
        $form = $this->createForm(CreateGroupFormType::class);
        $form->handleRequest($request);
        $created = false;

        if ($request->getMethod() === Request::METHOD_POST) {
            $request->request->set('name', $form->getData()?->getName());
            $this->createGroupRoute->__invoke($request, $security);
            $created = true;
        }

        return $this->render('front/group/create_group.html.twig', [
            'controller_name' => 'CreateGroupController',
            'createGroupForm' => $form,
            'created' => $created,
            'createdGroupName' => $form->getData()?->getName(),
        ]);
    }
}
