<?php

namespace App\Entity;

use App\Repository\AudiosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AudiosRepository::class)
 */
class Audios
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
    private $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $artist;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $soundfile;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getSoundfile(): ?string
    {
        return $this->soundfile;
    }

    public function setSoundfile(string $soundfile): self
    {
        $this->soundfile = $soundfile;

        return $this;
    }
}
