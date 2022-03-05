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
        // l'encodeur me formate un message
        $encoders = [new JsonEncoder()];

        // On instancie (repère) le "normaliseur" pour convertir la collection en tableau
        // le normaliseur permet de rendre le message clair
        $normalizers = [new ObjectNormalizer()];

        // On instancie (rèpère) le convertisseur
        // on créer un serializer: il permet de nous rendre notre réponse bien encoder, bien normaliser
        return new Serializer($normalizers, $encoders);
    }

    /**
     * @param mixed $data
     * @return String
     */
    public function getRawJson(mixed $data): String {
        // On récupère le serializer déjà configurer par défault
        $serializer = $this->getSerializer();
        // On convertit en json (brut)
        return $serializer->serialize($data, 'json', [
            // circular-reference-handler pour éviter d'avoir des boucles à l'infini lors de creation du json
            // (pour ne pas que ça crash)
            'circular_reference_handler' => function ($object) {
            // je ne fais pas de boucle, au lieu de faire des boucles je récupère l'id de l'object
                return $object->getId();
            }
        ]);
    }

    /**
     * @param String $json
     * @param array|null $customHeader
     * @return Response
     */
    // j'initialise la reponse que je dois renvoyer depuis le controller
    public function initReponse(String $json = '', array $customHeader = null) {
        // if you have data, set the response with data or init by default by empty string
        $response = new Response($json);
        // j'appelle une réponse
        if (!empty($customHeader)) {
            // si customHeader est different de vide (donc plein) on ajoute nos customs header a nos headers
            // Set multiple headers simultaneously
            $response->headers->add($customHeader);
            //les customs header et les headers sont des blocs d'infos de json (reponse)
        }
        $response->headers->set('Content-Type', 'application/json');
        //dans la réponse, vise le header et set un content type avec la valeur application json

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
        //return la initresponse : il attends un json et un customHeader, donc je lui dit va chercher le json dans la method getRawJson et prends le customHeader aussi en parametres
        
    }

    public function getJsonBodyFromRequest() {
        //je creer une nouvelle request
        $request = new Request();
        //je decode la request
        return json_decode($request->getContent(), true);
    }
}