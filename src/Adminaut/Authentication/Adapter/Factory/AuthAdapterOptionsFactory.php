<?php

namespace Adminaut\Authentication\Adapter\Factory;

use Adminaut\Authentication\Adapter\AuthAdapterOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthAdapterOptionsFactory
 * @package Adminaut\Authentication\Adapter\Factory
 */
class AuthAdapterOptionsFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthAdapterOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var array $config */
        $config = $serviceLocator->get('Config');

        if (
            isset($config['adminaut'][AuthAdapterOptions::CONFIG_KEY])
            && is_array($config['adminaut'][AuthAdapterOptions::CONFIG_KEY])
        ) {
            return new AuthAdapterOptions($config['adminaut'][AuthAdapterOptions::CONFIG_KEY]);
        }

        return new AuthAdapterOptions();
    }
}
