<?php

namespace App\Entity;

use App\Repository\GamesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: GamesRepository::class)]
#[UniqueEntity('title')]
#[Vich\Uploadable()]
class Games
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $time = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $difficulty = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[Vich\UploadableField(mapping: 'gamesPictures', fileNameProperty: 'picture')]
    #[Assert\Image()]
    private ?File $pictureFile = null;

    #[ORM\Column(length: 1024)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Riddle::class, mappedBy: 'game', orphanRemoval: true, cascade: ['persist'])]
    private Collection $riddle;

    public function __construct()
    {
        $this->riddle = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    public function setPictureFile(?File $pictureFile): static
    {
        $this->pictureFile = $pictureFile;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Riddle>
     */
    public function getRiddle(): Collection
    {
        return $this->riddle;
    }

    public function addRiddle(Riddle $riddle): static
    {
        if (!$this->riddle->contains($riddle)) {
            $this->riddle->add($riddle);
            $riddle->setGame($this);
        }

        return $this;
    }

    public function removeRiddle(Riddle $riddle): static
    {
        if ($this->riddle->removeElement($riddle)) {
            // set the owning side to null (unless already changed)
            if ($riddle->getGame() === $this) {
                $riddle->setGame(null);
            }
        }

        return $this;
    }
}
