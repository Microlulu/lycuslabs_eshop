<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $user_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $reference;

    #[ORM\Column(type: 'datetime')]
    private $date_order;

    #[ORM\Column(type: 'text')]
    private $adresse;

    #[ORM\OneToMany(mappedBy: 'order_id', targetEntity: DetailOrder::class, orphanRemoval: true)]
    private $detailOrders;

    #[ORM\Column(type: 'boolean')]
    private $delivery;

    #[ORM\Column(type: 'float', nullable: true)]
    private $voucher;

    public function __construct()
    {
        $this->detailOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateOrder(): ?\DateTimeInterface
    {
        return $this->date_order;
    }

    public function setDateOrder(\DateTimeInterface $date_order): self
    {
        $this->date_order = $date_order;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection|DetailOrder[]
     */
    public function getDetailOrders(): Collection
    {
        return $this->detailOrders;
    }

    public function addDetailOrder(DetailOrder $detailOrder): self
    {
        if (!$this->detailOrders->contains($detailOrder)) {
            $this->detailOrders[] = $detailOrder;
            $detailOrder->setOrderId($this);
        }

        return $this;
    }

    public function removeDetailOrder(DetailOrder $detailOrder): self
    {
        if ($this->detailOrders->removeElement($detailOrder)) {
            // set the owning side to null (unless already changed)
            if ($detailOrder->getOrderId() === $this) {
                $detailOrder->setOrderId(null);
            }
        }

        return $this;
    }

    public function getDelivery(): ?bool
    {
        return $this->delivery;
    }

    public function setDelivery(bool $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getVoucher(): ?float
    {
        return $this->voucher;
    }

    public function setVoucher(?float $voucher): self
    {
        $this->voucher = $voucher;

        return $this;
    }
}
