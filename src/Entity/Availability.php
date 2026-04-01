<?php

namespace App\Entity;

use App\Repository\AvailabilityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvailabilityRepository::class)]
#[ORM\Table(name: 'availabilities')]
class Availability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'avb_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'avb_start_time',type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $startTime = null;

    #[ORM\Column(name: 'avb_end_time',type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $endTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(?\DateTime $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTime $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }
}
