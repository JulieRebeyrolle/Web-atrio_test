<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/person", name="person_")
 */
class PersonController extends AbstractController
{
    /**
     * @Route ("/", methods={"GET"}, name="index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PersonController.php',
        ]);
    }

    /**
     * @Route ("/", methods={"POST"}, name="new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $person = $this->get('serializer')->deserialize($request->getContent(), Person::class, 'json');

        if ($person->getAge() > 150) {
            return $this->json(['success' => false, 'error' => 'As a human, you should be under 150 years old!'], 400);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($person);

        $entityManager->flush();

        return $this->json(["success" => true, "data" => $person], 201);
    }


}
