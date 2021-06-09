<?php

/**
 * Timeline Plugin
 *
 * PHP version 7
 *
 * @package    Grav\Plugin
 * @author     Ole Vik <git@olevik.net>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @link       https://github.com/OleVik/grav-plugin-timeline
 */

namespace Grav\Plugin;

use Grav\Common\Grav;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use Grav\Plugin\TimelinePlugin\SchemaBlueprint;
use Grav\Framework\Cache\Adapter\FileCache;

/**
 * Class TimelinePlugin
 *
 * @package Grav\Plugin
 */
class TimelinePlugin extends Plugin
{
    /**
     * Locations for storage
     *
     * @var array
     */
    public $target;

    /**
     * Register plugin
     *
     * @return void
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => [
                ['onPluginsInitialized', 0]
            ]
        ];
    }

    /**
     * Composer autoload.
     *
     * @return \Composer\Autoload\ClassLoader
     */
    public function autoload(): \Composer\Autoload\ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }
    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function onPluginsInitialized()
    {
        if (Grav::instance()['config']->get('system.debugger.enabled')) {
            $this->grav['debugger']->startTimer('timeline', 'Timeline');
        }
        $Locator = $this->grav['locator'];
        $this->target = array(
            'persist' => $Locator->findResource('user://') . '/data/timeline',
            'transient' => $Locator->findResource('cache://') . '/timeline',
            'native' => $Locator->findResource('user://') . '/plugins/timeline/data'
        );
        if ($this->isAdmin()) {
            $this->enable(
                [
                    'onGetPageTemplates' => ['onGetPageTemplates', 0]
                ]
            );
        } else {
            $this->enable(
                [
                    'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
                    'onTwigExtensions' => ['onTwigExtensions', 0]
                ]
            );
        }
        if (Grav::instance()['config']->get('system.debugger.enabled')) {
            $this->grav['debugger']->stopTimer('timeline');
        }
    }

    /**
     * Add current directory to twig lookup paths.
     *
     * @return void
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Register templates and blueprints
     *
     * @param RocketTheme\Toolbox\Event\Event $event Event handler
     *
     * @return void
     */
    public function onGetPageTemplates(Event $event)
    {
        $types = $event->types;
        $Locator = $this->grav['locator'];
        $types->scanBlueprints(
            $Locator->findResource('plugin://' . $this->name . '/blueprints')
        );
        $types->scanTemplates(
            $Locator->findResource('plugin://' . $this->name . '/templates')
        );
    }

    /**
     * Get list of language codes
     *
     * With ISO 639-2 and 639-3 as keys
     *
     * @return array
     */
    public static function getLanguageCodes()
    {
        require_once __DIR__ . '/vendor/autoload.php';
        foreach (\Carbon\Language::all() as $code => $item) {
            if (isset($item['isoName'])) {
                $return[$code] = $item['isoName'];
            }
        }
        return $return;
    }

    /**
     * Get active language in ISO-639-1
     *
     * @return string
     */
    public static function getActiveLanguage()
    {
        if (Grav::instance()['language']->getLanguage()) {
            return Grav::instance()['language']->getLanguage();
        }
        return Grav::instance()['config']->get('plugins.timeline.locale', 'en');
    }

    /**
     * Get plugin's language in ISO-639-1
     *
     * @return string
     */
    public static function getPluginLanguage()
    {
        return Grav::instance()['config']->get('plugins.timeline.locale', 'en');
    }

    /**
     * Get plugin's default order by setting
     *
     * @return string
     */
    public static function getOrderBy()
    {
        return Grav::instance()['config']->get('plugins.timeline.order.by', 'date');
    }

    /**
     * Get plugin's default order direction setting
     *
     * @return string
     */
    public static function getOrderDir()
    {
        return Grav::instance()['config']->get('plugins.timeline.order.dir', 'desc');
    }

    /**
     * Get Event Schemas for blueprint
     *
     * @return array
     */
    public static function getEventTypes()
    {
        $config = Grav::instance()['config']->get('plugins.timeline');
        $Locator = Grav::instance()['locator'];
        $file = 'Event.schema.json';
        if ($config['cache'] != 'disabled') {
            $target = array(
                'persist' => $Locator->findResource('user://') . '/data/timeline',
                'transient' => $Locator->findResource('cache://') . '/timeline',
                'native' => $Locator->findResource('user://') . '/plugins/timeline/data'
            );
            $location = $target[$config['cache']];
            $Storage = new FileCache('', null, $location);
            if (!$Storage->doHas($file)) {
                $schemas = new SchemaBlueprint('/^Bordeux\\\\SchemaOrg\\\\Thing\\\\Event/mi');
                $schemas->remove('UserInteraction');
                $data = $schemas->data;
                $json = json_encode($data);
                $Storage->doSet($file, $json, 0);
            } else {
                $data = json_decode($Storage->doGet($file), true);
            }
        } else {
            $schemas = new SchemaBlueprint('/^Bordeux\\\\SchemaOrg\\\\Thing\\\\Event/mi');
            $schemas->remove('UserInteraction');
            $data = $schemas->data;
        }
        return SchemaBlueprint::getOptions((array) $data['Schema']['Thing']);
    }

    /**
     * Add Twig extensions
     *
     * @return void
     */
    public function onTwigExtensions()
    {
        if (!class_exists('\Grav\Plugin\DateTranslate')) {
            include_once __DIR__ . '/twig/DateTranslateExtension.php';
            $this->grav['twig']->twig->addExtension(new DateTranslateExtension($this->grav));
        }
        include_once __DIR__ . '/twig/TimelineDataExtension.php';
        $this->grav['twig']->twig->addExtension(new TimelineDataExtension($this->grav));
        include_once __DIR__ . '/twig/TimelineTruncateExtension.php';
        $this->grav['twig']->twig->addExtension(new TimelineTruncateExtension($this->grav));
    }
}
