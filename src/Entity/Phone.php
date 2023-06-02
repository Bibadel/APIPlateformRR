<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use App\State\PhoneProvider;

#[ORM\Entity(repositoryClass: PhoneRepository::class)]
#[ApiResource(
    operations:[
        new GetCollection(
            uriTemplate: "/{uuid}/phones",
            uriVariables: [
                "uuid" => new Link(
                    toProperty: 'user',
                    fromClass: User::class
                )
            ],
            provider: PhoneProvider::class,
            paginationEnabled:false),
        new Post(
            securityPostDenormalize: "object.user == user", 
            securityPostDenormalizeMessage: 'Not allowed to do that !'
        ),
        new Delete(
            securityPostDenormalize: "object.user == user", 
            securityPostDenormalizeMessage: 'Not allowed to do that !'
        ),
        new Get(
            securityPostDenormalize: "object.user == user", 
            securityPostDenormalizeMessage: 'Not allowed to do that !'
        ),
        new Patch(
            securityPostDenormalize: "object.user == user", 
            securityPostDenormalizeMessage: 'Not allowed to do that !',
            uriTemplate: "/phones/{uuid}",
            uriVariables: [
                "uuid" => new Link(
                    toProperty: 'user',
                    fromClass: User::class
                )
            ],
        )
    ],
        normalizationContext:['groups' => ['read:Phone']],
        denormalizationContext:['groups' => ['write:Phone']]
)]
class Phone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:Phone'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read:Phone' , 'write:Phone'])]
    private ?string $pin = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read:Phone' , 'write:Phone'])]
    private ?string $lockSchema = null;

    #[ORM\ManyToOne(inversedBy: 'phones')]
    #[ORM\JoinColumn(referencedColumnName:'uuid', name:'user_uuid', nullable: false)]
    #[Groups(['read:Phone' , 'write:Phone'])]
    public ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPin(): ?string
    {
        return $this->pin;
    }

    public function setPin(?string $pin): self
    {
        $this->pin = $pin;

        return $this;
    }

    public function getLockSchema(): ?string
    {
        return $this->lockSchema;
    }

    public function setLockSchema(?string $lockSchema): self
    {
        $this->lockSchema = $lockSchema;

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
