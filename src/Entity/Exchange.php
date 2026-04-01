<?php

namespace App\Entity;

use App\Repository\ExchangeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRepository::class)]
#[ORM\Table(name: 'exchanges')]
class Exchange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'exc_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'exc_object',length: 255)]
    private ?string $object = null;

    #[ORM\Column(name: 'exc_message',type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(name: 'exc_create_date')]
    private ?\DateTime $createDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(string $object): static
    {
        $this->object = $object;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getCreateDate(): ?\DateTime
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTime $createDate): static
    {
        $this->createDate = $createDate;

        return $this;
    }
}
