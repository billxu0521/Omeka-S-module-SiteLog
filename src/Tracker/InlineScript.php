<?php 
namespace SiteLog\Tracker;

use Laminas\EventManager\Event;

class InlineScript extends AbstractTracker
{
    public function track($url, $type, Event $event): void
    {
        if ($type === 'html') {
            $this->trackInlineScript($url, $type, $event);
        }
    }
}
