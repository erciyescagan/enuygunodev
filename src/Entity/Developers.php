<?php

namespace App\Entity;

use App\Repository\DevelopersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DevelopersRepository::class)
 */
class Developers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $powerlevel;

    /**
     * @ORM\Column(type="integer")
     */
    private $dailyhours;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $Status;

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

    public function getPowerlevel(): ?int
    {
        return $this->powerlevel;
    }

    public function setPowerlevel(int $powerlevel): self
    {
        $this->powerlevel = $powerlevel;

        return $this;
    }

    public function getDailyhours(): ?int
    {
        return $this->dailyhours;
    }

    public function setDailyhours(int $dailyhours): self
    {
        $this->dailyhours = $dailyhours;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }
}
