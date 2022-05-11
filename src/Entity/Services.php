<?php

namespace App\Entity;

use App\Repository\ServicesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ServicesRepository::class)]
class Services
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $subtitle;

    #[ORM\Column(type: 'text')]
    private $text;

    #[ORM\OneToMany(mappedBy: 'service_id', targetEntity: ImagesServices::class, orphanRemoval: true)]
    private $imagesServices;

    #[ORM\Column(type: 'string', length: 255)]
    private $shortcut;

    public function __construct()
    {
        $this->imagesServices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection|ImagesServices[]
     */
    public function getImagesServices(): Collection
    {
        return $this->imagesServices;
    }

    public function addImagesService(ImagesServices $imagesService): self
    {
        if (!$this->imagesServices->contains($imagesService)) {
            $this->imagesServices[] = $imagesService;
            $imagesService->setServiceId($this);
        }

        return $this;
    }

    public function removeImagesService(ImagesServices $imagesService): self
    {
        if ($this->imagesServices->removeElement($imagesService)) {
            // set the owning side to null (unless already changed)
            if ($imagesService->getServiceId() === $this) {
                $imagesService->setServiceId(null);
            }
        }

        return $this;
    }

    public function getShortcut(): ?string
    {
        return $this->shortcut;
    }

    public function setShortcut(string $shortcut): self
    {
        $this->shortcut = $shortcut;

        return $this;
    }

}
