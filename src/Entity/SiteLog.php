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
     * @var Site
     * @ManyToOne(
     *     targetEntity="Omeka\Entity\Site"
     * )
     * @JoinColumn(
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     */
    protected $site;
    
    /**
     * @var Item
     * @ManyToOne(
     *     targetEntity="Omeka\Entity\Item"
     * )
     * @JoinColumn(
     *     nullable=true,
     *     onDelete="SET NULL"
     * )
     */
    protected $item;

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

    public function getSiteid()
    {
        return $this->site_id;
    }
    
    public function setUserip($user_ip)
    {
        $this->user_ip = $user_ip;
    }
    
    public function getUserip()
    {
        return $this->user_ip;
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
     * @param Site $site
     * @return self
     */
    public function setSite(Site $site = null)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return \Omeka\Entity\Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Item $item
     * @return self
     */
    public function setItem(Item $item = null)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return \Omeka\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
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
