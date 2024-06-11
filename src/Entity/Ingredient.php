<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Coktail>
     */
    #[ORM\ManyToMany(targetEntity: Coktail::class, mappedBy: 'Ingredients')]
    private Collection $coktails;

    public function __construct()
    {
        $this->coktails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Coktail>
     */
    public function getCoktails(): Collection
    {
        return $this->coktails;
    }

    public function addCoktail(Coktail $coktail): static
    {
        if (!$this->coktails->contains($coktail)) {
            $this->coktails->add($coktail);
            $coktail->addIngredient($this);
        }

        return $this;
    }

    public function removeCoktail(Coktail $coktail): static
    {
        if ($this->coktails->removeElement($coktail)) {
            $coktail->removeIngredient($this);
        }

        return $this;
    }
}
