<?php

namespace App\Controller;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/api/usuario')]
class UsuarioController extends AbstractController
{
    #[Route('/show_all', name: 'show_all')]
    public function show_all(UsuarioRepository $usuarioRepository): Response {
        $usuarios = $usuarioRepository->findAll();

        $defaultContext = [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['tareas'],
        ];

        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $encoder = new JsonEncoder();
        $serializer = new Serializer([$normalizer], [$encoder]);

        $respuesta = $serializer->serialize($usuarios, 'json');

        return new JsonResponse($respuesta, 200, [], true);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id, UsuarioRepository $usuarioRepository, EntityManagerInterface $entityManager): Response {
        $usuario = $usuarioRepository->findOneById($id);

        $entityManager->remove($usuario);
        $entityManager->flush();

        $data = [
            'resultado' => 'ok',
        ];

        $normalizer = new ObjectNormalizer();
        $encoder = new JsonEncoder();
        $serializer = new Serializer([$normalizer], [$encoder]);

        $respuesta = $serializer->serialize($data, 'json');
        return new JsonResponse($respuesta,200, [], true);
    }
}
