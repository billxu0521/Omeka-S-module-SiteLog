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
            'o:resources_id' => $this->resourcesid(),
            'o:resources_type' => $this->resourcestype(),
            'o:site_id' => $this->site(),
            'o:page_slug' => $this->page(),
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
    
    public function site()
    {
        return  $this->resource->getSiteid();
    }
    
    public function page()
    {
        return  $this->resource->getPageslug();
    }
    
    public function resourcesid()
    {
        return $this->resource->getResourcesid();
    }

    public function resourcestype()
    {
        return $this->resource->getResourcestype();
    }
  
    /**
     * @return \DateTime
     */
    public function created()
    {
        return $this->resource->getCreated();
    }

    
    public function reference()
    {
        return $this->resource->getReference();
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
