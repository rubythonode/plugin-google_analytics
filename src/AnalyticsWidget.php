<?php
/**
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license     LGPL-2.1
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html
 * @link        https://xpressengine.io
 */

namespace Xpressengine\Plugins\GoogleAnalytics;

use Google_Client;
use Google_Auth_AssertionCredentials;
use Google_Service_Analytics;
use Xpressengine\Widget\AbstractWidget;
use XeFrontend;
use Xpressengine\Widget\Exceptions\NotConfigurationWidgetException;

class AnalyticsWidget extends AbstractWidget
{
    /**
     * @var Setting
     */
    protected $setting;
    
    protected function init()
    {
        $this->setting = app('xe.plugin.ga')->getSetting();
    }

    public function render()
    {
        // dailyVisits, visitSources, browsers, pageViews
        $type = array_get($this->config, 'type');
        XeFrontend::js('https://www.google.com/jsapi')->appendTo('head')->load();
        if (!$type) {
            throw new \Exception('Must need type argument');
        }

        if ($this->checkConfiguration() !== true) {
            throw new NotConfigurationWidgetException;
        }

        $class = __NAMESPACE__ . '\\Widgets\\' . ucfirst($type);
        if (class_exists(ucfirst($class)) !== true) {
            throw new \Exception("Unknown type [{$type}]");
        }

        /** @var \Xpressengine\Plugins\GoogleAnalytics\Widgets\AbstractAnalytics $widget */
        $widget = new $class($this->getAnalytics(), $this->setting->get('profileId'), $this->config);
        return $widget->render();
    }

    private function getAnalytics()
    {
        $projectName = $this->setting->get('projectName');
        $accountEmail = $this->setting->get('accountEmail');
        $clientId = $this->setting->get('clientId');

        $client = new Google_Client();
        $client->setApplicationName($projectName);
        $client->setAssertionCredentials(new Google_Auth_AssertionCredentials(
            $accountEmail,
            [\Google_Service_Analytics::ANALYTICS_READONLY],
            $this->setting->getKeyContent()
        ));
        $client->setClientId($clientId);

        return new Google_Service_Analytics($client);
    }

    private function checkConfiguration()
    {
        return $this->setting->get('projectName')
            && $this->setting->get('accountEmail')
            && $this->setting->get('clientId')
            && $this->setting->get('profileId')
            && $this->setting->getKeyContent();
    }
}
