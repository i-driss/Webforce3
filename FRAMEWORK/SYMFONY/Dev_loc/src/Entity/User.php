<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profil_picture;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $presentation;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $skill;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $movie;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $music;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cv;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reset_token;
     /** 
      * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $activation_token;
   

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $postal_code;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $verified = false;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $package;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $details;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $social_entity;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="User")
     */
    private $articles;

    /**
     * @ORM\Column(type="boolean", options={"default":true})
     */
    private $availabe;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="send_from")
     */
    private $chats_from;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="send_to")
     */
    private $chats_to;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->chats_from = new ArrayCollection();
        $this->chats_to = new ArrayCollection();
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

    public function setPassword(string $password): self
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

    public function getProfilPicture(): ?string
    {
        return $this->profil_picture;
    }

    public function setProfilPicture(?string $profil_picture): self
    {
        $this->profil_picture = $profil_picture;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getSkill(): ?string
    {
        return $this->skill;
    }

    public function setSkill(?string $skill): self
    {
        $this->skill = $skill;

        return $this;
    }

    public function getMovie(): ?string
    {
        return $this->movie;
    }

    public function setMovie(?string $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getMusic(): ?string
    {
        return $this->music;
    }

    public function setMusic(?string $music): self
    {
        $this->music = $music;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;
        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postal_code;
    }

    public function setPostalCode(?int $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(?bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    public function getPackage(): ?string
    {
        return $this->package;
    }

    public function setPackage(?string $package): self
    {
        $this->package = $package;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getSocialEntity(): ?string
    {
        return $this->social_entity;
    }

    public function setSocialEntity(?string $social_entity): self
    {
        $this->social_entity = $social_entity;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    public function getAvailabe(): ?bool
    {
        return $this->availabe;
    }

    public function setAvailabe(bool $availabe): self
    {
        $this->availabe = $availabe;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|Chat[]
     */
    public function getChatsFrom(): Collection
    {
        return $this->chats_from;
    }

    public function addChatFrom(Chat $chat_from): self
    {
        if (!$this->chats_from->contains($chat_from)) {
            $this->chats_from[] = $chat_from;
            $chat_from->setSendFrom($this);
        }

        return $this;
    }

    public function removeChatFrom(Chat $chat_from): self
    {
        if ($this->chats_from->removeElement($chat_from)) {
            // set the owning side to null (unless already changed)
            if ($chat_from->getSendFrom() === $this) {
                $chat_from->setSendFrom(null);
            }
        }

        return $this;
    }


    // TO

    /**
     * @return Collection|Chat[]
     */
    public function getChatsTo(): Collection
    {
        return $this->chats_to;
    }

    public function addChatTo(Chat $chat_to): self
    {
        if (!$this->chats_to->contains($chat_to)) {
            $this->chats_to[] = $chat_to;
            $chat_to->setSendTo($this);
        }

        return $this;
    }

    public function removeChatTo(Chat $chat_to): self
    {
        if ($this->chats_to->removeElement($chat_to)) {
            // set the owning side to null (unless already changed)
            if ($chat_to->getSendTo() === $this) {
                $chat_to->setSendTo(null);
            }
        }

        return $this;
    }

}
