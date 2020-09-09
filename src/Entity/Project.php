<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
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
    private $ProviderId;

    /**
     * @ORM\Column(type="integer")
     */
    private $CompanyId;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $Status;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $Assigned;

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

    public function getProviderId(): ?int
    {
        return $this->ProviderId;
    }

    public function setProviderId(int $ProviderId): self
    {
        $this->ProviderId = $ProviderId;

        return $this;
    }

    public function getCompanyId(): ?int
    {
        return $this->CompanyId;
    }

    public function setCompanyId(int $CompanyId): self
    {
        $this->CompanyId = $CompanyId;

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

    public function getAssigned(): ?string
    {
        return $this->Assigned;
    }

    public function setAssigned(?string $Assigned): self
    {
        $this->Assigned = $Assigned;

        return $this;
    }
}
