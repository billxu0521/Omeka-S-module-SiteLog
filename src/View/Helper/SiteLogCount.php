<?php declare(strict_types=1);

namespace SiteLog\View\Helper;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Group\Entity\Group;
use Laminas\View\Helper\AbstractHelper;
use Omeka\Entity\Item;
use Omeka\Entity\ItemSet;
use Omeka\Entity\Media;
use Omeka\Entity\Resource;
use Omeka\Entity\User;
use PDO;

class SiteLogCount extends AbstractHelper
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Return the count for a list of groups for a specified resource type.
     
     */
    public function __invoke(
       
    ) {
        
      
        //$eqGroupGrouping = $qb->expr()->eq('groups.id', $joinTable . '.group_id');
        
        //$result = 0;
        //return $result;
    }
    /**
     * 取出前20的條目
     */
    public function getItemViewRank($site_id){
        error_log('SiteLog Count');
        
        //讀取資料庫並且計算
        $qb = $this->connection->createQueryBuilder();
        $select = [];
        $select['name'] = 'site_log.resources_id';
        $select['type'] = 'site_log.resources_type';
        $select['views'] = 'COUNT(site_log.resources_id) AS "views"';
        
        $resourcestype = 'item';
        $viewLimit = 20;
        $qb
            ->select($select)
            ->from('site_log')
            ->andWhere('site_log.resources_type = :resources_type')
            ->setParameter('resources_type', $resourcestype)
            ->andWhere('site_log.site_id = :site_id')
            ->setParameter('site_id', $site_id)
            ->groupBy('site_log.resources_id')
            ->orderBy('views','DESC')
            ->setMaxResults($viewLimit);
        /*
        $eqResourceType = $qb->expr()->eq('resource.resource_type', ':resource_type');
        $eqResourceGrouping = $qb->expr()->eq('resource.id','site_log.resource_id');
        $qb
            ->setParameter('resource_type', Item::class);
        $qb->expr()->andX(
                $eqResourceGrouping,
                $eqResourceType
            );
        */
        $stmt = $this->connection->executeQuery($qb, $qb->getParameters());
        $result = $stmt->fetchAll();
        error_log(json_encode($result));

        return $result;
    }

    /**
     * 取出前20的頁面瀏覽
     */
    public function getPageViewRank($site_id){
        error_log('SiteLog Count');
        
        //讀取資料庫並且計算
        $qb = $this->connection->createQueryBuilder();
        $select = [];
        $select['name'] = 'site_log.resources_id';
        $select['type'] = 'site_log.resources_type';
        $select['views'] = 'COUNT(site_log.resources_id) AS "views"';
        
        $resourcestype = 'page';
        $viewLimit = 20;
        $qb
            ->select($select)
            ->from('site_log')
            ->andWhere('site_log.resources_type = :resources_type')
            ->setParameter('resources_type', $resourcestype)
            ->andWhere('site_log.site_id = :site_id')
            ->setParameter('site_id', $site_id)
            ->groupBy('site_log.resources_id')
            ->orderBy('views','DESC')
            ->setMaxResults($viewLimit);
            
        $stmt = $this->connection->executeQuery($qb, $qb->getParameters());
        $result = $stmt->fetchAll();
        error_log(json_encode($result));

        return $result;
    }
}
