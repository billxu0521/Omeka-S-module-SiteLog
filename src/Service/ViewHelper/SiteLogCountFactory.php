<?php declare(strict_types=1);

namespace SiteLog\Service\ViewHelper;

use SiteLog\View\Helper\SiteLogCount;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class SiteLogCountFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $services, $requestedName, array $options = null)
    {
        return new SiteLogCount(
            $services->get('Omeka\Connection')
        );
    }
}
