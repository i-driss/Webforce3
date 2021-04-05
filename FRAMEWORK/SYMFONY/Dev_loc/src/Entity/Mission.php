<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MissionRepository::class)
 */
class Mission
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $forfait;

    /**
     * @ORM\OneToOne(targetEntity=user::class, cascade={"persist", "remove"})
     */
    private $shopkeeper;

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

    public function getForfait(): ?int
    {
        return $this->forfait;
    }

    public function setForfait(int $forfait): self
    {
        $this->forfait = $forfait;

        return $this;
    }

    public function getShopkeeper(): ?user
    {
        return $this->shopkeeper;
    }

    public function setShopkeeper(?user $shopkeeper): self
    {
        $this->shopkeeper = $shopkeeper;

        return $this;
    }
}
