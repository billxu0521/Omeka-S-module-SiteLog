<?php
namespace SiteLog\Controller\Admin;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Form\Form;

class RankController extends AbstractActionController
{
    
    public function indexItemAction()
    {
        $site = $this->currentSite();
        $site_id = $site->id();
        $view = new ViewModel();
        $siteLogCounter = $this->viewHelpers()->get('siteLogCounter');
        $itemViewRanks = $siteLogCounter->getItemViewRank($site_id);
        foreach ($itemViewRanks as $key => $itemViewRank){
            $item = $this->api()->read('items', $itemViewRank['resources_id'])->getContent();
            $itemViewRanks[$key]['url'] = $item->adminUrl();
            $itemViewRanks[$key]['title'] = $item->getJsonLd()['o:title'];
        }

        $view->setVariable('itemViewRanks', $itemViewRanks);

        return $view;
       
    }

    public function indexPageAction()
    {
        $site = $this->currentSite();
        $site_id = $site->id();
        $view = new ViewModel();
        $siteLogCounter = $this->viewHelpers()->get('siteLogCounter');
        $pageViewRanks = $siteLogCounter->getPageViewRank($site_id);
        foreach ($pageViewRanks as $key => $pageViewRank){
            $page = $this->api()->read('site_pages', $pageViewRank['resources_id'])->getContent();
            $pageViewRanks[$key]['url'] = $page->adminUrl();
            $pageViewRanks[$key]['title'] = $page->getJsonLd()['o:title'];
        }

        $view->setVariable('itemViewRanks', $pageViewRanks);

        return $view;
       
    }
}

