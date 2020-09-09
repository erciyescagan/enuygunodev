<?php

namespace App\Entity;

use App\Repository\AssignmentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AssignmentsRepository::class)
 */
class Assignments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $taskId;

    /**
     * @ORM\Column(type="integer")
     */
    private $DevId;

    /**
     * @ORM\Column(type="integer")
     */
    private $ProjectId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskId(): ?int
    {
        return $this->taskId;
    }

    public function setTaskId(int $taskId): self
    {
        $this->taskId = $taskId;

        return $this;
    }

    public function getDevId(): ?int
    {
        return $this->DevId;
    }

    public function setDevId(int $DevId): self
    {
        $this->DevId = $DevId;

        return $this;
    }

    public function getProjectId(): ?int
    {
        return $this->ProjectId;
    }

    public function setProjectId(int $ProjectId): self
    {
        $this->ProjectId = $ProjectId;

        return $this;
    }
}
