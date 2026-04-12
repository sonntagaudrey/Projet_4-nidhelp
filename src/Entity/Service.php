<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ORM\Table(name: 'services')]
#[ORM\HasLifecycleCallbacks]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'srv_id')]
    private ?int $id = null;

    #[ORM\Column(name: 'srv_name', length: 255)]
    private ?string $name = null;

    #[ORM\Column(name: 'srv_description', type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(name: 'srv_create_date')]
    private ?\DateTime $createDate = null;

    #[ORM\Column(name: 'srv_update_date',nullable: true)]
    private ?\DateTime $updateDate = null; 

    #[ORM\ManyToOne(inversedBy: 'services')] // Attention, tu avais 'service' au singulier, vérifie le mappedBy dans Category
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'cat_id', nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'usr_id', nullable: false)]
    private ?User $author = null;

    public function __construct()
    {
        $this->createDate = new \DateTime();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getUpdateDate(): ?\DateTime
    {
        return $this->updateDate;
    }

    public function setUpdateDate(?\DateTime $updateDate): static
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }
}
