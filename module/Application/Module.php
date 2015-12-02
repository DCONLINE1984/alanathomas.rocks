<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class Module
{
    public function getServiceConfig()
    {    
         return array(
            'factories' => array(
                'Application\Service\BlogComment' => function ($sm) {
                    $service = new \Application\Service\BlogComment($sm);
                    $service->setDb($sm->get('Zend\Db\Adapter\Adapter'));
                    $service->setEncoder(new \Application\Service\Encoder\BlogComment());
                    $service->setModel(new \Application\Model\BlogComment());
                    return $service;
                },
                'Application\Service\BlogPost' => function ($sm) {
                    $service = new \Application\Service\BlogPost($sm);
                    $service->setDb($sm->get('Zend\Db\Adapter\Adapter'));
                    $service->setEncoder(new \Application\Service\Encoder\BlogPost());
                    $service->setModel(new \Application\Model\BlogPost());
                    return $service;
                },
                'Application\Service\Chess' => function ($sm) {
                    $service = new \Application\Service\Chess($sm);
                    $service->setDb($sm->get('Zend\Db\Adapter\Adapter'));
                    $service->setEncoder(new \Application\Service\Encoder\Chess());
                    $service->setModel(new \Application\Model\ChessGame());
                    return $service;
                },
                'Application\Event\MyAuthStorage' => function($sm){
                    return new \Application\Event\MyAuthStorage('deanclow');  
                },
                'AuthService' => function($sm) {
                    //My assumption, you've alredy set dbAdapter
                    //and has users table with columns : user_name and pass_word
                    //that password hashed with md5
                    $dbAdapter           = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter, 
                                              'users','username','password', 'SHA1(?)');
                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                            $authService->setStorage($sm->get('Application\Event\MyAuthStorage'));
                    return $authService;
                },
            )
        );   
    }
    
    public function onBootstrap(MvcEvent $e)
    {
        $this -> initAcl($e);
        $e -> getApplication() -> getEventManager() -> attach('route', array($this, 'checkAcl'));

        //set a custom session config
        $this->initSession(array(
            'remember_me_seconds' => 180,
            'use_cookies' => true,
            'cookie_httponly' => true,
            'save_path' => $_SERVER['DOCUMENT_ROOT'] . '/../data/session'
        ));
        $eventManager = $e->getApplication()->getEventManager();
        $sharedEvents = $eventManager->getSharedManager();
        //when we start to add other modules, we can add the different layouts below
        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
            /* @var $e \Zend\Mvc\MvcEvent */
            // fired when an ActionController under the namespace is dispatched.
            $controller = $e->getTarget();
            $routeMatch = $e->getRouteMatch();
            /* @var $routeMatch \Zend\Mvc\Router\RouteMatch */
            $routeName = $routeMatch->getMatchedRouteName();
            $controller->layout('layout/layout');
        }, 100);
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function initSession($config)
    {
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config);
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->start();
        Container::setDefaultManager($sessionManager);
    }
    
    public function initAcl(MvcEvent $e) 
    {
        $acl = new \Zend\Permissions\Acl\Acl();
        $roles = include __DIR__ . '/config/module.acl.roles.php';
        $allResources = array();
        foreach ($roles as $role => $resources) {
            $role = new \Zend\Permissions\Acl\Role\GenericRole($role);
            $acl -> addRole($role);
            $allResources = array_merge($resources, $allResources);
            //adding resources
            foreach ($resources as $resource) {
                if(!$acl ->hasResource($resource))
                    $acl -> addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource));
            }
            //adding restrictions
            foreach ($allResources as $resource) {
                $acl -> allow($role, $resource);
            }
        }
        //testing
        //var_dump($acl->isAllowed('guest','home'));
        //true
        //setting to view
        $e -> getViewModel() -> acl = $acl;
    }

    public function checkAcl(MvcEvent $e) {
        $params = $e->getRouteMatch()->getParams();
        $route = $e -> getRouteMatch() -> getMatchedRouteName()."/".$params['action'];
        //you set your role
        $userRole = 'guest';
        $this->app = $e->getApplication();
        if($this->app->getServiceManager()->get("AuthService")->hasIdentity()){
            $userRole = $this->app->getServiceManager()->get("AuthService")->getIdentity(); //change the identity, yey!!
        }
        if (!$e->getViewModel()->acl->hasResource($route) || !$e->getViewModel()->acl->isAllowed($userRole, $route)) {
            $response = $e -> getResponse();
            //location to page or what ever
            $response -> getHeaders() -> addHeaderLine('Location', $e -> getRequest() -> getBaseUrl() . '/404');
            $response -> setStatusCode(404);
        }
    }
}
