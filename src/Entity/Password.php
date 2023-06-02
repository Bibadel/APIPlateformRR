<?php

namespace App\Entity;

use App\Repository\PasswordRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Link;
use App\State\PasswordProvider;

#[ORM\Entity(repositoryClass: PasswordRepository::class)]
#[ApiResource(
    operations:[
        new GetCollection(
            uriTemplate: "/{uuid}/passwords",
            uriVariables: [
                "uuid" => new Link(
                    toProperty: 'user',
                    fromClass: User::class
                )
            ],
            provider: PasswordProvider::class,
            paginationEnabled:false),
        new Post(
            securityPostDenormalize: "object.user == user", 
            securityPostDenormalizeMessage: 'Not allowed to do that !'
        ),
        new Delete(
            securityPostDenormalize: "object.user == user", 
            securityPostDenormalizeMessage: 'Not allowed to do that !')
    ],
        normalizationContext:['groups' => ['read:Password']],
        denormalizationContext:['groups' => ['write:Password']]
)]
class Password
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:Password'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['read:Password' , 'write:Password'])]
    private ?bool $toDelete = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read:Password' , 'write:Password'])]
    private ?string $destination = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:Password' , 'write:Password'])]
    private ?string $login = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:Password' , 'write:Password'])]
    private ?string $pass = null;

    #[ORM\ManyToOne(inversedBy: 'passwords')]
    #[ORM\JoinColumn(referencedColumnName:'uuid', name:'user_uuid', nullable: false)]
    #[Groups(['read:Password' , 'write:Password'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPass(): ?string
    {
        return $this->pass;
    }

    public function setPass(string $pass): self
    {
        $this->pass = $pass;

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
}
