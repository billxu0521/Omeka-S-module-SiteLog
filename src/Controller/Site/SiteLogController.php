<?php

namespace SiteLog\Controller\Site;

use Laminas\Http\Response;
use Laminas\Http\PhpEnvironment\RemoteAddress;
use Laminas\Mvc\Controller\AbstractActionController;
use Omeka\Api\Representation\AbstractResourceEntityRepresentation;
use Omeka\Api\Representation\AbstractRepresentation;
use Laminas\View\Model\JsonModel;

class SiteLogController extends AbstractActionController
{
    public function addAction()
    {
        
        $request = $this->getRequest();
        $api = $this->api();
        $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $site = $this->currentSite();
        $site_id = $site ? $site->id() : null;
        $data = $this->params()->fromPost();
        $log = $data['log'];
        $logRequest = [
            'o:user_ip' => $user_ip,
            'o:site_id' => $site_id,
            'o:log' => $log,
        ];
        
        $api->create('site_log', $logRequest);
        
        return new JsonModel([
            'status' => 'success',
            'data' => [
                'site_id' => $site_id,
                'log' => $log,
                ],
        ]);
        
    }
    
    /**
     * Return a message of error.
     *
     * @param string $message
     * @param int $statusCode
     * @param array $messages
     * @return \Zend\View\Model\JsonModel
     */
    protected function jsonError($message, $statusCode = Response::STATUS_CODE_400, array $messages = [])
    {
        $response = $this->getResponse();
        $response->setStatusCode($statusCode);
        $output = ['error' => $message];
        if ($messages) {
            $output['messages'] = $messages;
        }
        return new JsonModel($output);
    }
    
}
