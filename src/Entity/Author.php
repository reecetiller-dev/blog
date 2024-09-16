<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Author
 *
 * @ORM\Table(name="author")
 * @ORM\Entity(repositoryClass="App\Repository\AuthorRepository")
 */
class Author
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(name="hash", type="string", length=10, unique=true)
     */
    private string $hash;

    /**
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private string $name;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private string $username;

    /**
     * @ORM\Column(name="company", type="string", length=255)
     */
    private string $company;

    /**
     * @ORM\Column(name="short_bio", type="string", length=500)
     */
    private string $shortBio;

    /**
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private ?string $phone;

    /**
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    private ?string $facebook;

    /**
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     */
    private ?string $twitter;

    /**
     * @ORM\Column(name="github", type="string", length=255, nullable=true)
     */
    private ?string $github;

    /**
     * @ORM\Column(name="created_at", type="datetimetz")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private \DateTime $updatedAt;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private ?\DateTime $deletedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->deletedAt = null;
        $this->hash = hash('crc32b', microtime());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getHash(): int
    {
        return $this->id;
    }

    public function setName(string $name): Author
    {
        $this->name = $name;

        return $this;
    }

    public function getName():string 
    {
        return $this->name;
    }

    public function setTitle(string $title): Author
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setUsername(string $username): Author
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setCompany(string $company): Author
    {
        $this->company = $company;

        return $this;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setShortBio(string $shortBio): Author
    {
        $this->shortBio = $shortBio;

        return $this;
    }

    public function getShortBio(): string
    {
        return $this->shortBio;
    }

    public function setPhone(string $phone): Author
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setFacebook(string $facebook): Author
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setTwitter(string $twitter): Author
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setGithub(string $github): Author
    {
        $this->github = $github;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }
}
