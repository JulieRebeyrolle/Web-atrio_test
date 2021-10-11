<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255", maxMessage="Le nom saisi {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères")
     * @Assert\Length(min="2", minMessage="Le nom saisi {{ value }} est trop court, il devrait contenir au moins {{ limit }} caractères")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255", maxMessage="Le prénom saisi {{ value }} est trop long, il ne devrait pas dépasser {{ limit }} caractères")
     * @Assert\Length(min="2", minMessage="Le prénom saisi {{ value }} est trop court, il devrait contenir au moins {{ limit }} caractères")
     *
     */
    private $firstname;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $birthdate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = trim($lastname);

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = trim($firstname);

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getAge(): int
    {
        $today = new \DateTime('now');
        return $this->birthdate->diff($today)->y;
    }
}
