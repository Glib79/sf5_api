<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManager
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $ecncoder;
    
    /**
     * @var JWTTokenManagerInterface
     */
    private $JWTManager;
    
    /**
     * @var UserRepository
     */
    private $userRepository;
    
    /**
     * UserManager constructor
     * @param UserPasswordEncoderInterface $encoder
     * @param JWTTokenManagerInterface $JWTManager
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserPasswordEncoderInterface $encoder,
        JWTTokenManagerInterface $JWTManager, 
        UserRepository $userRepository
    )
    {
        $this->encoder = $encoder;
        $this->JWTManager = $JWTManager;
        $this->userRepository = $userRepository;
    }
    
    /**
     * Generate JWT token based on User
     * @param UserInterface $user
     * @return string JWT token
     */
    public function generateJWTToken(UserInterface $user): string
    {
        return $this->JWTManager->create($user);
    }
    
    /**
     * Create User
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function createUser(string $email, string $password): bool
    {
        $user = new User($email);
        $user->setPassword($this->encoder->encodePassword($user, $password));

        return $this->userRepository->create($user);
    }
}