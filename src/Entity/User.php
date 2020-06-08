<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message = "Please enter a valid email address.")
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message = "Please enter a valid password")
     * @Assert\Length(max=4096)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank(message = "Valid first name is required.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank(message = "Valid last name is required.")
     */
    private $last_name;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class, mappedBy="usersThatLike")
     * @ORM\JoinTable(name="likes")
     */
    private $likedPosts;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class, mappedBy="usersThatDontLike")
     * @ORM\JoinTable(name="dislikes")
     */
    private $dislikedPosts;

    public function __construct()
    {
        $this->likedPosts = new ArrayCollection();
        $this->dislikedPosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getLikedPosts(): Collection
    {
        return $this->likedPosts;
    }

    public function addLikedPost(Post $likedPost): self
    {
        if (!$this->likedPosts->contains($likedPost)) {
            $this->likedPosts[] = $likedPost;
            $likedPost->addUsersThatLike($this);
        }

        return $this;
    }

    public function removeLikedPost(Post $likedPost): self
    {
        if ($this->likedPosts->contains($likedPost)) {
            $this->likedPosts->removeElement($likedPost);
            $likedPost->removeUsersThatLike($this);
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getDislikedPosts(): Collection
    {
        return $this->dislikedPosts;
    }

    public function addDislikedPost(Post $dislikedPost): self
    {
        if (!$this->dislikedPosts->contains($dislikedPost)) {
            $this->dislikedPosts[] = $dislikedPost;
            $dislikedPost->addUsersThatDontLike($this);
        }

        return $this;
    }

    public function removeDislikedPost(Post $dislikedPost): self
    {
        if ($this->dislikedPosts->contains($dislikedPost)) {
            $this->dislikedPosts->removeElement($dislikedPost);
            $dislikedPost->removeUsersThatDontLike($this);
        }

        return $this;
    }
}
