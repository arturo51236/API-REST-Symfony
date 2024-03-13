<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Usuario;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?Usuario $usuario, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager): JsonResponse {
        // $email = $this->getUser()->getUserIdentifier();
        if (null === $usuario) {
            return $this->json([
            'message' => 'missing credentials',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = $jwtManager->decode($this->tokenStorageInterface->getToken());

        return $this->json([
            'user'  => $usuario->getUserIdentifier(),
            'token' => $token,
        ]);
    }
}
