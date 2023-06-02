<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\State\UserPasswordHasher;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\State\UserTokenProvider;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Post(processor: UserPasswordHasher::class, denormalizationContext: ['groups' => ['write:Post:User']]),
        new Get(
            uriTemplate: "/{uuid}/user",
            securityPostDenormalize: "object.uuid == uuid", 
            securityPostDenormalizeMessage: 'Not allowed to do that !',
            normalizationContext:['groups' => ['read:User']]
        ),
        new Get(
            uriTemplate: "/get_unlock_token/{uuid}",
            provider: UserTokenProvider::class,
            normalizationContext:['groups' => ['read:User']]
        ),
        new Patch(
            uriTemplate: "/{uuid}/user",
            securityPostDenormalize: "object.uuid == uuid", 
            securityPostDenormalizeMessage: 'Not allowed to do that !',
            denormalizationContext:['groups' => ['write:Patch:User']]
        ),
        new Delete(
            uriTemplate: "/{uuid}/user",
            securityPostDenormalize: "object.uuid == uuid", 
            securityPostDenormalizeMessage: 'Not allowed to do that !'
        )
        ]
        
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['read:User', 'write:Post:User', 'read:Secret', 'read:Category', 'read:Phone', 'read:Password'])]
    #[ApiProperty(identifier:true)]
    public ?string $uuid = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(type: Types::TEXT, nullable:true)]
    #[Groups(['read:User', 'write:Patch:User'])]
    public ?string $unlockDate = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;
   
    #[Groups(['write:Post:User'])]
    private ?string $plainPassword = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Phone::class, orphanRemoval: true, cascade:["remove"])]
    private Collection $phones;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Password::class, orphanRemoval: true, cascade:["remove"])]
    private Collection $passwords;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Secret::class, orphanRemoval: true, cascade:["remove"])]
    private Collection $secrets;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SecretCategory::class, orphanRemoval: true, cascade:["remove"])]
    private Collection $secretCategories;

    public function __construct()
    {
        $this->phones = new ArrayCollection();
        $this->passwords = new ArrayCollection();
        $this->secrets = new ArrayCollection();
        $this->secretCategories = new ArrayCollection();
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->uuid;
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

    public function getUnlockTime(): ?string
    {
        return $this->unlockDate;
    }

    public function setUnlockTime(string $unlockDate): self
    {
        $this->unlockDate = $unlockDate;

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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
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
     * @return Collection<int, Phone>
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    public function addPhone(Phone $phone): self
    {
        if (!$this->phones->contains($phone)) {
            $this->phones->add($phone);
            $phone->setUser($this);
        }

        return $this;
    }

    public function removePhone(Phone $phone): self
    {
        if ($this->phones->removeElement($phone)) {
            // set the owning side to null (unless already changed)
            if ($phone->getUser() === $this) {
                $phone->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Password>
     */
    public function getPasswords(): Collection
    {
        return $this->passwords;
    }

    public function addPassword(Password $password): self
    {
        if (!$this->passwords->contains($password)) {
            $this->passwords->add($password);
            $password->setUser($this);
        }

        return $this;
    }

    public function removePassword(Password $password): self
    {
        if ($this->passwords->removeElement($password)) {
            // set the owning side to null (unless already changed)
            if ($password->getUser() === $this) {
                $password->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Secret>
     */
    public function getSecrets(): Collection
    {
        return $this->secrets;
    }

    public function addSecret(Secret $secret): self
    {
        if (!$this->secrets->contains($secret)) {
            $this->secrets->add($secret);
            $secret->setUser($this);
        }

        return $this;
    }

    public function removeSecret(Secret $secret): self
    {
        if ($this->secrets->removeElement($secret)) {
            // set the owning side to null (unless already changed)
            if ($secret->getUser() === $this) {
                $secret->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SecretCategory>
     */
    public function getSecretCategories(): Collection
    {
        return $this->secretCategories;
    }

    public function addSecretCategory(SecretCategory $secretCategory): self
    {
        if (!$this->secretCategories->contains($secretCategory)) {
            $this->secretCategories->add($secretCategory);
            $secretCategory->setUser($this);
        }

        return $this;
    }

    public function removeSecretCategory(SecretCategory $secretCategory): self
    {
        if ($this->secretCategories->removeElement($secretCategory)) {
            // set the owning side to null (unless already changed)
            if ($secretCategory->getUser() === $this) {
                $secretCategory->setUser(null);
            }
        }

        return $this;
    }
}
