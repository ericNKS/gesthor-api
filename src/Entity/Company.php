<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 14, unique:true)]
    private ?string $cnpj = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'comId')]
    private Collection $users;

    /**
     * @var Collection<int, Rule>
     */
    #[ORM\OneToMany(targetEntity: Rule::class, mappedBy: 'ComId')]
    private Collection $rules;

    /**
     * @var Collection<int, Official>
     */
    #[ORM\OneToMany(targetEntity: Official::class, mappedBy: 'comId')]
    private Collection $officials;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->rules = new ArrayCollection();
        $this->officials = new ArrayCollection();
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'cnpj' => $this->getCnpj(),
        ];
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

    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    public function setCnpj(string $cnpj): static
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setComId($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getComId() === $this) {
                $user->setComId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rule>
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function addRule(Rule $rule): static
    {
        if (!$this->rules->contains($rule)) {
            $this->rules->add($rule);
            $rule->setComId($this);
        }

        return $this;
    }

    public function removeRule(Rule $rule): static
    {
        if ($this->rules->removeElement($rule)) {
            // set the owning side to null (unless already changed)
            if ($rule->getComId() === $this) {
                $rule->setComId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Official>
     */
    public function getOfficials(): Collection
    {
        return $this->officials;
    }

    public function addOfficial(Official $official): static
    {
        if (!$this->officials->contains($official)) {
            $this->officials->add($official);
            $official->setComId($this);
        }

        return $this;
    }

    public function removeOfficial(Official $official): static
    {
        if ($this->officials->removeElement($official)) {
            // set the owning side to null (unless already changed)
            if ($official->getComId() === $this) {
                $official->setComId(null);
            }
        }

        return $this;
    }
}
