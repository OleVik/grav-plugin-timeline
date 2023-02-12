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
     * Property to order content by.
     * @var string
     */
    private $orderBy;

    /**
     * Direction to order content by.
     * @var string
     */
    private $orderDir;

    /**
     * Start of date range.
     * @var string
     */
    private $startDate;

    /**
     * End of date range.
     * @var string
     */
    private $endDate;

    /**
     * Limit recursion-depth..
     * @var int
     */
    private $limit;

    /**
     * Grav-instance.
     * @var object
     */
    public $instance;

    /**
     * Page-instance.
     * @var object
     */
    public $page;

    /**
     * Page-instance.
     * @var string
     */
    public $locale;

    /**
     * Start of processing.
     * @var object
     */
    public $startTime;

    /**
     * Initialize class
     *
     * @param string $orderBy  Property to order by.
     * @param string $orderDir Direction to order.
     */
    public function __construct(
        $orderBy = 'date',
        $orderDir = 'asc',
        $startDate = null,
        $endDate = null
    ) {
        $this->startTime = microtime(true);
        $this->orderBy = $orderBy;
        $this->orderDir = $orderDir;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->instance = Grav::instance();
        $this->page = $this->instance['page'];
        $this->locale = $this->instance['config']->get('plugins.timeline.locale', 'en');

        if (isset($this->page->header()->limit) && is_int($this->page->header()->limit)) {
            $this->limit = $this->page->header()->limit;
        } else {
            $this->limit = $this->instance['config']->get('plugins.timeline.limit', 8);
        }
    }

    /**
     * Create page-structure recursively
     *
     * @param string  $route Route to page.
     * @param string  $mode  Placeholder for operation-mode, private.
     * @param integer $depth Placeholder for recursion depth, private.
     *
     * @return array Page-structure with children and media
     */
    public function buildTree($route, $mode = false, $depth = 0)
    {
        $nodes = array();
        $locale = $this->locale;
        $orderBy = $this->orderBy;
        $orderDir = $this->orderDir;
        if (!$this->page->route()) {
            $grav = Grav::instance();
            $grav['debugger']->enabled(false);
            $grav['twig']->init();
            $grav['pages']->init();
            $page = $grav['page']->find($route);
        } else {
            $page = $this->page;
        }
        if ($depth >= $this->limit) {
            return;
        }
        $depth++;
        $mode = '@page.self';
        if ($depth > 1) {
            $mode = '@page.children';
        }
        $pages = $this->page->evaluate([$mode => $route])->published();
        foreach ($pages as $page) {
            if ($page->template() == 'timeline') {
                $events = $page->evaluate(['@page.descendants' => $page->route()]);
                $events = $events->published()->order($this->orderBy, $this->orderDir);
                $first = $events->ofType('timeline_event')->first();
                $page->date(date('c', $first->date()));
            }
        }
        $pages->dateRange($this->startDate, $this->endDate);

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
                $children = $this->buildTree($route, $mode, $depth);
                if (!empty($children)) {
                    $children = Utilities::parseLocalizedDatetimes($children, $orderBy, $locale);
                    $children = Utilities::sortByDatetime($children, $orderDir, $orderBy);
                    $nodes[$route]['children'] = array_merge($children);
                }
            }
            if (isset($header['inject_timeline'])) {
                $injections = $this->buildTree($header['inject_timeline'], $mode, $depth);
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
