<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Service\UserService;

#[Route('/user')]
class UserController extends AbstractController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    
    #[Route('/', name: 'user_list', methods: ['GET'])]
    public function index(): Response
    {
        $users = $this->userService->getAllUsers();
        return $this->json($users);
    }

    /**
     * @Route("/users/{id}", name="user_show", methods={"GET"})
     */
    public function show($id): Response
    {
        $user = $this->userService->getUserById($id);
        return $this->json($user);
    }

    #[Route('/create', name: 'user_create', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $name = $request->request->get('name');
        $age = $request->request->get('age');
        $user = $this->userService->createUser($name, $age);
        return $this->json($user);
    }

    /**
     * @Route("/users/{id}", name="user_edit", methods={"PUT"})
     */
    public function edit(Request $request, $id): Response
    {
        $user = $this->userService->getUserById($id);
        $name = $request->request->get('name');
        $age = $request->request->get('age');
        $user = $this->userService->updateUser($user, $name, $age);
        return $this->json($user);
    }

    /**
     * @Route("/users/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id): Response
    {
        $user = $this->userService->getUserById($id);
        $this->userService->deleteUser($user);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    
}
