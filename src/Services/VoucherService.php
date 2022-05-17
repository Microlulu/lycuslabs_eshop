<?php

namespace App\Services;

use App\Entity\UsedVoucher;
use App\Entity\User;
use App\Entity\Voucher;
use App\Repository\UsedVoucherRepository;
use App\Repository\VoucherRepository;
use Doctrine\ORM\EntityManagerInterface;

class VoucherService {

    private EntityManagerInterface $entityManager;
    private VoucherRepository $voucherRepository;
    private UsedVoucherRepository $usedVoucherRepository;

    // Je fais une construct pour initialiser/parameter mes datas avant de les utiliser.
    // J'appelle mon Entity Manager, le Répository des Vouchers et le Repository des Voucher utilisés.
    public function __construct(EntityManagerInterface $entityManager, VoucherRepository $voucherRepository, UsedVoucherRepository $usedVoucherRepository) {
        $this->entityManager = $entityManager;
        $this->voucherRepository = $voucherRepository;
        $this->usedVoucherRepository = $usedVoucherRepository;
    }

    //Je crée une fonction pour vérifier si mon voucher peut être utilisé.
    //Cette fonction prend comme paramètres : le code réduction que l'utilisateur va rentrer et l'utilisateur.
    //Je précise dans la fonction que je veux un booléen : donc une réponse vraie ou fausse.
    function VerifyVoucher(Voucher $voucher, User $user): bool {
        // pour vérifier mon voucher, je crée une nouvelle datetime dans laquelle je viens stocker la date d'aujourd'hui dans la variable $dateNow.
        $dateNow = new \DateTimeImmutable();
        // Si le voucher est null return false (c'est-à-dire si la base de données et vide).
        // Pour la sécurité, je rajoute également la variable $user. Je lui précise que si l'utilisateur est également null (pas connecté) il faut retourner faux.
        // DONC : voucher ou user null? = false
        if ($voucher == null || $user == null){
            return false;
        }else{
            // Je viens ensuite vérifier la date du voucher :
            // début du if : Si le voucher a une date de début inférieur ou égal à la date de maintenant et si il a une date de fin supérieur à la date de maintenant. (true)
            if ($voucher->getDateStart() <= $dateNow && $voucher->getDateEnd() > $dateNow){
                // Avant de dire vrai, on vérifie que le code n'a pas déjà été utilisé par l'utiisateur, on le cherche le voucher par son id et on vérifie que l'id de l'utilisateur n'est pas associé.
                $usedVoucher = $this->usedVoucherRepository->findOneBy(['voucher_id' => $voucher->getId(), 'user_id' => $user->getId()]);
                //si le code n'est pas trouvé dans la table usedVoucher (donc null), on renvoi true. (ok)
                if ($usedVoucher == null){
                    return true;
                }else{
                    //Sinon : s'il y a deja un code, c'est-à-dire que l'user a déjà utilisé le code, donc on renvoie false. (non)
                    return false;
                }
            } else {
                // Dans ce else on dit que si le voucher a une date de début qui n'est pas inférieur ou égale a la date de maintenant et s'il a une date de fin qui est inférieur à la date d'aujourd'hui
                // Le code n'est pas encore activé ou est expiré.
                // Et dans ce cas on retourne faux. (non)
                return false;
            }
        }
    }

// La fonction ApplyVoucher prends comme paramètre le code de réduction et l'utilisateur.
    function ApplyVoucher(Voucher $voucher, User $user ){
        //Je viens créer la date d'aujourd'hui avec une nouvelle Datetime.
        $dateNow = new \DateTimeImmutable();
        //Je viens ensuite créer un nouvel objet : UsedVoucher (voucher utilisé)
        $usedVoucher = new UsedVoucher();
        //Je lui dit set la date d'aujourd'hui a mon usedVoucher
        $usedVoucher->setUsedate($dateNow);
        //Set l'id de l'user également (comme ça on sait quel utilisateur s'est servit du code de réduction)
        $usedVoucher->setUserId($user);
        //Et une fois que ce code valide à été rentré, set le en tant que usedVoucher (voucher utilisé)
        $usedVoucher->setVoucherId($voucher);
        //Enregistre le nouveau voucher utilisé dans la base de donnée.
        $this->entityManager->persist($usedVoucher);
        //Et valide les informations.
        $this->entityManager->flush();
    }

    // Function pour créer un voucher.
    function CreateVoucher(){

    }
}