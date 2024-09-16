<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlogPost
 *
 * @ORM\Table(name="blog_post")
 * @ORM\Entity(repositoryClass="App\Repository\BlogPostRepository")
 * @ORM\HasLifecycleCallbacks
 */
class BlogPost
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(name="body", type="text")
     */
    private string $body;

    /**
     * @ORM\ManyToOne(targetEntity="Author")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private Author $author;

    /**
     * @ORM\ManyToOne(targetEntity="BlogPostHistory")
     * @ORM\JoinColumn(name="blog_post_history_id", referencedColumnName="id")
     */
    private BlogPostHistory $blogPostHistory;

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

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setTitle(string $title): BlogPost
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setBody(string $body): BlogPost
    {
        $this->body = $body;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setAuthor(Author $author): BlogPost
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function setBlogPostHistory(BlogPostHistory $blogPostHistory): BlogPostHistory
    {
        $this->blogPosthistory = $blogPostHistory;

        return $this;
    }

    public function getBlogPostHistory(): BlogPostHistory
    {
        return $this->blogPosthistory;
    }

    public function setCreatedAt(\DateTime $createdAt): BlogPost
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): BlogPost
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setDeletedAt(?\DateTime $deletedAt): BlogPost
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }
}
