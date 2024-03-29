<?php
namespace SiteLog\Controller\Admin;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Form\Form;


class IndexController extends AbstractActionController
{
    
    public function indexAction()
    {
        $site = $this->currentSite();
        $site_id = $site->id();
        $siteLogCounter = $this->viewHelpers()->get('siteLogCounter');
        $itemViewRanks = $siteLogCounter->getItemViewRank($site_id);
        foreach ($itemViewRanks as $key => $itemViewRank){
            $item = $this->api()->read('items', $itemViewRank['resources_id'])->getContent();
            $itemViewRanks[$key]['url'] = $item->adminUrl();
            $itemViewRanks[$key]['title'] = $item->getJsonLd()['o:title'];
        }

        $pageViewRanks = $siteLogCounter->getPageViewRank($site_id);
        foreach ($pageViewRanks as $key => $pageViewRank){
            $page = $this->api()->read('site_pages', $pageViewRank['resources_id'])->getContent();
            $pageViewRanks[$key]['url'] = $page->adminUrl();
            $pageViewRanks[$key]['title'] = $page->getJsonLd()['o:title'];
        }

        $view = new ViewModel();
        $view->setVariable('itemViewRanks', $itemViewRanks);
        $view->setVariable('itemPageRanks', $pageViewRanks);
        
        return $view;
    }

    public function browserAction()
    {
       
    }
}

