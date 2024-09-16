<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlogPost
 *
 * @ORM\Table(name="blog_post_history")
 * @ORM\Entity(repositoryClass="App\Repository\BlogPostHistoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class BlogPostHistory
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
     * @ORM\Column(name="action", type="string", length=255)
     */
    private string $action;

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private ?string $title;

    /**
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    private ?string $body;

    /**
     * @ORM\ManyToOne(targetEntity="Author")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private Author $author;

    /**
     * @ORM\ManyToOne(targetEntity="BlogPost")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private BlogPost $blogPost;

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

    public function setAction(string $action): BlogPostHistory
    {
        $this->action = $action;

        return $this;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setTitle(string $title): BlogPostHistory
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setBody(string $body): BlogPostHistory
    {
        $this->body = $body;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setAuthor(Author $author): BlogPostHistory
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function setBlogPost(BlogPost $blogPost): BlogPostHistory
    {
        $this->blogPost = $blogPost;

        return $this;
    }

    public function getBlogPost(): BlogPost
    {
        return $this->blogPost;
    }

    public function setCreatedAt(\DateTime $createdAt): BlogPostHistory
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): BlogPostHistory
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
