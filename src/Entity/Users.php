<?php

namespace App\Entity;

use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Entity\Trait\CreatedAtTrait;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;



#[UniqueEntity(fields: ['email'], message: 'Il existe déja un compte pour cet e-mail')]
#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[Vich\Uploadable]

class Users implements UserInterface, PasswordAuthenticatedUserInterface

{
    use CreatedAtTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Merci de renseigner un email')]
    #[Assert\Email(
        message: "{{ value }}n'est pas valide",
        mode: "strict"
    )]
    private ?string $email = null;


    #[ORM\Column(type: 'json')]
    private $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;


    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(message: 'Merci de renseigner un pseudo')]
    #[Assert\Length(
        min: 5,
        max: 25,
        minMessage: 'Votre pseudo doit faire au moins de {{ limit }} caractères',
        maxMessage: 'Votre pseudo ne peut pas faire plus de {{ limit }} caractères',
    )]
    private $firstname;


    #[ORM\OneToMany(mappedBy: 'users', targetEntity: Theme::class)]
    private Collection $themes;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(type: 'boolean')]
    private $isBanned = false;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserPicture $userPicture = null;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->themes = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
    public function getUserIdentifier(): string
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


        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }


    /**
     * @return Collection<int, Theme>
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }

    public function addTheme(Theme $theme): self
    {
        if (!$this->themes->contains($theme)) {
            $this->themes->add($theme);
            $theme->setUsers($this);
        }

        return $this;
    }

    public function removeTheme(Theme $theme): self
    {
        if ($this->themes->removeElement($theme)) {
            // set the owning side to null (unless already changed)
            if ($theme->getUsers() === $this) {
                $theme->setUsers($this);
            }
        }

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
            $this->comments->add($comment);
            $comment->setUsers($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUsers() === $this) {
                $comment->setUsers(null);
            }
        }

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getIsBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(?bool $isBanned): static
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    public function getUserPicture(): ?UserPicture
    {
        return $this->userPicture;
    }

    public function setUserPicture(?UserPicture $userPicture): static
    {
        // unset the owning side of the relation if necessary
        if ($userPicture === null && $this->userPicture !== null) {
            $this->userPicture->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($userPicture !== null && $userPicture->getUser() !== $this) {
            $userPicture->setUser($this);
        }

        $this->userPicture = $userPicture;

        return $this;
    }


  

 
}
