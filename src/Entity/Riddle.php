<?php

namespace App\Entity;

use App\Repository\RiddleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: RiddleRepository::class)]
#[Vich\Uploadable()]
class Riddle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['riddle.show'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['riddle.show'])]
    private ?string $question = null;

    #[ORM\Column(length: 255)]
    private ?string $answer = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['riddle.show'])]
    private ?string $picture = null;

    #[Vich\UploadableField(mapping: 'riddlesPictures', fileNameProperty: 'picture')]
    #[Assert\Image()]
    private ?File $pictureFile = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'riddle')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Games $game = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): static
    {
        $this->answer = $answer;

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

    public function setPictureFile(?File $pictureFile = null): void
    {
        $this->pictureFile = $pictureFile;
        if (null !== $pictureFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getGame(): ?Games
    {
        return $this->game;
    }

    public function setGame(?Games $game): static
    {
        $this->game = $game;

        return $this;
    }
}
