<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email")
 * errorPath="email",
 * message="It appears you have already registered with this email."
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
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
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Drink::class, mappedBy="user", orphanRemoval=true)
     */
    private $drinks;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $alcoolScore;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSubscribed;

    /**
     * @ORM\OneToMany(targetEntity=Sober::class, mappedBy="user", orphanRemoval=true)
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=ArgumentUser::class, mappedBy="user", orphanRemoval=true)
     */
    private $argumentUsers;

    /**
     * @ORM\OneToMany(targetEntity=Goal::class, mappedBy="user", orphanRemoval=true)
     */
    private $goals;

    /**
     * @ORM\ManyToMany(targetEntity=Badge::class, mappedBy="user")
     */
    private $badges;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $homepage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $autoSober;

    /**
     * @ORM\OneToMany(targetEntity=Money::class, mappedBy="user", orphanRemoval=true)
     */
    private $money;

    public function __construct()
    {
        $this->drinks = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->date = new ArrayCollection();
        $this->argumentUsers = new ArrayCollection();
        $this->goals = new ArrayCollection();
        $this->badges = new ArrayCollection();
        $this->money = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->pseudo;
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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Drink[]
     */
    public function getDrinks(): Collection
    {
        return $this->drinks;
    }

    public function addDrink(Drink $drink): self
    {
        if (!$this->drinks->contains($drink)) {
            $this->drinks[] = $drink;
            $drink->setUser($this);
        }

        return $this;
    }

    public function removeDrink(Drink $drink): self
    {
        if ($this->drinks->removeElement($drink)) {
            // set the owning side to null (unless already changed)
            if ($drink->getUser() === $this) {
                $drink->setUser(null);
            }
        }

        return $this;
    }

    public function getAlcoolScore(): ?int
    {
        return $this->alcoolScore;
    }

    public function setAlcoolScore(?int $alcoolScore): self
    {
        $this->alcoolScore = $alcoolScore;

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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getIsSubscribed(): ?bool
    {
        return $this->isSubscribed;
    }

    public function setIsSubscribed(bool $isSubscribed): self
    {
        $this->isSubscribed = $isSubscribed;

        return $this;
    }

    /**
     * @return Collection|Sober[]
     */
    public function getDate(): Collection
    {
        return $this->date;
    }

    public function addDate(Sober $date): self
    {
        if (!$this->date->contains($date)) {
            $this->date[] = $date;
            $date->setUser($this);
        }

        return $this;
    }

    public function removeDate(Sober $date): self
    {
        if ($this->date->removeElement($date)) {
            // set the owning side to null (unless already changed)
            if ($date->getUser() === $this) {
                $date->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ArgumentUser[]
     */
    public function getArgumentUsers(): Collection
    {
        return $this->argumentUsers;
    }

    public function addArgumentUser(ArgumentUser $argumentUser): self
    {
        if (!$this->argumentUsers->contains($argumentUser)) {
            $this->argumentUsers[] = $argumentUser;
            $argumentUser->setUser($this);
        }

        return $this;
    }

    public function removeArgumentUser(ArgumentUser $argumentUser): self
    {
        if ($this->argumentUsers->removeElement($argumentUser)) {
            // set the owning side to null (unless already changed)
            if ($argumentUser->getUser() === $this) {
                $argumentUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Goal[]
     */
    public function getGoals(): Collection
    {
        return $this->goals;
    }

    public function addGoal(Goal $goal): self
    {
        if (!$this->goals->contains($goal)) {
            $this->goals[] = $goal;
            $goal->setUser($this);
        }

        return $this;
    }

    public function removeGoal(Goal $goal): self
    {
        if ($this->goals->removeElement($goal)) {
            // set the owning side to null (unless already changed)
            if ($goal->getUser() === $this) {
                $goal->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Badge[]
     */
    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function addBadge(Badge $badge): self
    {
        if (!$this->badges->contains($badge)) {
            $this->badges[] = $badge;
            $badge->addUser($this);
        }

        return $this;
    }

    public function removeBadge(Badge $badge): self
    {
        if ($this->badges->removeElement($badge)) {
            $badge->removeUser($this);
        }

        return $this;
    }

    public function getHomepage(): ?string
    {
        return $this->homepage;
    }

    public function setHomepage(?string $homepage): self
    {
        $this->homepage = $homepage;

        return $this;
    }

    public function getAutoSober(): ?bool
    {
        return $this->autoSober;
    }

    public function setAutoSober(bool $autoSober): self
    {
        $this->autoSober = $autoSober;

        return $this;
    }

    /**
     * @return Collection|Money[]
     */
    public function getMoney(): Collection
    {
        return $this->money;
    }

    public function addMoney(Money $money): self
    {
        if (!$this->money->contains($money)) {
            $this->money[] = $money;
            $money->setUser($this);
        }

        return $this;
    }

    public function removeMoney(Money $money): self
    {
        if ($this->money->removeElement($money)) {
            // set the owning side to null (unless already changed)
            if ($money->getUser() === $this) {
                $money->setUser(null);
            }
        }

        return $this;
    }
}
