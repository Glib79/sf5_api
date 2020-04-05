<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * This entity is only for authentication - it's needed by UserInterface
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * Email is also an username
     * @ORM\Column(type="string", length=45, unique=true)
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;
    
    /**
     * @ORM\Column(type="json")
     */
    private $roles;
    
    /**
     * User constructor.
     * @param $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * User stuff
     */
    
    /**
     * Erease Credentials
     */
    public function eraseCredentials()
    {
    }
    
    /**
     * Get Salt
     * We don't need salt because bcrypt don't need salt (it has build in salt stuff)
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }
    
    /**
     * Get Username - Email is also username
     * @return string
     */
    public function getUsername(): string
    {
        return $this->email;
    }
    
    /**
     * Getters and setters
     */
    
    /**
     * Get Email - Email is also username
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    
    /**
     * Set Email - Email is also username
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        
        return $this;
    }
    
    /**
     * Get Password
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    /**
     * Set password
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        
        return $this;
    }
    
    /**
     * Get roles
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
    
    /**
     * Set roles
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        
        return $this;
    }
}