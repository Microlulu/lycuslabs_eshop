<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $shortcut;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\Column(type: 'datetime')]
    private $createdat;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $deletedat;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updatedat;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $category_id;

    #[ORM\OneToMany(mappedBy: 'product_id', targetEntity: ImagesProduct::class, orphanRemoval: true)]
    private $imagesProducts;

    #[ORM\ManyToOne(targetEntity: Voucher::class, inversedBy: 'product_id')]
    private $voucher;

    #[ORM\Column(type: 'text', nullable: true)]
    private $descriptionadd;

    public function __construct()
    {
        $this->imagesProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getShortcut(): ?string
    {
        return $this->shortcut;
    }

    public function setShortcut(?string $shortcut): self
    {
        $this->shortcut = $shortcut;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
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

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getDeletedat(): ?\DateTimeInterface
    {
        return $this->deletedat;
    }

    public function setDeletedat(?\DateTimeInterface $deletedat): self
    {
        $this->deletedat = $deletedat;

        return $this;
    }

    public function getUpdatedat(): ?\DateTimeInterface
    {
        return $this->updatedat;
    }

    public function setUpdatedat(?\DateTimeInterface $updatedat): self
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    public function getCategoryId(): ?Category
    {
        return $this->category_id;
    }

    public function setCategoryId(?Category $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }

    /**
     * @return Collection|ImagesProduct[]
     */
    public function getImagesProducts(): Collection
    {
        return $this->imagesProducts;
    }

    public function addImagesProduct(ImagesProduct $imagesProduct): self
    {
        if (!$this->imagesProducts->contains($imagesProduct)) {
            $this->imagesProducts[] = $imagesProduct;
            $imagesProduct->setProductId($this);
        }

        return $this;
    }

    public function removeImagesProduct(ImagesProduct $imagesProduct): self
    {
        if ($this->imagesProducts->removeElement($imagesProduct)) {
            // set the owning side to null (unless already changed)
            if ($imagesProduct->getProductId() === $this) {
                $imagesProduct->setProductId(null);
            }
        }

        return $this;
    }

    public function getVoucher(): ?Voucher
    {
        return $this->voucher;
    }

    public function setVoucher(?Voucher $voucher): self
    {
        $this->voucher = $voucher;

        return $this;
    }

    public function getDescriptionadd(): ?string
    {
        return $this->descriptionadd;
    }

    public function setDescriptionadd(?string $descriptionadd): self
    {
        $this->descriptionadd = $descriptionadd;

        return $this;
    }
}
