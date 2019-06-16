<?php
namespace Grav\Plugin;

use Grav\Common\Grav;
use Grav\Common\Plugin;
use Grav\Common\Page\Header;
use Grav\Common\Twig\TwigExtension;
use Grav\Common\Language\LanguageCodes;
use RocketTheme\Toolbox\Event\Event;

require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/API/Data.php';
require __DIR__ . '/API/Content.php';
require __DIR__ . '/API/LinkedData.php';
require __DIR__ . '/API/SchemaBlueprint.php';
/* @deprecated 2.0.0 */
require __DIR__ . '/API/Storage.php';
require 'Utilities.php';
use Timeline\API\Data;
use Timeline\API\Content;
use Timeline\API\LinkedData;
use Timeline\API\SchemaBlueprint;
use Timeline\Utilities;
use Grav\Framework\Cache\Adapter\FileCache;
/* @deprecated 2.0.0 */
use Grav\Framework\Cache\Adapter\FileStorage;

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
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function onPluginsInitialized()
    {
        if ($this->config->get('system')['debugger']['enabled']) {
            $this->grav['debugger']->startTimer('timeline', 'Timeline');
        }
        if (!extension_loaded('intl')) {
            $this->grav['log']->notice('The Timeline-plugin for Grav requires the intl-extension for PHP.');
            return;
        }
        $res = $this->grav['locator'];
        $this->target = array(
            'persist' => $res->findResource('user://') . '/data/timeline',
            'transient' => $res->findResource('cache://') . '/timeline',
            'native' => $res->findResource('user://') . '/plugins/timeline/data'
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
                    'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
                    'onTwigExtensions' => ['onTwigExtensions', 0]
                ]
            );
        }
        if ($this->config->get('system')['debugger']['enabled']) {
            $this->grav['debugger']->stopTimer('timeline');
        }
    }

    /**
     * Declare config from plugin-config
     * 
     * @return array Plugin configuration
     */
    public function config()
    {
        $config = Grav::instance()['config']->get('plugins.timeline');
        if ($config['enabled']) {
            return $config;
        } else {
            return false;
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
     * Add data to Twig
     * 
     * @return void
     */
    public function onTwigSiteVariables()
    {
        $page = $this->grav['page'];
        if ($this->config() && $page->template() == 'timeline') {
            $content = new Content('date', 'asc');
            $tree = $content->buildTree($page->route());
            $this->grav['twig']->twig_vars['timeline_content'] = $tree;
            if ($this->config()['linked_data']) {
                $ld = new LinkedData();
                $ld->buildTree($page->route());
                $this->grav['twig']->twig_vars['timeline_linked_data'] = $ld->getSchemas();
            }
        }
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
        $res = $this->grav['locator'];
        $types->scanBlueprints(
            $res->findResource('plugin://' . $this->name . '/blueprints')
        );
        $types->scanTemplates(
            $res->findResource('plugin://' . $this->name . '/templates')
        );
    }

    /**
     * Get list of language codes
     *
     * @return array
     */
    public static function getLanguageCodes()
    {
        $target = 'assets/ISO-639-1-language.json';
        $res = Grav::instance()['locator'];
        $file = $res->findResource('plugin://timeline/' . $target);
        $json = json_decode(file_get_contents($file));
        $return = array();
        foreach ($json as $code => $item) {
            $return[$code] = $item->name;
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
        $plugins = Grav::instance()['config']->get('plugins');
        if (!$plugins['timeline']['language']) {
            return Grav::instance()['language']->getLanguage();
        } else {
            return $plugins['timeline']['language'];
        }
    }

    /**
     * Get plugin's language in ISO-639-1
     *
     * @return string
     */
    public static function getPluginLanguage()
    {
        return Grav::instance()['config']->get('plugins')['timeline']['language'];
    }

    /**
     * Get plugin's default order by setting
     *
     * @return string
     */
    public static function getOrderBy()
    {
        return Grav::instance()['config']->get('plugins')['timeline']['order']['by'];
    }

    /**
     * Get plugin's default order direction setting
     *
     * @return string
     */
    public static function getOrderDir()
    {
        return Grav::instance()['config']->get('plugins')['timeline']['order']['dir'];
    }

    /**
     * Get Event Schemas for blueprint
     *
     * @return array
     */
    public static function getEventTypes()
    {
        $config = Grav::instance()['config']->get('plugins.timeline');
        $res = Grav::instance()['locator'];
        $file = 'Event.schema.json';
        $target = array(
            'persist' => $res->findResource('user://') . '/data/timeline',
            'transient' => $res->findResource('cache://') . '/timeline',
            'native' => $res->findResource('user://') . '/plugins/timeline/data'
        );
        $location = $target[$config['cache']];
        $Storage = new FileCache('', null, $location);
        if ($config['cache'] != 'disabled') {
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
        include_once __DIR__ . '/twig/TruncateExtension.php';
        $this->grav['twig']->twig->addExtension(new TruncateExtension($this->grav));
    }
}
