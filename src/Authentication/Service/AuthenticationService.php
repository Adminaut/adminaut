<?php

namespace Adminaut\Authentication\Service;

use Adminaut\Authentication\Adapter\AuthAdapter;
use Adminaut\Authentication\Storage\AuthStorage;
use Adminaut\Entity\UserEntityInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

/**
 * Class AuthenticationService
 * @package Adminaut\Authentication\Service
 */
class AuthenticationService implements AuthenticationServiceInterface, EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    const EVENT_AUTHENTICATION_SUCCESS = 'adminaut.authentication.success';
    const EVENT_AUTHENTICATION_CLEAR = 'adminaut.authentication.clear';

    /**
     * @var AuthAdapter
     */
    private $adapter;

    /**
     * @var AuthStorage
     */
    private $storage;

    //-------------------------------------------------------------------------

    /**
     * AuthenticationService constructor.
     * @param AuthAdapter $adapter
     * @param AuthStorage $storage
     */
    public function __construct(AuthAdapter $adapter, AuthStorage $storage)
    {
        $this->adapter = $adapter;
        $this->storage = $storage;
    }

    //-------------------------------------------------------------------------

    /**
     * @return AuthAdapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param AuthAdapter $adapter
     */
    public function setAdapter(AuthAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return AuthStorage
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @param AuthStorage $storage
     */
    public function setStorage(AuthStorage $storage)
    {
        $this->storage = $storage;
    }

    //-------------------------------------------------------------------------

    /**
     * Authenticates and provides an authentication result
     * @param string $email
     * @param string $password
     * @return Result
     */
    public function authenticate($email = null, $password = null)
    {
        $result = $this->adapter->authenticate($email, $password);

        if (
            $result->getCode() === Result::SUCCESS
            && $result->getIdentity() instanceof UserEntityInterface
        ) {
            $this->getEventManager()->trigger(self::EVENT_AUTHENTICATION_SUCCESS, $this, [
                'identity' => $result->getIdentity(),
            ]);
            $this->storage->write($result->getIdentity());
        }

        return $result;
    }

    /**
     * Returns true if and only if an identity is available
     *
     * @return bool
     */
    public function hasIdentity()
    {
        if (true === $this->storage->isEmpty()) {
            return false;
        }
        return true;
    }

    /**
     * Returns the authenticated identity or null if no identity is available
     *
     * @return UserEntityInterface|null
     */
    public function getIdentity()
    {
        if (false === $this->storage->isEmpty()) {
            return $this->storage->read();
        }

        return null;
    }

    /**
     * Clears the identity
     *
     * @return void
     */
    public function clearIdentity()
    {
        if (false === $this->storage->isEmpty()) {
            $this->getEventManager()->trigger(self::EVENT_AUTHENTICATION_CLEAR, $this, [
                'identity' => $this->getIdentity(),
            ]);
            $this->storage->clear();
        }
    }

    /**
     * @param string $password
     * @return Result
     */
    public function changePassword($password)
    {
        return $this->getAdapter()->changePassword($this->getIdentity(), $password);
    }
}
