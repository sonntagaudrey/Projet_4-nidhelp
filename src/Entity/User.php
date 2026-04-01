<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'usr_id')]
    private ?int $id = null;

    #[ORM\Column(name:'usr_name', length: 160)]
    private ?string $name = null;

    #[ORM\Column(name:'usr_firstname',length: 160)]
    private ?string $firstname = null;

    #[ORM\Column(name:'usr_address',length: 255)]
    private ?string $adress = null;

    #[ORM\Column(name:'usr_postcode',length: 5)]
    private ?string $postcode = null;

    #[ORM\Column(name:'usr_town',length: 60)]
    private ?string $town = null;

    #[ORM\Column(name:'usr_email',length: 255)]
    private ?string $email = null;

    #[ORM\Column(name:'usr_pwd',length: 255)]
    private ?string $pwd = null;

    #[ORM\Column(name:'usr_create_date',)]
    private ?\DateTime $createDate = null;

    #[ORM\Column(name:'usr_update_date',nullable: true)]
    private ?\DateTime $updateDate = null;

    #[ORM\Column(length: 10)]
    private ?string $phone = null;

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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): static
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): static
    {
        $this->town = $town;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPwd(): ?string
    {
        return $this->pwd;
    }

    public function setPwd(string $pwd): static
    {
        $this->pwd = $pwd;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
}
