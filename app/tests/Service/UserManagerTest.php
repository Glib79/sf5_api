<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{
    /**
     * SCENARIO: receiving User object
     * EXPECTED: return proper JWT token
     */
    public function testGenerateJWTToken()
    {
        $encoder = $this->createMock(UserPasswordEncoderInterface::class);
        
        $encoder->expects($this->never())
            ->method($this->anything());
        
        $user = new User('test@test.com');
        
        $JWTManager = $this->createMock(JWTTokenManagerInterface::class);
        
        $JWTManager->expects($this->once())
            ->method('create')
            ->with($user)
            ->willReturn('token_string');
            
        $userRepository = $this->createMock(UserRepository::class);
        
        $userRepository->expects($this->never())
            ->method($this->anything());
        
        $userManager = new UserManager($encoder, $JWTManager, $userRepository);
        
        $result = $userManager->generateJWTToken($user);
        
        $this->assertSame('token_string', $result);
    }
    
    /**
     * SCENARIO: receiving email and password
     * EXPECTED: create and save User to database 
     */
    public function testCreateUser()
    {
        $userWithoutPass = new User('test@test.com');
        
        $encoder = $this->createMock(UserPasswordEncoderInterface::class);
        
        $encoder->expects($this->once())
            ->method('encodePassword')
            ->with($userWithoutPass, 'password_string')
            ->willReturn('encoded_password_string');
            
        $JWTManager = $this->createMock(JWTTokenManagerInterface::class);
        
        $JWTManager->expects($this->never())
            ->method($this->anything());

        $userWithPass = new User('test@test.com');    
        $userWithPass->setPassword('encoded_password_string');
            
        $userRepository = $this->createMock(UserRepository::class);
        
        $userRepository->expects($this->once())
            ->method('create')
            ->with($userWithPass)
            ->willReturn(true);
        
        $userManager = new UserManager($encoder, $JWTManager, $userRepository);
        
        $result = $userManager->createUser('test@test.com', 'password_string');
        
        $this->assertTrue($result);
    }
}