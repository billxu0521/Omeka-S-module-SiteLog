<?php declare(strict_types=1);

namespace SiteLog\Service\ViewHelper;

use Omeka\View\Helper\Api;
use SiteLog\View\Helper\SiteLogger;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class SiteLoggerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        return new SiteLogger(
            $services->get('Omeka\ApiManager')
        );
        
    }
}
