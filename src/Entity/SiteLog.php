<?php

namespace SiteLog\Entity;

use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Omeka\Entity\AbstractEntity;
use Omeka\Entity\Site;
use Omeka\Entity\Item;

/**
 * @Entity
 * @Table(
 *     name="site_log"
 * )
 * @HasLifecycleCallbacks
 */
class SiteLog extends AbstractEntity
{
    /**
     * @var int
     *
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @var int
     *
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $item_id;

    /**
     * @var int
     *
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $site_id;
  
    /**
     * @var string
     * @Column(type="string", length=45)
     */
    protected $user_ip;

    /**
     * @var string
     * @Column(type="text")
     */
    protected $page_slug;

    /**
     * @var string
     * @Column(type="text")
     */
    protected $reference;

    /**
     * @var string
     * @Column(type="text")
     */
    protected $context;
  
    /**
     * @var DateTime
     *
     * @Column(type="datetime")
     */
    protected $created;
    /**
     * @var DateTime
     *
     * @Column(type="datetime")
     */
   

    public function getId()
    {
        return $this->id;
    }
    
    public function getItemid()
    {
        return $this->item_id;
    }

    public function setItemid($item_id)
    {
        $this->item_id = $item_id;
    }

    public function getSiteid()
    {
        return $this->site_id;
    }

    public function setSiteid($site_id)
    {
        $this->site_id = $site_id;
    }
    
    public function setUserip($user_ip)
    {
        $this->user_ip = $user_ip;
    }
    
    public function getUserip()
    {
        return $this->user_ip;
    }

    public function getPageslug()
    {
        return $this->page_slug;
    }

    public function setPageslug($page_slug)
    {
        $this->page_slug = $page_slug;
    }

    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function setContext($context)
    {
        $this->context = $context;
    }

    public function getContext()
    {
        return $this->context;
    }


    /**
     * @param DateTime $created
     * @return self
     */
    public function setCreated(DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @PrePersist
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $this->created = new DateTime('now');
        return $this;
    }
}
