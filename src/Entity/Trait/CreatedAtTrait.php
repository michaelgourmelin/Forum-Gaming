<?php

namespace App\Entity\Trait;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait CreatedAtTrait
{
    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $created_at;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getTimeSinceCreation(): string
    {
        $origin = $this->created_at;
        $target = new DateTimeImmutable('now');
        $interval = $origin->diff($target);

        if ($interval->y > 0) {
            return $interval->format('%y ans');
        } elseif ($interval->m > 0) {
            return $interval->format('%m mois');
        } elseif ($interval->days > 0) {
            return $interval->format('%a jours');
        } elseif ($interval->h > 0) {
            return $interval->format('%h heures');
        } else {
            return $interval->format('%i minutes');
        }
    }
}
