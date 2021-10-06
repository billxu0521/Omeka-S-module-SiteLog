<?php declare(strict_types=1);

namespace SiteLog\View\Helper;

use Omeka\Api\Representation\SitePageRepresentation;

use Omeka\Api\Manager as ApiManager;
use Laminas\View\Helper\AbstractHelper;

class SiteLogger extends AbstractHelper
{

    /**
     * @var SitePageRepresentation
     */
    protected $page;

    /**
     * @var ApiManager
     */
    protected $apiManager;

    /**
     * Construct the helper.
     *
     * @param ApiManager $apiManager
     */
    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
    }

     /**
     * Add new log in DB
     * 
     * @param int  $site_id
     * @param int $resources_id
     * @param string $reference 
     * @param string $page_slug
     * @param string $logmessege 
     */
    public function __invoke(
        $site_id = null,
        $resources_id = null,
        $resources_type = '',
        $reference = '',
        $page_slug = '',
        $logmessege = ''
    ) {
        //$logmessege = '["messege":"test"]';
        /**
         * @todo get setting to check ip record
         * 
        */
        $checkiprecord = true;
        if($checkiprecord){
            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        $logRequest = [
            'o:user_ip' => $user_ip,
            'o:site_id' => $site_id,
            'o:resources_id' => $resources_id,
            'o:resources_type' => $resources_type,
            'o:page_slug' => $page_slug,
            'o:reference' => $reference,
            'o:context' => $logmessege,
        ];
        $this->apiManager->create('site_log', $logRequest);
    }
}
