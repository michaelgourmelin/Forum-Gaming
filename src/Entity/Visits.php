<?php

namespace App\Entity;

use App\Repository\VisitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitsRepository::class)]
class Visits
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
     
    private ?int $id = null;

    
     #[ORM\Column(type:"integer")]
     
    private $count = 0;

    
     #[ORM\Column( type:"string", length:255, nullable:true)]
     
    private $ipaddress;

     #[ORM\ManyToMany(targetEntity: theme::class, inversedBy: 'visits')]
     private Collection $theme;

     public function __construct()
     {
         $this->theme = new ArrayCollection();
     }

    // Getters and setters for count and ipAddress

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;
        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipaddress;
    }

    public function setIpAddress(?string $ipaddress): self
    {
        $this->ipaddress = $ipaddress;
        return $this;
    }

    /**
     * @return Collection<int, theme>
     */
    public function getTheme(): Collection
    {
        return $this->theme;
    }

    public function addTheme(theme $theme): static
    {
        if (!$this->theme->contains($theme)) {
            $this->theme->add($theme);
        }

        return $this;
    }

    public function removeTheme(theme $theme): static
    {
        $this->theme->removeElement($theme);

        return $this;
    }
}
