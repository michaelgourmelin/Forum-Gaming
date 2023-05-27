<?php

namespace App\Entity\Trait;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Doctrine\ORM\Mapping as ORM;

trait CreatedAtTrait
{
    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $created_at;

   
  
    public function __construct()

    {
        // $origin = new DateTimeImmutable($this->created_at);
        // $target = new DateTimeImmutable('now');
        // $interval = $origin->diff($target);
        // return $interval;
    }

    


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
       
    }


    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}