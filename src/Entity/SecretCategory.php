<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use App\Repository\SecretCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Link;
use App\State\SecretCategoryProvider;

#[ORM\Entity(repositoryClass: SecretCategoryRepository::class)]
#[ApiResource(
    operations:[
        new GetCollection(
            uriTemplate: "/{uuid}/secret_categories",
            uriVariables: [
            "uuid" => new Link(
                toProperty: 'user',
                fromClass: User::class
            )
        ],
        provider: SecretCategoryProvider::class,
        paginationEnabled:false),
        new Post(
            securityPostDenormalize: "object.user == user", 
            securityPostDenormalizeMessage: 'Not allowed to do that !'
        ),
        new Delete(
            securityPostDenormalize: "object.user == user", 
            securityPostDenormalizeMessage: 'Not allowed to do that !'
        )
        ],
        normalizationContext:['groups' => ['read:Category']],
        denormalizationContext:['groups' => ['write:Category']]
)]
class SecretCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:Category', 'read:Secret'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:Category', 'read:Secret', 'write:Category'])]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy:'secretCategories')]
    #[ORM\JoinColumn(referencedColumnName:'uuid', name:'user_uuid', nullable: false)]
    #[Groups(['read:Category', 'write:Category'])]
    public ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Secret::class)]
    private Collection $secrets;

    public function __construct()
    {
        $this->secrets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $secret->setCategory($this->id);
        }

        return $this;
    }

    public function removeSecret(Secret $secret): self
    {
        if ($this->secrets->removeElement($secret)) {
            // set the owning side to null (unless already changed)
            if ($secret->getCategory() === $this) {
                $secret->setCategory(null);
            }
        }

        return $this;
    }
}
