<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\UserRepository;
use DateTime;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserTokenProvider implements ProviderInterface
{
    
    public function __construct(private Security $security, private JWTTokenManagerInterface $JWTManager, private readonly UserRepository $userRepository){}

    /**
    * {@inheritDoc}
    */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->userRepository->findByUuid($uriVariables['uuid']);
        $date_now = new DateTime("now");
        $UnlockDate = new DateTime($user[0]->unlockDate);
        
        if($UnlockDate <= $date_now) {
            return new JsonResponse(['status' => "unlocked"]);
        }else {
            throw new Exception('Not allowed to do that !', 401);
        }
    }
}
