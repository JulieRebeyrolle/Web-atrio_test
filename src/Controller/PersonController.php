<?php

namespace App\Controller;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/person", name="person_")
 */
class PersonController extends AbstractController
{
    /**
     * @Route ("/", methods={"GET"}, name="index")
     * @return Response
     */
    public function index(PersonRepository $personRepository): Response
    {
        return $this->json([
            'success' => true,
            'data' => $personRepository->findBy([], ['lastname' => 'ASC']),
        ]);
    }

    /**
     * @Route ("/", methods={"POST"}, name="new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, ValidatorInterface $validator): Response
    {
        try {
            $person = $this->get('serializer')->deserialize($request->getContent(), Person::class, 'json');

            $errors = $validator->validate($person);

            if (count($errors) > 0) {
                return $this->json(['success' => false, 'status' => '400', 'errors' => $errors], 400);
            }

            if ($person->getAge() < 0 | $person->getAge() > 150) {
                return $this->json([
                    'success' => false,
                    'error' => 'Age should be between 0 and 150'
                ], 400);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);

            $entityManager->flush();

            return $this->json([
                "success" => true,
                "data" => $person
            ], 201);
        } catch (NotEncodableValueException | NotNormalizableValueException $e) {
            return $this->json(['success' => false, 'status' => '400', 'error' => $e->getMessage()],400);
        }
    }


}
