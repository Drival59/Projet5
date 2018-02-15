<?php

namespace HV\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="HV\UsersBundle\Repository\UsersRepository")
 * @UniqueEntity(fields="email", message="Cette adresse mail est déjà associé à un compte.")
 * @UniqueEntity(fields="login", message="Ce pseudo est déjà utilisé par un autre utilisateur.")
 */
class Users
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255)
     * @Assert\Length(min=2)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, unique=true)
     * @Assert\Length(min=8, minMessage="Le mot de passe doit faire au moins {{ limit }} caractères.")
     * @Assert\IdenticalTo(propertyPath="confirmPassword", message="Le mot de passe et sa confirmation ne sont pas identiques.")
     */
    private $password;

    /**
     * @var string
     * @Assert\Length(min=8, minMessage="Le mot de passe doit faire au moins {{ limit }} caractères.")
     * @Assert\IdenticalTo(propertyPath="password", message="Le mot de passe et sa confirmation ne sont pas identiques.")
     */
    private $confirmPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     * @Assert\File(
     *    mimeTypes={
     *        "image/png",
     *        "image/jpeg",
     *        "image/jpg",
     *        "image/bmp"
     *    }
     * )
     */
    private $avatar;

    /**
     * @var int
     *
     * @ORM\Column(name="rights", type="integer")
     */
    private $rights;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\IdenticalTo(propertyPath="confirmEmail", message="L'adresse email et sa confirmation ne sont pas identiques.")
     */
    private $email;

    /**
     * @var string
     * @Assert\IdenticalTo(propertyPath="email", message="L'adresse email et sa confirmation ne sont pas identiques.")
     */
    private $confirmEmail;

    public function __construct()
    {
      $this->setRights(0);
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return Users
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set rights
     *
     * @param integer $rights
     *
     * @return Users
     */
    public function setRights($rights)
    {
        $this->rights = $rights;

        return $this;
    }

    /**
     * Get rights
     *
     * @return int
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return Users
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set confirmPassword
     *
     * @param string $confirmPassword
     *
     * @return Users
     */
    public function setConfirmPassword($confirmPassword)
    {
        $this->confirmPassword = $confirmPassword;
        return $this;
    }

    /**
     * Get confirmPassword
     *
     * @return string
     */
    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }

    /**
     * Set confirmEmail
     *
     * @param string $confirmEmail
     *
     * @return Users
     */
    public function setConfirmEmail($confirmEmail)
    {
        $this->confirmEmail = $confirmEmail;
        return $this;
    }

    /**
     * Get confirmEmail
     *
     * @return string
     */
    public function getConfirmEmail()
    {
        return $this->confirmEmail;
    }
}
