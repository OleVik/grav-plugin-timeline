<?php

/**
 * Timeline Plugin
 *
 * PHP version 7
 *
 * @package    Grav\Plugin\TimelinePlugin
 * @author     Ole Vik <git@olevik.net>
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 * @link       https://github.com/OleVik/grav-plugin-timeline
 */

namespace Grav\Plugin\TimelinePlugin;

use Grav\Common\Grav;
use Grav\Common\Plugin;
use Grav\Common\Page\Page;
use Grav\Common\Page\Media;
use Grav\Common\Page\Header;
use Grav\Common\Page\Collection;
use RocketTheme\Toolbox\Event\Event;

use Grav\Plugin\TimelinePlugin\Utilities;

/**
 * Content API
 */
class Content
{
    /**
     * Initialize class
     *
     * @param string $orderBy  Property to order by.
     * @param string $orderDir Direction to order.
     */
    public function __construct($orderBy = 'date', $orderDir = 'asc')
    {
        $this->orderBy = $orderBy;
        $this->orderDir = $orderDir;
    }

    /**
     * Create page-structure recursively
     *
     * @param string  $route Route to page.
     * @param string  $limit Limit recursion-depth.
     * @param string  $mode  Placeholder for operation-mode, private.
     * @param integer $depth Placeholder for recursion depth, private.
     *
     * @return array Page-structure with children and media
     */
    public function buildTree($route, $limit = null, $mode = false, $depth = 0)
    {
        if (!Grav::instance()['page']->route()) {
            $grav = Grav::instance();
            $grav['debugger']->enabled(false);
            $grav['twig']->init();
            $grav['pages']->init();
            $page = $grav['page']->find($route);
        } else {
            $page = Grav::instance()['page'];
        }
        if (!isset($limit) || !is_int($limit)) {
            if (isset($page->header()->limit) && is_int($page->header()->limit)) {
                $limit = $page->header()->limit;
            } else {
                $limit = Grav::instance()['config']->get('plugins.timeline.limit', 8);
            }
        }
        if ($depth >= $limit) {
            return;
        }
        $depth++;
        $mode = '@page.self';
        if ($depth > 1) {
            $mode = '@page.children';
        }
        $pages = $page->evaluate([$mode => $route]);
        $pages = $pages->published()->order($this->orderBy, $this->orderDir);
        $nodes = array();
        $locale = Grav::instance()['config']->get('plugins.timeline.locale', 'en');
        $orderBy = $this->orderBy;
        $orderDir = $this->orderDir;
        foreach ($pages as $page) {
            $route = $page->rawRoute();
            $path = $page->path();
            $nodes[$route] = self::createNode($page, $depth);
            $header = $nodes[$route]['header'];
            if (isset($header['locale'])) {
                $locale = $header['locale'];
            }
            if (isset($header['order'])) {
                if (isset($header['order']['by'])) {
                    $orderBy = $header['order']['by'];
                }
                if (isset($header['order']['dir'])) {
                    $orderDir = $header['order']['dir'];
                }
            }
            $media = new Media($path);
            foreach ($media->all() as $filename => $file) {
                $nodes[$route]['media'][$filename] = $file;
            }
            $nodes[$route]['children'] = array();
            if (!empty($nodes[$route])) {
                $children = $this->buildTree($route, $limit, $mode, $depth);
                if (!empty($children)) {
                    $children = Utilities::parseLocalizedDatetimes($children, $orderBy, $locale);
                    $children = Utilities::sortByDatetime($children, $orderDir, $orderBy);
                    $nodes[$route]['children'] = array_merge($children);
                }
            }
            if (isset($header['inject_timeline'])) {
                $injections = $this->buildTree($header['inject_timeline'], $limit, $mode, $depth);
                if (!empty($injections)) {
                    $children = Utilities::parseLocalizedDatetimes($injections, $orderBy, $locale);
                    $children = Utilities::sortByDatetime($injections, $orderDir, $orderBy);
                    if ($header['inject_period'] == true) {
                        $pages = Grav::instance()['pages'];
                        $page = $pages->find($header['inject_timeline']);
                        $nodes[$header['inject_timeline']] = self::createNode($page, $depth);
                        $nodes[$header['inject_timeline']]['children'] = $injections;
                    } else {
                        $nodes[$route]['children'] = array_merge($children, $injections);
                    }
                }
            }
        }
        if (!empty($nodes)) {
            return $nodes;
        } else {
            return null;
        }
    }

    public static function createNode($page, $depth)
    {
        $node = array();
        $route = $page->rawRoute();
        $date = $page->date();
        $datetime = \DateTime::createFromFormat('U', $date);
        $date = $datetime->format('Y-m-d H:i:s');
        $header = $page->find($route)->header();
        $header = $page->toArray($header)['header'];
        $node['title'] = $page->title();
        $node['depth'] = $depth;
        $node['route'] = $route;
        $node['url'] = $page->url();
        $node['folder'] = $page->folder();
        $node['datetime'] = $date;
        $node['template'] = $page->template();
        $node['markdown'] = $page->rawMarkdown();
        $node['content'] = $page->content();
        $node['header'] = $header;
        return $node;
    }
}
