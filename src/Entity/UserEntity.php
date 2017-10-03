<?php

namespace Adminaut\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Class UserEntity
 * @package Adminaut\Entity
 * @ORM\Entity(repositoryClass="Adminaut\Repository\UserRepository")
 * @ORM\Table(name="adminaut_user")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\HasLifecycleCallbacks()
 */
class UserEntity implements UserEntityInterface
{
    use AdminautEntityTrait;

    /**
     * Constants
     */
    const STATUS_UNKNOWN = 0;
    const STATUS_NEW = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_LOCKED = 3;
    const STATUS_BANNED = 4;

    /**
     * @ORM\Column(type="string", length=128);
     * @Annotation\Options({"label":"Name", "listed":true});
     * @Annotation\Flags({"priority":5});
     * @Annotation\Type("Adminaut\Datatype\Text");
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=128, unique=true);
     * @Annotation\Options({"label":"Email", "listed":true, "primary":true});
     * @Annotation\Flags({"priority":10});
     * @Annotation\Type("Zend\Form\Element\Email");
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=128);
     * @Annotation\Options({"label":"Password"});
     * @Annotation\Flags({"priority":15});
     * @Annotation\Type("Zend\Form\Element\Password");
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=128);
     * @Annotation\Options({"label":"Role", "empty_option":"Select role", "listed":true});
     * @Annotation\Flags({"priority":20});
     * @Annotation\Type("Zend\Form\Element\Select");
     * @var string
     */
    protected $role;

    /**
     * @ORM\Column(type="string", length=128, options={"default":"en"});
     * @Annotation\Options({"label":"Language", "availableLanguages":{"cs", "sk", "en", "de"}, "listed":true});
     * @Annotation\Flags({"priority":19});
     * @Annotation\Type("Adminaut\Datatype\Language");
     * @var string
     */
    protected $language;

    /**
     * @ORM\Column(type="integer", name="status", options={"default":0});
     * @Annotation\Exclude();
     * @var int
     */
    protected $status;

    /**
     * Inverse side.
     * @ORM\OneToMany(targetEntity="UserAccessTokenEntity", mappedBy="user");
     * @Annotation\Exclude();
     * @var Collection
     */
    protected $accessTokens;

    /**
     * Inverse side.
     * @ORM\OneToMany(targetEntity="UserLoginEntity", mappedBy="user");
     * @Annotation\Exclude();
     * @var Collection
     */
    protected $logins;

    //-------------------------------------------------------------------------

    /**
     * UserEntity constructor.
     */
    public function __construct()
    {
        $this->language = 'en';
        $this->status = $this::STATUS_NEW;
        $this->accessTokens = new ArrayCollection();
        $this->logins = new ArrayCollection();
    }

    //-------------------------------------------------------------------------

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getGravatarHash()
    {
        return md5(strtolower(trim($this->getEmail())));
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'language' => $this->language,
            'status' => $this->status,
            'active' => $this->active,
        ];
    }
}