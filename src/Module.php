<?php

namespace Adminaut;

use Adminaut\Form\Annotation\FormAnnotationsListener;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 * @package Adminaut
 */
class Module implements ConfigProviderInterface
{

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface|MvcEvent $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var Application $app */
        $app = $e->getApplication();

        $serviceManager = $app->getServiceManager();

        FormAnnotationsListener::setServiceManager($serviceManager);
    }

    /**
     * @param MvcEvent $e
     */
    function onDispatchError(MvcEvent $e)
    {
        $viewModel = $e->getViewModel();
        $viewModel->setTemplate('layout/admin-blank');
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return [
            'controller_plugins' => include __DIR__ . '/../config/controller_plugins.php',
            'controllers'        => include __DIR__ . '/../config/controllers.php',
            'dependencies'       => include __DIR__ . '/../config/dependencies.php',
            'doctrine'           => include __DIR__ . '/../config/doctrine.php',
            'form_elements'      => include __DIR__ . '/../config/form_elements.php',
            'router'             => include __DIR__ . '/../config/router.php',
            'service_manager'    => include __DIR__ . '/../config/service_manager.php',
            'view_helpers'       => include __DIR__ . '/../config/view_helpers.php',
            'view_manager'       => include __DIR__ . '/../config/view_manager.php',
            'translator'         => include __DIR__ . '/../config/translator.php'
        ];
    }
}
