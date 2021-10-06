<?php
namespace SiteLog\Controller\Site;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Omeka\Controller\Site\ItemController as OmekaItemController;

class ItemController extends OmekaItemController
{
    public function searchAction()
    {
        
    }

    public function showAction()
    {   
        $site = $this->currentSite();
        $response = $this->api()->read('items', $this->params('id'));
        $item = $response->getContent();

        //log
        $logmessege = '["messege":"resource request"]';
        $siteLogger = $this->viewHelpers()->get('siteLogger');
        $site_id = $site->id();
        $resources_id = $item->id();
        $resources_type = 'item';
        $reference = 'SiteLog';
        $page_slug = $item->id();
        $siteLogger = $siteLogger($site_id,$resources_id,$resources_type,$reference,$page_slug,$logmessege);

        $view = new ViewModel;
        $view->setVariable('site', $site);
        $view->setVariable('item', $item);
        $view->setVariable('resource', $item);
        return $view;
    }

    public function browseAction()
    {
        $site = $this->currentSite();

        $this->setBrowseDefaults('created');

        $view = new ViewModel;
        
        $query = $this->params()->fromQuery();
        $query['site_id'] = $site->id();
        if ($this->siteSettings()->get('browse_attached_items', false)) {
            $query['site_attachments_only'] = true;
        }
        if ($itemSetId = $this->params('item-set-id')) {
            $itemSetResponse = $this->api()->read('item_sets', $itemSetId);
            $itemSet = $itemSetResponse->getContent();
            $view->setVariable('itemSet', $itemSet);
            $query['item_set_id'] = $itemSetId;

            //log
            $logmessege = '["messege":"resource request"]';
            $siteLogger = $this->viewHelpers()->get('siteLogger');
            $site_id = $site->id();
            $resources_id = $itemSet->id();
            $resources_type = 'itemsets';
            $reference = 'SiteLog';
            $page_slug = $itemSet->id();
            $siteLogger = $siteLogger($site_id,$resources_id,$resources_type,$reference,$page_slug,$logmessege);
        }

        $response = $this->api()->search('items', $query);
        $this->paginator($response->getTotalResults());
        $items = $response->getContent();

        $view->setVariable('site', $site);
        $view->setVariable('items', $items);
        $view->setVariable('resources', $items);
        return $view;
    }
}
