<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use App\Repository\SecretRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Post;
use App\State\SecretProvider;
use ApiPlatform\Metadata\Link;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: SecretRepository::class)]
#[ApiResource(
    operations:[
        new GetCollection(
            uriTemplate: "/{uuid}/secrets",
            uriVariables: [
                "uuid" => new Link(
                    toProperty: 'user',
                    fromClass: User::class
                )
            ],
            provider: SecretProvider::class,
            paginationEnabled:false),
        new Post(
            securityPostDenormalize: "object.user == user", 
            securityPostDenormalizeMessage: 'Not allowed to do that !'
        ),
        new Delete(
            securityPostDenormalize: "object.user == user", 
            securityPostDenormalizeMessage: 'Not allowed to do that !')
    ],
        normalizationContext:['groups' => ['read:Secret']],
        denormalizationContext:['groups' => ['write:Secret']]
)]
class Secret
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    #[Groups(['read:Secret' , 'write:Secret'])]
    private ?int $id = null;

    #[ORM\Column]
    #[ApiProperty(description:'À supprimer')]
    #[Groups(['read:Secret', 'write:Secret'])]
    private ?bool $toDelete = false;

    #[ORM\Column]
    #[ApiProperty(description:'Date de création')]
    #[Groups(['read:Secret'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: Types::TEXT)]
    #[ApiProperty(description:'Titre du secret')]
    #[Groups(['read:Secret', 'write:Secret'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[ApiProperty(description:'Contenu du secret')]
    #[Groups(['read:Secret', 'write:Secret'])]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy:'secrets')]
    #[ORM\JoinColumn(referencedColumnName:'uuid', name:'user_uuid', nullable: false)]
    #[MaxDepth(1)]
    #[Groups(['read:Secret', 'write:Secret'])]
    public ?User $user = null;

    #[ORM\ManyToOne(targetEntity: SecretCategory::class, inversedBy:'secrets')]
    #[ORM\JoinColumn(referencedColumnName:'id',name:'category_id', nullable: false)]
    #[MaxDepth(1)]
    #[Groups(['read:Secret', 'write:Secret'])]
    private ?SecretCategory $category = null;

    public function __construct() {
        $this->createdAt = new \DateTime();
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

    public function isToDelete(): ?bool
    {
        return $this->toDelete;
    }

    public function setToDelete(bool $toDelete): self
    {
        $this->toDelete = $toDelete;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getCategory(): ?SecretCategory
    {
        return $this->category;
    }

    public function setCategory(?SecretCategory $category): self
    {
        $this->category = $category;

        return $this;
    }
}
