<?php

namespace App\Entity;

use App\Repository\AgeRatingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgeRatingRepository::class)]
class AgeRating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $age;

    #[ORM\Column(type: 'float')]
    private $rating_factor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getRatingFactor(): ?float
    {
        return $this->rating_factor;
    }

    public function setRatingFactor(float $rating_factor): self
    {
        $this->rating_factor = $rating_factor;

        return $this;
    }
}
