<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index as index;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\Table(name="posts", indexes={@Index(name="title_idx", columns={"title"})})
 */
class Post
{
    public const uploadFolder = '/uploads/images/';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $photo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="posts")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="likedPosts")
     * @ORM\JoinTable(name="likes")
     */
    private $usersThatLike;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="dislikedPosts")
     * @ORM\JoinTable(name="dislikes")
     */
    private $usersThatDontLike;

  /** 
    * @Assert\NotBlank(message="Please, upload a post image!")
    * @Assert\File(mimeTypes={ "image/*" })
    */
    private $uploaded_image;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->usersThatLike = new ArrayCollection();
        $this->usersThatDontLike = new ArrayCollection();
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

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRelation(): ?Category
    {
        return $this->category;
    }

    public function setRelation(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersThatLike(): Collection
    {
        return $this->usersThatLike;
    }

    public function addUsersThatLike(User $usersThatLike): self
    {
        if (!$this->usersThatLike->contains($usersThatLike)) {
            $this->usersThatLike[] = $usersThatLike;
        }

        return $this;
    }

    public function removeUsersThatLike(User $usersThatLike): self
    {
        if ($this->usersThatLike->contains($usersThatLike)) {
            $this->usersThatLike->removeElement($usersThatLike);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsersThatDontLike(): Collection
    {
        return $this->usersThatDontLike;
    }

    public function addUsersThatDontLike(User $usersThatDontLike): self
    {
        if (!$this->usersThatDontLike->contains($usersThatDontLike)) {
            $this->usersThatDontLike[] = $usersThatDontLike;
        }

        return $this;
    }

    public function removeUsersThatDontLike(User $usersThatDontLike): self
    {
        if ($this->usersThatDontLike->contains($usersThatDontLike)) {
            $this->usersThatDontLike->removeElement($usersThatDontLike);
        }

        return $this;
    }

    public function getUploadedImage()
    {
        return $this->uploaded_image;
    }

    public function setUploadedImage($uploaded_image): self
    {
        $this->uploaded_image = $uploaded_image;

        return $this;
    }
}
