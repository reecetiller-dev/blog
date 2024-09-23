<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\BaseTrait;
use App\Entity\Traits\BlogPostTrait;

/**
 * BlogPost
 *
 * @ORM\Table(name="blog_post")
 * @ORM\Entity(repositoryClass="App\Repository\BlogPostRepository")
 * @ORM\HasLifecycleCallbacks
 */
class BlogPost
{
    use BaseTrait;
    use BlogPostTrait;

    /**
     * @ORM\ManyToOne(targetEntity="BlogPostHistory")
     * @ORM\JoinColumn(name="blog_post_history_id", referencedColumnName="id")
     */
    private BlogPostHistory $blogPostHistory;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->deletedAt = null;
        $this->hash = hash('crc32b', microtime());
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
}
