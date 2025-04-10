<?php

namespace App\Entity;

use App\Repository\OfficialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfficialRepository::class)]
class Official
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 11)]
    private ?string $cpf = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateHiring = null;

    #[ORM\ManyToOne(inversedBy: 'officials')]
    private ?Company $comId = null;

    /**
     * @var Collection<int, Salary>
     */
    #[ORM\OneToMany(targetEntity: Salary::class, mappedBy: 'offId')]
    private Collection $salaries;

    /**
     * @var Collection<int, Position>
     */
    #[ORM\ManyToMany(targetEntity: Position::class, mappedBy: 'offId')]
    private Collection $positions;

    public function __construct()
    {
        $this->salaries = new ArrayCollection();
        $this->positions = new ArrayCollection();
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

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): static
    {
        $this->cpf = $cpf;

        return $this;
    }

    public function getDateHiring(): ?\DateTimeInterface
    {
        return $this->dateHiring;
    }

    public function setDateHiring(\DateTimeInterface $dateHiring): static
    {
        $this->dateHiring = $dateHiring;

        return $this;
    }

    public function getComId(): ?Company
    {
        return $this->comId;
    }

    public function setComId(?Company $comId): static
    {
        $this->comId = $comId;

        return $this;
    }

    /**
     * @return Collection<int, Salary>
     */
    public function getSalaries(): Collection
    {
        return $this->salaries;
    }

    public function addSalary(Salary $salary): static
    {
        if (!$this->salaries->contains($salary)) {
            $this->salaries->add($salary);
            $salary->setOffId($this);
        }

        return $this;
    }

    public function removeSalary(Salary $salary): static
    {
        if ($this->salaries->removeElement($salary)) {
            // set the owning side to null (unless already changed)
            if ($salary->getOffId() === $this) {
                $salary->setOffId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Position>
     */
    public function getPositions(): Collection
    {
        return $this->positions;
    }

    public function addPosition(Position $position): static
    {
        if (!$this->positions->contains($position)) {
            $this->positions->add($position);
            $position->addOffId($this);
        }

        return $this;
    }

    public function removePosition(Position $position): static
    {
        if ($this->positions->removeElement($position)) {
            $position->removeOffId($this);
        }

        return $this;
    }
}
