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
use Spatie\SchemaOrg\Schema;

/**
 * LinkedData API
 */
class LinkedData
{
    public $data;

    /**
     * Initialize class
     *
     * @param string $orderBy  Property to order by.
     * @param string $orderDir Direction to order.
     */
    public function __construct($orderBy = 'date', $orderDir = 'asc')
    {
        $this->data = array();
        $this->orderBy = $orderBy;
        $this->orderDir = $orderDir;
    }

    /**
     * Create Schema-structure recursively
     *
     * @param string  $route Route to page
     * @param string  $mode  Placeholder for operation-mode, private.
     * @param integer $depth Placeholder for recursion depth, private.
     *
     * @return array Page-structure with children and media
     */
    public function buildTree($route, $mode = false, $depth = 0)
    {
        $page = Grav::instance()['page'];
        $depth++;
        $mode = '@page.self';
        if ($depth > 1) {
            $mode = '@page.children';
        }
        $pages = $page->evaluate([$mode => $route]);
        $pages = $pages->published()->order($this->orderBy, $this->orderDir);
        foreach ($pages as $page) {
            $route = $page->rawRoute();
            $date = $page->date();
            $date = \DateTime::createFromFormat('U', $date)->format('Y-m-d H:i:s');
            $header = $page->find($route)->header();
            $header = $page->toArray($header)['header'];
            $header['date'] = $date;
            $header['url'] = $page->url(true, true, true);
            $parsedown = new \ParsedownExtra();
            $header['content'] = $parsedown->text($page->rawMarkdown());
            if ($page->children() !== null) {
                $this->buildTree($route, $mode, $depth);
            }
            if (!isset($header['image'])) {
                if (!empty($page->media()->images())) {
                    $header['image'] = key($page->media()->images());
                }
            }
            if (count($page->children()) < 1) {
                $this->data[$route] = $header;
            }
        }
    }

    /**
     * Build valid Schema/JsonLD data
     *
     * @param array   $params    Page data.
     * @param string  $eventType Type of event.
     * @param boolean $script    Return JavaScript or PHP Array.
     *
     * @return array|string
     */
    public static function getSchema($params, $eventType, $script = false)
    {
        $event = Schema::$eventType();
        $event->name($params['title']);
        if (isset($params['subtitle'])) {
            $event->alternateName($params['subtitle']);
        }
        if (isset($params['date'])) {
            $event->startDate($params['date']);
        }
        if (isset($params['date'])) {
            $event->endDate($params['date']);
        }
        if (isset($params['url'])) {
            $event->url($params['url']);
        }
        if (isset($params['locale'])) {
            $event->inLanguage($params['locale']);
        }
        if (isset($params['content'])) {
            $event->description($params['content']);
        }
        $event->location(
            Schema::Place()
                ->name(isset($params['place']) ? $params['place'] : 'Place')
                ->address(
                    Schema::PostalAddress()
                        ->addressLocality(isset($params['locality']) ? $params['locality'] : 'Locality')
                        ->addressRegion(isset($params['region']) ? $params['region'] : 'Region')
                )
        );
        if (isset($params['image'])) {
            $event->image($params['image']);
        }
        if ($script) {
            $event = $event->toScript();
            $event = str_replace('<script type="application/ld+json">', '', $event);
            $event = str_replace('</script>', '', $event);
            return $event;
        }
        return $event->toArray();
    }

    /**
     * Aggregate Schema/JsonLD data
     *
     * @return string
     */
    public function getSchemas()
    {
        $data = array();
        foreach ($this->data as $route => $params) {
            if (isset($params['type'])) {
                $type = $params['type'];
                unset($params['type']);
                $data[] = LinkedData::getSchema($params, $type, true);
            } else {
                $data[] = LinkedData::getSchema($params, 'Event', true);
            }
        }
        $return = '';
        foreach ($data as $schema) {
            $return .= $schema . "\n";
        }
        return $return;
    }
}
