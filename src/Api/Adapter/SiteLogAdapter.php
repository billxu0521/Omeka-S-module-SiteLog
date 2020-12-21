<?php

namespace SiteLog\Api\Adapter;

use Doctrine\Common\Inflector\Inflector;
use Doctrine\ORM\QueryBuilder;
use Omeka\Api\Adapter\AbstractEntityAdapter;
use Omeka\Api\Request;
use Omeka\Entity\EntityInterface;
use Omeka\Stdlib\ErrorStore;

class SiteLogAdapter extends AbstractEntityAdapter
{
    protected $sortFields = [
        
    ];

    public function getResourceName()
    {
        return 'site_log';
    }

    public function getRepresentationClass()
    {
        return \SiteLog\Api\Representation\SiteLogRepresentation::class;
    }

    public function getEntityClass()
    {
        return \SiteLog\Entity\SiteLog::class;
    }

    public function hydrate(Request $request, EntityInterface $entity,
        ErrorStore $errorStore
    ) {
        
        if ($this->shouldHydrate($request, 'o:site_id')) {
            $siteId = $request->getValue('o:site_id');
            $entity->setSite($this->getAdapter('sites')->findEntity($siteId));
        }
        $entity->setUserip($request->getValue('o:user_ip', ''));
        $entity->setLog($request->getValue('o:log', ''));
        
    }

    public function buildQuery(QueryBuilder $qb, array $query)
    {
        
      
    }
}
