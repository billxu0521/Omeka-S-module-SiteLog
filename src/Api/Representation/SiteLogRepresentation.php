<?php

namespace SiteLog\Api\Representation;

use Omeka\Api\Representation\AbstractEntityRepresentation;

class SiteLogRepresentation extends AbstractEntityRepresentation
{
    public function getControllerName()
    {
        return \SiteLog\Controller\Admin\SiteLogController::class;
    }

    public function getJsonLdType()
    {
        return 'o:SiteLog';
    }

    public function getJsonLd()
    {
        $entity = $this->resource;
        
        $created = [
            '@value' => $this->getDateTime($this->created()),
            '@type' => 'http://www.w3.org/2001/XMLSchema#dateTime',
        ];
        
        return [
            'o:id' => $this->id(),
            'o:user_ip' => $this->userip(),
            'o:reference' => $this->reference(),
            'o:item_id' => $this->item(),
            'o:site_id' => $this->site(),
            'o:context' => $this->context(),
            'o:created' => $created,
        ];
    }
    
    /**
     * @return \Omeka\Api\Representation\UserRepresentation
     */
    public function id()
    {
        return $this->resource->getId();
    }
    
    /**
     * @return \Omeka\Api\Representation\UserRepresentation
     */
    public function user()
    {
        $user = $this->resource->getUser();
        return $this->getAdapter('users')->getRepresentation($user);
    }
    
    /**
     * @return \Omeka\Api\Representation\UserRepresentation
     */
    public function userip()
    {
        return $this->resource->getUserip();
    }

    /**
     * @return \Omeka\Api\Representation\UserRepresentation
     */
    public function context()
    {
        return $this->resource->getContext();
    }
    
    /**
     * @return \Omeka\Api\Representation\SiteRepresentation|null
     */
    public function site()
    {
        $site = $this->resource->getSite();
        return $site
            ? $this->getAdapter('sites')->getRepresentation($site)
            : null;
    }
    
    /**
     * @return \Omeka\Api\Representation\ItemRepresentation|null
     */
    public function item()
    {
        $item = $this->resource->getItem();
        return $item;
    }
  
    /**
     * @return \DateTime
     */
    public function created()
    {
        return $this->resource->getCreated();
    }

    /**
     * Check if a module is active.
     *
     * @param string $moduleClass
     * @return bool
     */
protected function isModuleActive($moduleClass)
{
        $services = $this->getServiceLocator();
        /** @var \Omeka\Module\Manager $moduleManager */
        $moduleManager = $services->get('Omeka\ModuleManager');
        $module = $moduleManager->getModule($moduleClass);
        return $module
            && $module->getState() === \Omeka\Module\Manager::STATE_ACTIVE;
    }
}
