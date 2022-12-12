<?php

namespace App\Entity\Blog;

use App\Repository\Blog\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $autor;

    /**
     * @ORM\Column(type="text")
     */
    private $blog;

    /**
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $tags;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="blog", cascade={"persist"})
     */
    private $comments;

    public function __construct()
    {
        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());
        $this->comments = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
       $this->setUpdated(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        $this->setSlug($this->title);

        return $this;
    }

    public function getAutor(): ?string
    {
        return $this->autor;
    }

    public function setAutor(string $autor): self
    {
        $this->autor = $autor;

        return $this;
    }

    public function getBlog(int $length = null): ?string
    {
        if (!is_null($length)) {
            if (mb_strlen($this->blog) > $length) {
                return substr($this->blog, 0, $length) . '...';
            } else {
                return $this->blog;
            }
        } else {
            return $this->blog;
        } 
    }

    public function setBlog(string $blog): self
    {
        $this->blog = $blog;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $comment->setBlog($this);
            $this->comments[] = $comment;
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getBlog() === $this) {
                $comment->setBlog(null);
            }
        }

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $this->slugify($slug);

        return $this;
    }

    public function slugify($text)
    {
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);

        $text = trim($text, '-');

        if (function_exists('iconv'))
        {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        $text = strtolower($text);

        $text = preg_replace('#[^-\w]+#', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }
}
