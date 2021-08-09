<?php
namespace SiteLog;

if (!class_exists(\Generic\AbstractModule::class)) {
    require file_exists(dirname(__DIR__) . '/Generic/AbstractModule.php')
        ? dirname(__DIR__) . '/Generic/AbstractModule.php'
        : __DIR__ . '/src/Generic/AbstractModule.php';
}

use Generic\AbstractModule;
use Laminas\EventManager\Event;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\JsonModel;
use Laminas\View\View;
use Laminas\View\ViewEvent;
use Laminas\View\Renderer\PhpRenderer;


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

        $controllers = [
            'Omeka\Controller\Admin\Item',
            //'Omeka\Controller\Admin\ItemSet',
            'Omeka\Controller\Admin\Media',
        ];

        foreach ($controllers as $controller) {
            // Display 
            $sharedEventManager->attach(
                $controller,
                'view.show.sidebar',
                [$this, 'adminViewShowSidebar']
            );
        }

        /*
        $sharedEventManager->attach(
            View::class,
            ViewEvent::EVENT_RESPONSE,
            [$this, 'appendSiteLog']
        );       
        */
    }


    public function handleViewLayout(Event $event): void
    {
        $view = $event->getTarget();
        if (!$view->status()->isSiteRequest()) {
            
            
            return;
        }
        
        $services = $this->getServiceLocator();
        $site = $services->get('ControllerPluginManager')->get('currentSite');
        $assetUrl = $view->plugin('assetUrl');
        $url = $site()->url();
        $siteurl = 'var siteurl = ' .json_encode($url). ';';
        $view->headScript()
            ->appendScript($siteurl)
            ->appendFile($assetUrl('js/sitelog_lib.js', 'SiteLog'), 'text/javascript', ['defer' => 'defer']);
            //->appendFile($assetUrl('js/sitelog_script.js', 'SiteLog'), 'text/javascript', ['defer' => 'defer']);
    }

    public function appendSiteLog(ViewEvent $viewEvent): void
    {
        // In case of error or a internal redirection, there may be two calls.
        static $processed;
        if ($processed) {
            return;
        }
        $processed = true;

        $model = $viewEvent->getParam('model');
        if (is_object($model) && $model instanceof JsonModel) {
            $this->trackCall('json', $viewEvent);
            return;
        }

        $content = $viewEvent->getResponse()->getContent();

        // Quick hack to avoid a lot of checks for an event that always occurs.
        // Headers are not yet available, so the content type cannot be checked.
        // Note: The layout of the theme should start with this doctype, without
        // space or line break. This is not the case in the admin layout of
        // Omeka S 1.0.0, so a check is done.
        // The ltrim is required in case of a bad theme layout, and the substr
        // allows a quicker check because it avoids a trim on all the content.
        // if (substr($content, 0, 15) != '<!DOCTYPE html>') {
        $startContent = ltrim(substr((string) $content, 0, 30));
        if (strpos($startContent, '<!DOCTYPE html>') === 0) {
            $this->trackCall('html', $viewEvent);
        } elseif (strpos($startContent, '<?xml ') !== 0) {
            $this->trackCall('xml', $viewEvent);
        } elseif (json_decode($content) !== null) {
            $this->trackCall('json', $viewEvent);
        } else {
            $this->trackCall('undefined', $viewEvent);
        }
    }

    public function adminViewShowSidebar(Event $event): void
    {
        $view = $event->getTarget();

        $plugins = $view->getHelperPluginManager();
        $sitelogcount = $plugins->get('siteLogCounter');
        $sitelogcount = $sitelogcount();
        $resource = $view->vars()->resource;
        error_log(json_encode($resource->id()));

        echo '<div class="meta-group">
            <h4>SiteLogCount</h4>
        </div>';
    }
    
    /**
     * Track an html, an api, a json, an xml or an undefined response.
     *
     * @param string $type "html", "json", "xml", "undefined", or "error".
     * @param Event $event
     */
    protected function trackCall($type, Event $event): void
    {
       
        $services = $this->getServiceLocator();
        $serverUrl = $services->get('ViewHelperManager')->get('ServerUrl');
        $url = $serverUrl(true);

        $trackers = $services->get('Config')['sitelog']['trackers'];
        foreach ($trackers as $tracker) {
            $tracker = new $tracker();
            $tracker->setServiceLocator($services);
            $tracker->track($url, $type, $event);
        }
        
    }
    
    
    public function handleGuestWidgets(Event $event)
    {
        
    }
        
}
