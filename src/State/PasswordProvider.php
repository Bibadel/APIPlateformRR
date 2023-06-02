<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Metadata\CollectionOperationInterface;
use App\Entity\Password;
use App\Repository\PasswordRepository;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;

class PasswordProvider implements ProviderInterface
{
    
    public function __construct(private readonly PasswordRepository $passwordRepository, private Security $security){}

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
            $passwords = $this->passwordRepository->findByUuid($uriVariables['uuid']);
            return $passwords;
        }
        return new Password($uriVariables['uuid']);
    }
}
