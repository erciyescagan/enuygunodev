<?php

namespace App\Entity;

use App\Repository\ParamsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParamsRepository::class)
 */
class Params
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
    private $Key;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $val;

    /**
     * @ORM\Column(type="integer")
     */
    private $CompanyId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    public function setKey(string $Key): self
    {
        $this->Key = $Key;

        return $this;
    }

    public function getVal(): ?string
    {
        return $this->val;
    }

    public function setVal(string $val): self
    {
        $this->val = $val;

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
}
