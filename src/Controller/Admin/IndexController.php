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
        $siteSettings = $this->siteSettings();
        $this->setBrowseDefaults('created');
       
        $params = $this->params()->fromQuery();
        $params['site_id'] = $site->id();        
        
        $response = $this->api()->search('site_log',$params);
        $this->paginator($response->getTotalResults(), $this->params()->fromQuery('page'));

        $site_log = $response->getContent();
        error_log('site id');
        error_log(json_encode($site_log));
        
        $view = new ViewModel();
        $view->setVariable('sitelogs', $site_log);
        $view->setVariable('siteid', $params);
        
        return $view;
    }
}

