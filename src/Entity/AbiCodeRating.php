<?php

namespace App\Entity;

use App\Repository\AbiCodeRatingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbiCodeRatingRepository::class)]
class AbiCodeRating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 10)]
    private $abi_code;

    #[ORM\Column(type: 'float')]
    private $rating_factor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAbiCode(): ?string
    {
        return $this->abi_code;
    }

    public function setAbiCode(string $abi_code): self
    {
        $this->abi_code = $abi_code;

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
