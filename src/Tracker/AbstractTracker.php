<?php 
namespace SiteLog\Tracker;

use Zend\EventManager\Event;
use Zend\Http\PhpEnvironment\RemoteAddress;
use Zend\ServiceManager\ServiceLocatorInterface;
use Omeka\Stdlib\Message;

abstract class AbstractTracker implements TrackerInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $services;

    public function setServiceLocator(ServiceLocatorInterface $services): void
    {
        $this->services = $services;
    }

    /**
     * Get service locator.
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->services;
    }

    public function track($url, $type, Event $event): void
    {
        $this->trackInlineScript($url, $type, $event);
        
    }

    protected function trackInlineScript($url, $type, Event $event): void
    {
       
        $services = $this->getServiceLocator();
        $assetUrl = $services->get('ViewHelperManager')->get('assetUrl');        
        $inlineScript = '<script type="text/javascript" src="'.$assetUrl('js/sitelog_script.js', 'SiteLog').'"></script>';
        $response = $event->getResponse();
        $content = $response->getContent();
        $endTagBody = strripos((string) $content, '</body>', -7);
        if (empty($endTagBody)) {
            $this->trackError($url, $type, $event);
            return;
        }

        $content = substr_replace($content, $inlineScript, $endTagBody, 0);
        $response->setContent($content);
    }

    protected function trackNotInlineScript($url, $type, Event $event): void
    {
    }

    protected function trackError($url, $type, Event $event): void
    {
        $logger = $this->services->get('Omeka\Logger');
        $logger->err(new Message('Error in content "%s" from url %s (referrer: %s; user agent: %s; user #%d; ip %s).', // @translate
            $type, $url, $this->getUrlReferrer(), $this->getUserAgent(), $this->getUserId(), $this->getClientIp()));
    }

    /**
     * Get the url referrer.
     *
     * @return string
     */
    protected function getUrlReferrer()
    {
        return @$_SERVER['HTTP_REFERER'];
    }

    /**
     * Get the ip of the client.
     *
     * @return string
     */
    protected function getClientIp()
    {
        $ip = (new RemoteAddress())->getIpAddress();
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
            || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
        ) {
            return $ip;
        }
        return '::';
    }

    /**
     * Get the user agent.
     *
     * @return string
     */
    protected function getUserAgent()
    {
        return @$_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Get the user id.
     *
     * @return int
     */
    protected function getUserId()
    {
        $services = $this->getServiceLocator();
        $identity = $services->get('ViewHelperManager')->get('Identity');
        $user = $identity();
        return $user ? $user->getId() : 0;
    }
}
