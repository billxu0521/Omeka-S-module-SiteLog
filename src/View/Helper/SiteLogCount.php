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
        error_log('SiteLog Count');
        //讀取資料庫並且計算
        $qb = $this->connection->createQueryBuilder();
      
        //$eqGroupGrouping = $qb->expr()->eq('groups.id', $joinTable . '.group_id');
        
        //$result = 0;
        //return $result;
    }
}
