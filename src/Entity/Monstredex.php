<?php

namespace App\Entity;

use App\Repository\MonstredexRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
// use Symfony\Component\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MonstredexRepository::class)]
class Monstredex
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getAllMonstredex", "getAllMonstre"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    
    #[Groups(["getAllMonstredex", "getAllMonstre"])]
    
    #[Assert\NotBlank(message:"Une entrée Monstredex se doit d'avoir un nom composé de characteres")]
    #[Assert\NotNull(message:"Une entrée Monstredex se doit d'avoir un nom composé de characteres")]
    #[Assert\Length(min: 5, minMessage:"Une entrée Monstredex se doit d'avoir un nom composé d'au moins {{ limit }} characteres")]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(["getAllMonstredex"])]
    private ?int $pv_max = null;

    #[ORM\Column]
    #[Groups(["getAllMonstredex"])]
    private ?int $pv_min = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'evolution')]
    private ?self $devolution = null;

    #[ORM\OneToMany(mappedBy: 'devolution', targetEntity: self::class)]

    private Collection $evolution;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $created_by = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $updated_by = null;

    public function __construct()
    {
        $this->evolution = new ArrayCollection();
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

    public function getPvMax(): ?int
    {
        return $this->pv_max;
    }

    public function setPvMax(int $pv_max): static
    {
        $this->pv_max = $pv_max;

        return $this;
    }

    public function getPvMin(): ?int
    {
        return $this->pv_min;
    }

    public function setPvMin(int $pv_min): static
    {
        $this->pv_min = $pv_min;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDevolution(): ?self
    {
        return $this->devolution;
    }

    public function setDevolution(?self $devolution): static
    {
        $this->devolution = $devolution;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getEvolution(): Collection
    {
        return $this->evolution;
    }

    public function addEvolution(self $evolution): static
    {
        if (!$this->evolution->contains($evolution)) {
            $this->evolution->add($evolution);
            $evolution->setDevolution($this);
        }

        return $this;
    }

    public function removeEvolution(self $evolution): static
    {
        if ($this->evolution->removeElement($evolution)) {
            // set the owning side to null (unless already changed)
            if ($evolution->getDevolution() === $this) {
                $evolution->setDevolution(null);
            }
        }

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): static
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updated_by;
    }

    public function setUpdatedBy(?User $updated_by): static
    {
        $this->updated_by = $updated_by;

        return $this;
    }
}
