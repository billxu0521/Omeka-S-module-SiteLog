<?php
namespace SiteLog\Controller\Site;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Omeka\Controller\Site\PageController as OmekaPageController;

class PageController extends OmekaPageController
{
    public function browseAction()
    {
        $this->setBrowseDefaults('created');
        $query = $this->params()->fromQuery();
        $query['site_id'] = $this->currentSite()->id();

        $response = $this->api()->search('site_pages', $query);
        $this->paginator($response->getTotalResults());
        $pages = $response->getContent();

        $view = new ViewModel;
        $view->setVariable('pages', $pages);
        return $view;
    }

    public function showAction()
    {
        $site = $this->currentSite();
        $page = $this->api()->read('site_pages', [
            'slug' => $this->params('page-slug'),
            'site' => $site->id(),
        ])->getContent();

        $this->viewHelpers()->get('sitePagePagination')->setPage($page);
        
        //log
        $logmessege = '["messege":"page request"]';
        $siteLogger = $this->viewHelpers()->get('siteLogger');
        $site_id = $site->id();
        $item_id = null;
        $reference = 'SiteLog';
        $page_slug = $page->slug();
        $siteLogger = $siteLogger($site_id,$item_id,$reference,$page_slug,$logmessege);

        $view = new ViewModel;

        $view->setVariable('site', $site);
        $view->setVariable('page', $page);
        $view->setVariable('displayNavigation', true);

        $contentView = clone $view;
        $contentView->setTemplate('omeka/site/page/content');
        $contentView->setVariable('pageViewModel', $view);

        $view->addChild($contentView, 'content');
        return $view;
    }
}
