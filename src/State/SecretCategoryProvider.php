<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Metadata\CollectionOperationInterface;
use App\Entity\SecretCategory;
use App\Repository\SecretCategoryRepository;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;

class SecretCategoryProvider implements ProviderInterface
{
    
    public function __construct(private readonly SecretCategoryRepository $secretCategoryRepository, private Security $security){}

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
            $categories = $this->secretCategoryRepository->findByUuid($uriVariables['uuid']);
            return $categories;
        }
        return new SecretCategory($uriVariables['uuid']);
    }
}
