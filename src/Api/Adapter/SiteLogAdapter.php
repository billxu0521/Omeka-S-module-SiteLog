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
        'id' => 'id',
        'reference' => 'reference',
        'item_id' => 'item_id',
        'site_id' => 'site_id',
        'user_ip' => 'user_ip',
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
        $data = $request->getContent();
        if ($this->shouldHydrate($request, 'o:site_id')) {
            $siteId = $request->getValue('o:site_id');
            $entity->setSite($this->getAdapter('sites')->findEntity($siteId));
        }  
        if ($this->shouldHydrate($request, 'o:item_id')) {
            $itemId = $request->getValue('o:item_id');
            $entity->setItem($this->getAdapter('items')->findEntity($itemId));
        }                
        $entity->setUserip($request->getValue('o:user_ip', ''));
        $entity->setReference($request->getValue('o:reference', ''));
        $entity->setContext($request->getValue('o:context', ''));
    }

    public function buildQuery(QueryBuilder $qb, array $query)
    {
        $isOldOmeka = \Omeka\Module::VERSION < 2;
        $alias = $isOldOmeka ? $this->getEntityClass() : 'omeka_root';

        $expr = $qb->expr();
        if (isset($query['site_id'])) {
            $userAlias = $this->createAlias();
            $qb->innerJoin(
                $alias . '.site',
                $userAlias
            );
            $qb->andWhere($expr->eq(
                "$userAlias.id",
                $this->createNamedParameter($qb, $query['site_id']))
            );
        }

    }
}
