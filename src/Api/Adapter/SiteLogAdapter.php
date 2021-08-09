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
        'page_slug' => 'page_slug',
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
        $entity->setSiteid($request->getValue('o:site_id', ''));
        $entity->setItemid($request->getValue('o:item_id', ''));
        $entity->setUserip($request->getValue('o:user_ip', ''));
        $entity->setPageslug($request->getValue('o:page_slug', ''));
        $entity->setReference($request->getValue('o:reference', ''));
        $entity->setContext($request->getValue('o:context', ''));
    }

    public function buildQuery(QueryBuilder $qb, array $query)
    {
        $isOldOmeka = \Omeka\Module::VERSION < 2;        
        $expr = $qb->expr();
        
        // User table is not joined to get only existing users: useless with
        // "on delete set null".
        if (isset($query['site_id']) && strlen((string) $query['site_id'])) {
            $qb->andWhere($expr->eq(
                'omeka_root.site_id',
                $this->createNamedParameter($qb, $query['site_id'])
            ));
        }

        if (isset($query['item_id']) && strlen((string) $query['item_id'])) {
            $qb->andWhere($expr->eq(
                'omeka_root.item_id',
                $this->createNamedParameter($qb, $query['item_id'])
            ));
        }

        if (isset($query['page_slug']) && strlen((string) $query['page_slug'])) {
            $qb->andWhere($expr->eq(
                'omeka_root.page_slug',
                $this->createNamedParameter($qb, $query['page_slug'])
            ));
        }

    }
}
