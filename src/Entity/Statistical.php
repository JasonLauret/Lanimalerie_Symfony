<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StatisticalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatisticalRepository::class)
 */
#[ApiResource]
class Statistical
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $abandonedCart;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbOfVisits;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAbandonedCart(): ?int
    {
        return $this->abandonedCart;
    }

    public function setAbandonedCart(?int $abandonedCart): self
    {
        $this->abandonedCart = $abandonedCart;

        return $this;
    }

    public function getNbOfVisits(): ?int
    {
        return $this->nbOfVisits;
    }

    public function setNbOfVisits(?int $nbOfVisits): self
    {
        $this->nbOfVisits = $nbOfVisits;

        return $this;
    }
}
