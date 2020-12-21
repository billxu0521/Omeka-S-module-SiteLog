<?php
namespace SiteLog;

if (!class_exists(\Generic\AbstractModule::class)) {
    require file_exists(dirname(__DIR__) . '/Generic/AbstractModule.php')
        ? dirname(__DIR__) . '/Generic/AbstractModule.php'
        : __DIR__ . '/src/Generic/AbstractModule.php';
}

use Generic\AbstractModule;
use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\Mvc\MvcEvent;

/**
 * Search History.
 *
 * @copyright Daniel Berthereau 2019-2020
 * @license http://www.cecill.info/licences/Licence_CeCILL_V2.1-en.txt
 */
class Module extends AbstractModule
{
    const NAMESPACE = __NAMESPACE__;

    public function onBootstrap(MvcEvent $event)
    {
        parent::onBootstrap($event);

        $acl = $this->getServiceLocator()->get('Omeka\Acl');
        $roles = $acl->getRoles();
        $acl
            ->allow(
                null,
                [   
                    Entity\SiteLog::class,
                    Api\Adapter\SiteLogAdapter::class,
                    'SiteLog\Controller\Site\SiteLog',
                    
                ]
        );
        
    }
   
    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        $sharedEventManager->attach(
            '*',
            'view.layout',
            [$this, 'handleViewLayout']
        );
       
    }

    public function handleViewLayout(Event $event): void
    {
        $view = $event->getTarget();
        if (!$view->status()->isSiteRequest()) {
            return;
        }
        
        //$site = $view->currentSite();
        $services = $this->getServiceLocator();
        $site = $services->get('ControllerPluginManager')->get('currentSite');
        $assetUrl = $view->plugin('assetUrl');
        $url = $site()->url();
        $siteurl = 'var siteurl = ' .json_encode($url). ';';

        $view->headScript()
           ->appendScript($siteurl)
           ->appendFile($assetUrl('js/sitelog_lib.js', 'SiteLog'), 'text/javascript', ['defer' => 'defer','siteurl' => $siteurl])
           ->appendFile($assetUrl('js/sitelog_script.js', 'SiteLog'), 'text/javascript', ['defer' => 'defer']);
           
    }

    public function handleGuestWidgets(Event $event)
    {
        
    }
}
