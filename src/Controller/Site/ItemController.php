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
        $item_id = $item->id();
        $reference = 'SiteLog';
        $page_slug = $item->id();
        $siteLogger = $siteLogger($site_id,$item_id,$reference,$page_slug,$logmessege);

        $view = new ViewModel;
        $view->setVariable('site', $site);
        $view->setVariable('item', $item);
        $view->setVariable('resource', $item);
        return $view;
    }
}
