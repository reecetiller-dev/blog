<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\BaseTrait;
use App\Entity\Traits\BlogPostTrait;

/**
 * BlogPost
 *
 * @ORM\Table(name="blog_post_history")
 * @ORM\Entity(repositoryClass="App\Repository\BlogPostHistoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class BlogPostHistory
{
    use BaseTrait;
    use BlogPostTrait;

    /**
     * @ORM\Column(name="action", type="string", length=255)
     */
    private string $action;

    /**
     * @ORM\ManyToOne(targetEntity="BlogPost")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private BlogPost $blogPost;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->deletedAt = null;
        $this->hash = hash('crc32b', microtime());
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

    public function setBlogPost(BlogPost $blogPost): BlogPostHistory
    {
        $this->blogPost = $blogPost;

        return $this;
    }

    public function getBlogPost(): BlogPost
    {
        return $this->blogPost;
    }
}
