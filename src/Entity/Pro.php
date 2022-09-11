<?php

namespace App\Entity;

use App\Repository\ProRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProRepository::class)]
class Pro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $businessName = null;

    #[ORM\Column(length: 255)]
    private ?string $contactName = null;

    #[ORM\Column(length: 10)]
    private ?string $phone = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'pros')]
    private Collection $categories;


    #[ORM\OneToMany(mappedBy: 'pro', targetEntity: Picture::class, cascade: ['persist'])]
    private Collection $pictures;

    #[ORM\ManyToOne(inversedBy: 'pros')]
    private ?City $city = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $departments = null;

    private ?Picture $firstPicture;


    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function showCategories(): string 
    {
        $categories_name = [];
        foreach($this->categories as $category)
        {
            $categories_name[] = $category->getName();
        }
        $html = implode(' - ', $categories_name);
        return substr($html, 0, 50). '...';
    }

    public function getBusinessName(): ?string
    {
        return $this->businessName;
    }

    public function setBusinessName(string $businessName): self
    {
        $this->businessName = $businessName;

        return $this;
    }

    public function getContactName(): ?string
    {
        return $this->contactName;
    }

    public function setContactName(string $contactName): self
    {
        $this->contactName = $contactName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }


    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setPro($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getPro() === $this) {
                $picture->setPro(null);
            }
        }

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDepartments(): ?string
    {
        return $this->departments;
    }

    public function setDepartments(string $departments): self
    {
        $this->departments = $departments;

        return $this;
    }



    /**
     * Get the value of firstPicture
     */ 
    public function getFirstPicture()
    {
        return $this->firstPicture;
    }

    /**
     * Set the value of firstPicture
     *
     * @return  self
     */ 
    public function setFirstPicture($firstPicture)
    {
        $this->firstPicture = $firstPicture;

        return $this;
    }
}
