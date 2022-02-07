<?php

namespace App\Entity;

use App\Repository\UsedVoucherRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsedVoucherRepository::class)]
class UsedVoucher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $usedate;

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy: 'usedVouchers')]
    #[ORM\JoinColumn(nullable: false)]
    private $user_id;

    #[ORM\ManyToOne(targetEntity: voucher::class, inversedBy: 'usedVouchers')]
    #[ORM\JoinColumn(nullable: false)]
    private $voucher_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsedate(): ?\DateTimeInterface
    {
        return $this->usedate;
    }

    public function setUsedate(\DateTimeInterface $usedate): self
    {
        $this->usedate = $usedate;

        return $this;
    }

    public function getUserId(): ?user
    {
        return $this->user_id;
    }

    public function setUserId(?user $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getVoucherId(): ?voucher
    {
        return $this->voucher_id;
    }

    public function setVoucherId(?voucher $voucher_id): self
    {
        $this->voucher_id = $voucher_id;

        return $this;
    }
}
