<?php

namespace App\Controller;

use App\Entity\Tarea;
use App\Repository\TareaRepository;
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

#[Route('/api/tarea')]
class TareaController extends AbstractController
{
    #[Route('/show_main/{email}', name: 'show_main')]
    public function show_main(): Response {
        return $this->render('tarea/main.html.twig', [
            'controller_name' => 'TareaController',
        ]);
    }

    #[Route('/show_all', name: 'show_all')]
    public function show_all(TareaRepository $tareaRepository): JsonResponse {
        $tareas = $tareaRepository->findAll();

        $defaultContext = [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['usuario'],
        ];

        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $encoder = new JsonEncoder();
        $serializer = new Serializer([$normalizer], [$encoder]);

        $respuesta = $serializer->serialize($tareas, 'json');

        return new JsonResponse($respuesta, 200, [], true);
    }

    #[Route('/show_by_usuario/{email}', name: 'show_by_usuario')]
    public function show_by_usuario($email, UsuarioRepository $usuarioRepository): JsonResponse {
        $usuario = $usuarioRepository->findUsuarioByEmail($email);
        $tareas = $usuario->getTareas();

        $defaultContext = [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['usuario'],
        ];

        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $encoder = new JsonEncoder();
        $serializer = new Serializer([$normalizer], [$encoder]);

        $respuesta = $serializer->serialize($tareas, 'json');

        return new JsonResponse($respuesta, 200, [], true);
    }

    #[Route('/show_by_id/{id}', name: 'show_by_id')]
    public function show_by_id($id, TareaRepository $tareaRepository): JsonResponse {
        $tareas = $tareaRepository->findOneById($id);

        $defaultContext = [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['usuario'],
        ];

        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $encoder = new JsonEncoder();
        $serializer = new Serializer([$normalizer], [$encoder]);

        $respuesta = $serializer->serialize($tareas, 'json');

        return new JsonResponse($respuesta, 200, [], true);
    }

    #[Route('/add', name: 'add')]
    public function add(UsuarioRepository $usuarioRepository, EntityManagerInterface $entityManager): JsonResponse {
        $nombreTarea = htmlspecialchars($_POST['nombreTarea']);
        $email = htmlspecialchars($_POST['email']);
        $usuario = $usuarioRepository->findUsuarioByEmail($email);
        $tarea = new Tarea();
        $tarea->setNombre($nombreTarea);
        $tarea->setUsuario($usuario);
        $tarea->setFecha(new \DateTime());

        $entityManager->persist($tarea);
        $entityManager->flush();

        $defaultContext = [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['usuario'],
        ];

        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $encoder = new JsonEncoder();
        $serializer = new Serializer([$normalizer], [$encoder]);

        $respuesta = $serializer->serialize($tarea, 'json');

        return new JsonResponse($respuesta,200, [], true);
    }

    #[Route('/delete', name: 'delete')]
    public function delete(TareaRepository $tareaRepository, EntityManagerInterface $entityManager): JsonResponse {
        $idTarea = htmlspecialchars($_GET['id']);
        $tarea = $tareaRepository->findOneById($idTarea);

        $entityManager->remove($tarea);
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
