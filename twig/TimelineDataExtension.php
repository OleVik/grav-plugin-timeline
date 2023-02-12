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

use Grav\Plugin\TimelinePlugin\Content;
use Grav\Plugin\TimelinePlugin\LinkedData;

/**
 * Timeline Data wrappers for Twig
 */
class TimelineDataExtension extends \Twig_Extension
{
    /**
     * Register name
     *
     * @return string
     */
    public function getName()
    {
        return 'TimelineData';
    }

    /**
     * Register functions
     *
     * @return \Twig_SimpleFunction
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('build_timeline', [$this, 'getTree']),
            new \Twig_SimpleFunction('build_linkeddata', [$this, 'getLinkedData'])
        ];
    }

    /**
     * Instantiate Content and build tree
     *
     * @param string $route     Route to page.
     * @param string $limit     Limit recursion-depth.
     * @param string $orderBy   Property to order by.
     * @param string $orderDir  Direction to order.
     * @param string $startDate Start of date range.
     * @param string $endDate   End of date range.
     *
     * @return array Hierarchical Page-structure with children and media
     */
    public function getTree(
        $route,
        $limit = null,
        $orderBy = 'date',
        $orderDir = 'asc',
        $startDate = null,
        $endDate = null
    ) {
        $content = new Content($orderBy, $orderDir, $startDate, $endDate);
        $tree = $content->buildTree($route, $limit);
        return $tree;
    }

    /**
     * Instantiate LinkedData and build tree
     *
     * @param string $route    Route to page.
     * @param string $orderBy  Property to order by.
     * @param string $orderDir Direction to order.
     *
     * @return array Aggregate Schema/JsonLD data
     */
    public function getLinkedData($route, $orderBy = 'date', $orderDir = 'asc')
    {
        $linkedData = new LinkedData($orderBy, $orderDir);
        $linkedData->buildTree($route);
        return $linkedData->getSchemas();
    }
}
