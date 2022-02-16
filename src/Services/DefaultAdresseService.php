<?php

namespace App\Services;

use App\Entity\Adresse;
use App\Entity\User;
use App\Repository\AdresseRepository;

class DefaultAdresseService {
//je crée une classe dans le but de pouvoir m'en servir partout ou j'ai besoin
// je crée une globale
    private AdresseRepository $adresseRepository;

    // Je fais une construct pour initialiser/parameter mes adresses avant de les utiliser.
    public function __construct(AdresseRepository $adresseRepository) {
        $this->adresseRepository = $adresseRepository;
    }

    // cette fonction vérifie si l'utilisateur a une adresse enregistrée
    public function isDefaultAdresse(User $user): bool {
        // si c'est different de vide (donc plein) prends les adresses de l'utilisateur et pour chaque adresses cherche l'adresse qui est par defaut true
        if (!empty($user->getAdresses())) {
            foreach ($user->getAdresses() as $adresse) {
                if ($adresse->getDelivery() == true) {
                    // et return true
                    return true;
                }
            }
        }//sinon vide : return false
        return false;
    }

    //le repository sert à chercher des données dans la BDD
    //l'entité sert à créer une ligne/donnée dans la BDD
    //je rappelle l'entité user, car l'adresse concerne l'user

    public function selectByDefault(Adresse $adresse, User $user){
        // j'appelle ma methode pour verifier si j'ai une adresse par default
        if($this->isDefaultAdresse($user)) {
            // s'il a deja une adresse par défaut la nouvelle adresse sera false
            $adresse->setDelivery(false);
        } else {
            // si il n'a pas d'adresse par default : mets la a true
            $adresse->setDelivery(true);
        }
    }
}