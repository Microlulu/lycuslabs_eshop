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

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'usedVouchers')]
    #[ORM\JoinColumn(nullable: false)]
    private $user_id;

    #[ORM\ManyToOne(targetEntity: Voucher::class, inversedBy: 'usedVouchers')]
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

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getVoucherId(): ?Voucher
    {
        return $this->voucher_id;
    }

    public function setVoucherId(?Voucher $voucher_id): self
    {
        $this->voucher_id = $voucher_id;

        return $this;
    }
}
