<?php
namespace Timeline\API;

use Grav\Common\Grav;
use Grav\Common\Plugin;
use Grav\Common\Page\Page;
use Grav\Common\Page\Media;
use Grav\Common\Page\Header;
use Grav\Common\Page\Collection;
use RocketTheme\Toolbox\Event\Event;

use Timeline\Utilities;

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
    public function __construct($orderBy = 'date', $orderDir = 'desc')
    {
        $this->orderBy = $orderBy;
        $this->orderDir = $orderDir;
    }

    /**
     * Create page-structure recursively
     * 
     * @param string  $route Route to page
     * @param string  $mode  Placeholder for operation-mode, private.
     * @param integer $depth Placeholder for recursion depth, private.
     * 
     * @return array Page-structure with children and media
     */
    public function buildTree($route, $mode = false, $depth = 0)
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
        $depth++;
        $mode = '@page.self';
        if ($depth > 1) {
            $mode = '@page.children';
        }
        $pages = $page->evaluate([$mode => $route]);
        $pages = $pages->published()->order($this->orderBy, $this->orderDir);
        $paths = array();
        foreach ($pages as $page) {
            $route = $page->rawRoute();
            $path = $page->path();
            $title = $page->title();
            $date = $page->date();
            $template = $page->template();
            $markdown = $page->rawMarkdown();
            $content = $page->content();
            $datetime = \DateTime::createFromFormat('U', $date);
            $date = $datetime->format('Y-m-d H:i:s');
            $paths[$route]['depth'] = $depth;
            $paths[$route]['route'] = $route;
            $paths[$route]['datetime'] = $date;
            $paths[$route]['template'] = $template;
            $paths[$route]['markdown'] = $markdown;
            $paths[$route]['content'] = $content;
            $header = $page->find($route)->header();
            $header = $page->toArray($header)['header'];
            $paths[$route]['header'] = $header;
            $media = new Media($path);
            foreach ($media->all() as $filename => $file) {
                $paths[$route]['media'][$filename] = $file;
            }
            if (!empty($paths[$route])) {
                $children = $this->buildTree($route, $mode, $depth);
                if (!empty($children)) {
                    if (isset($header['order']['by'], $header['order']['dir'])) {
                        $orderBy = $header['order']['by'];
                        $orderDir = $header['order']['dir'];
                        $children = Utilities::sortLeaf($children, $orderBy, $orderDir);
                    }
                    $paths[$route]['children'] = $children;
                }
            }
        }
        if (!empty($paths)) {
            return $paths;
        } else {
            return null;
        }
    }
}