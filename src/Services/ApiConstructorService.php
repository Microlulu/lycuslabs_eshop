<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiConstructorService{

    /**
     * @return Serializer
     */
    private function getSerializer(): Serializer {
        // On spécifie qu'on utilise l'encodeur JSON
        $encoders = [new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection en tableau
        $normalizers = [new ObjectNormalizer()];

        // On instancie le convertisseur
        return new Serializer($normalizers, $encoders);
    }

    /**
     * @param mixed $data
     * @return String
     */
    public function getRawJson(mixed $data): String {
        // On récupère le serializer déjà configurer par défaut
        $serializer = $this->getSerializer();
        // On convertit en json
        return $serializer->serialize($data, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
    }

    /**
     * @param String $json
     * @param array|null $customHeader
     * @return Response
     */
    public function initReponse(String $json = '', array $customHeader = null) {
        // if you have data, set the response with data or init by default by empty string
        $response = new Response($json);

        if (!empty($customHeader)) {
            // Set multiple headers simultaneously
            $response->headers->add($customHeader);
        }
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param mixed $data
     * @param array|null $customHeader
     * @return Response
     */
    // getResponseForApi prends 2 reponses dans la fonction
    // mixed prends toutes les datas (int, string, float, bool...)
    // $customHeader = null si il n'a rien c'est null
    public function getResponseForApi(mixed $data, array $customHeader = null): Response {
        return $this->initReponse($this->getRawJson($data), $customHeader);
        
    }

    public function getJsonBodyFromRequest() {
        $request = new Request();
        return json_decode($request->getContent(), true);
    }
}