<?php

namespace App\Entity;

use App\Repository\ShortUrlRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShortUrlRepository::class)]
class ShortUrl
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $matched_url = null;

    #[ORM\Column(length: 255)]
    private ?string $short_code_for_url = null;

    #[ORM\Column]
    private ?bool $active = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatchedUrl(): ?string
    {
        return $this->matched_url;
    }

    public function setMatchedUrl(string $matched_url): static
    {
        $this->matched_url = $matched_url;

        return $this;
    }

    public function getShortCodeForUrl(): ?string
    {
        return $this->short_code_for_url;
    }

    public function setShortCodeForUrl(string $short_code_for_url): static
    {
        $this->short_code_for_url = $short_code_for_url;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
