<?php

namespace App\Services;

use Doctrine\ORM\EntityManager;

class VoucherService {
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager =$entityManager;

    }


    function VerifyVoucher(){
    }

    function ApplyVoucher(){

    }

    function CreateVoucher(){

    }
}