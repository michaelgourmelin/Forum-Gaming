<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    use CreatedAtTrait;
    use SlugTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
 
    private ?int $id = null;


    #[ORM\Column(type: 'text', length: 70)]
    #[Assert\NotBlank(message: 'Merci de renseigner un thème')]
    #[Assert\Length(
        min: 5,
        max: 25,
        minMessage: 'Votre thème doit faire au moins {{ limit }} caractères',
        maxMessage: 'Votre thème ne peut pas faire plus de {{ limit }} caractères',
    )]
    private ?string $name = null;


    #[ORM\ManyToOne(inversedBy: 'themes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categories $categories = null;


    #[ORM\ManyToOne(inversedBy: 'themes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    #[ORM\OneToMany(mappedBy: 'theme', targetEntity: Comment::class,cascade:["remove"])] #[OrderBy(["created_at" => "DESC"])]
    private Collection $comments;



    public function __construct()
    {

        $this->created_at = new \DateTimeImmutable();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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


    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getUsers(): Users
    {
        return $this->users;
    }

    public function setUsers(Users $users): self
    {
        $this->users = $users;

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
            $comment->setTheme($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTheme() === $this) {
                $comment->setTheme(null);
            }
        }

        return $this;
    }
}
