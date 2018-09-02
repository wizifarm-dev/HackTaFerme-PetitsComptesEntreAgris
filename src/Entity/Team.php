<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
{
    use TimestampableEntity,
        BlameableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="teams")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Operation", mappedBy="team", orphanRemoval=true)
     */
    private $operations;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->operations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    /**
     * @return Collection|Operation[]
     */
    public function getOperations(): Collection
    {
        return $this->operations;
    }

    public function addOperation(Operation $operation): self
    {
        if (!$this->operations->contains($operation)) {
            $this->operations[] = $operation;
            $operation->setTeam($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        if ($this->operations->contains($operation)) {
            $this->operations->removeElement($operation);
            // set the owning side to null (unless already changed)
            if ($operation->getTeam() === $this) {
                $operation->setTeam(null);
            }
        }

        return $this;
    }

    public function getEqualizationPayment(User $user) : float
    {
        if ($this->operations->isEmpty()) {
            return 0;
        }

        $equalizationPayment = 0;

        foreach ($this->getOperations() as $operation) {
            $cost = $operation->getQuantity() * $operation->getResource()->getHourlyCost();

            if ($operation->getUser() === $user) {
                $equalizationPayment -= $cost;
            } else {
                $equalizationPayment += $cost;
            }
        }

        return $equalizationPayment;
    }

    public function __toString()
    {
        return $this->name;
    }
}
