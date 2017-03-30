<?php

namespace Adminaut\Controller;

use Adminaut\Service\AccessControlService;
use Doctrine\ORM\EntityManager;
use Adminaut\Controller\Plugin\UserAuthentication;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

/**
 * Class AdminautBaseController
 * @package Adminaut\Controller
 * @method UserAuthentication userAuthentication()
 */
class AdminautBaseController extends AbstractActionController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var AccessControlService
     */
    protected $acl;

    /**
     * @var array
     */
    protected $config;

    private $defaultAppearance = [
        'skin' => 'blue',
        'title' => 'Adminaut',
        'logo' => [
            'type' => 'image',
            'large' => 'adminaut/img/adminaut-logo.svg',
            'small' => 'adminaut/img/adminaut-logo-mini.svg',
        ],
        'footer' => ''
    ];

    public function __construct($config, $acl, $em)
    {
        $this->setConfig($config);
        $this->setAcl($acl);
        $this->setEntityManager($em);
    }

    /**
     * @param MvcEvent $e
     * @return $this
     */
    public function onDispatch(MvcEvent $e)
    {
        parent::onDispatch($e);
        if (!$this->userAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('adminaut/user/login');
        }
        $acl = $this->getAcl();
        $acl->setUser($this->userAuthentication()->getIdentity());

        $this->layout('layout/admin');
        $this->layout()->setVariable('acl', $acl);
        $appearance = array_merge($this->defaultAppearance, $this->config['adminaut']['appearance']);
        $this->layout()->setVariable('appearance', $appearance);
        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEntityManager($em)
    {
        $this->em = $em;
    }

    /**
     * @return AccessControlService
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @param AccessControlService $acl
     */
    public function setAcl($acl)
    {
        $this->acl = $acl;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param EventManagerInterface $events
     * @return $this
     */
    public function setEventManager(EventManagerInterface $events)
    {

        parent::setEventManager($events);
        /*
        $controller = $this;
        $events->attach('dispatch', function ($e) use ($controller) {
            $controller->layout('layout/admin');
            if ($controller->zfcUserAuthentication()->hasIdentity()) {
                var_dump($controller->zfcUserAuthentication()->getIdentity());
            }
        }, 100);
        */
        return $this;
    }
}