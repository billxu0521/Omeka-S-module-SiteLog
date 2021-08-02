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
        $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $logmessege = '["messege":"resource request"]';
        error_log('Item:'.$item->id());
        $logRequest = [
            'o:user_ip' => $user_ip,
            'o:item_id' => $item->id(),
            'o:site_id' => $site->id(),
            'o:reference' => 'SiteLog',
            'o:context' => $logmessege,
        ];
        $this->api()->create('site_log', $logRequest);
        $view = new ViewModel;
        $view->setVariable('site', $site);
        $view->setVariable('item', $item);
        $view->setVariable('resource', $item);
        return $view;
    }
}
