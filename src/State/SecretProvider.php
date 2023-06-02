<?php

namespace App\State;

use App\Entity\Secret;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Metadata\CollectionOperationInterface;
use App\Repository\SecretRepository;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;

class SecretProvider implements ProviderInterface
{
    
    public function __construct(private readonly SecretRepository $secretRepository, private Security $security){}

    /**
    * {@inheritDoc}
    */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();
        if($user->uuid != $uriVariables['uuid']) {
            throw new Exception('Not Allowed to do That !', 401);
        }
        if ($operation instanceof CollectionOperationInterface) {
            $secrets = $this->secretRepository->findByUuid($uriVariables['uuid']);
            return $secrets;
        }
        return new Secret($uriVariables['uuid']);
    }
}
