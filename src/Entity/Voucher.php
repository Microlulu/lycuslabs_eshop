<?php

namespace App\Entity;

use App\Repository\VoucherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoucherRepository::class)]
class Voucher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $discount;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'datetime')]
    private $date_start;

    #[ORM\Column(type: 'datetime')]
    private $date_end;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $couponcode;

    #[ORM\OneToMany(mappedBy: 'voucher', targetEntity: product::class)]
    private $product_id;

    #[ORM\OneToMany(mappedBy: 'voucher_id', targetEntity: UsedVoucher::class)]
    private $usedVouchers;

    public function __construct()
    {
        $this->product_id = new ArrayCollection();
        $this->usedVouchers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getCouponcode(): ?string
    {
        return $this->couponcode;
    }

    public function setCouponcode(?string $couponcode): self
    {
        $this->couponcode = $couponcode;

        return $this;
    }

    /**
     * @return Collection|product[]
     */
    public function getProductId(): Collection
    {
        return $this->product_id;
    }

    public function addProductId(product $productId): self
    {
        if (!$this->product_id->contains($productId)) {
            $this->product_id[] = $productId;
            $productId->setVoucher($this);
        }

        return $this;
    }

    public function removeProductId(product $productId): self
    {
        if ($this->product_id->removeElement($productId)) {
            // set the owning side to null (unless already changed)
            if ($productId->getVoucher() === $this) {
                $productId->setVoucher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UsedVoucher[]
     */
    public function getUsedVouchers(): Collection
    {
        return $this->usedVouchers;
    }

    public function addUsedVoucher(UsedVoucher $usedVoucher): self
    {
        if (!$this->usedVouchers->contains($usedVoucher)) {
            $this->usedVouchers[] = $usedVoucher;
            $usedVoucher->setVoucherId($this);
        }

        return $this;
    }

    public function removeUsedVoucher(UsedVoucher $usedVoucher): self
    {
        if ($this->usedVouchers->removeElement($usedVoucher)) {
            // set the owning side to null (unless already changed)
            if ($usedVoucher->getVoucherId() === $this) {
                $usedVoucher->setVoucherId(null);
            }
        }

        return $this;
    }
}
