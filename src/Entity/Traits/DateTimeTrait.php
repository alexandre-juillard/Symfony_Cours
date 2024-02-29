<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DateTimeTrait
{
    #[ORM\Column]
    
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(nullable: true)]
   
    private ?\DateTimeInterface $updatedAt = null;

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist()]
    public function setAutoCreatedAt(): void
    {
        $this->createdAt = new DateTimeInterface();
    }

    #[ORM\PreUpdate()]
    public function setAutoUpdatedAt(): voidS
    {
        $this->updatedAt = new DateTimeInterface();
    }

}