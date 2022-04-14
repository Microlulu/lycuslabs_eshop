<?php

namespace App\Entity;

use App\Repository\ImagesServicesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesServicesRepository::class)]
class ImagesServices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    #[ORM\Column(type: 'string', length: 255)]
    private $alt;

    #[ORM\ManyToOne(targetEntity: Services::class, inversedBy: 'imagesServices')]
    #[ORM\JoinColumn(nullable: false)]
    private $service_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getServiceId(): ?Services
    {
        return $this->service_id;
    }

    public function setServiceId(?Services $service_id): self
    {
        $this->service_id = $service_id;

        return $this;
    }


}
