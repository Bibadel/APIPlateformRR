<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Metadata\CollectionOperationInterface;
use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;

class PhoneProvider implements ProviderInterface
{
    
    public function __construct(private readonly PhoneRepository $phoneRepository, private Security $security){}

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
            $phones = $this->phoneRepository->findByUuid($uriVariables['uuid']);
            return $phones;
        }
        return new Phone($uriVariables['uuid']);
    }
}
