<?php

namespace App\Entity;

use App\Repository\PositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PositionRepository::class)]
class Position
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Official>
     */
    #[ORM\ManyToMany(targetEntity: Official::class, inversedBy: 'positions')]
    private Collection $offId;

    public function __construct()
    {
        $this->offId = new ArrayCollection();
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
     * @return Collection<int, Official>
     */
    public function getOffId(): Collection
    {
        return $this->offId;
    }

    public function addOffId(Official $offId): static
    {
        if (!$this->offId->contains($offId)) {
            $this->offId->add($offId);
        }

        return $this;
    }

    public function removeOffId(Official $offId): static
    {
        $this->offId->removeElement($offId);

        return $this;
    }
}
