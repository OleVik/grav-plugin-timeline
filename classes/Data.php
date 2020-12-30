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
 * Data API
 */
class Data
{
    /**
     * Initialize class
     */
    public function __construct()
    {
    }

    /**
     * Get UML-syntax, pretty-printed
     *
     * @param array $data Tree-data from Content API.
     *
     * @return string UML-data.
     */
    public static function getUMLSyntax(array $data)
    {
        $prepared = self::prepareUMLSyntax($data);
        return self::buildUMLSyntax($prepared);
    }

    /**
     * Prepare data for UML-syntax, recursively, for use with for example Nomnoml
     *
     * @param string $data Tree-data from Content API.
     *
     * @return array Content-data reduced and simplified.
     */
    protected static function buildUMLSyntax(array $data)
    {
        $output = '';
        $keys = array_keys($data);
        foreach (array_keys($keys) as $index) {
            $key = current($keys);
            $count = count($keys) - 1;
            $value = $data[$key];
            $next = next($keys);
            $next_value = $data[$next] ?? null;
            if (is_string($key) && is_array($value) && $count > 1 && !empty($next)) {
                $output .= '[' . $key . ']->[' . $next . ']' . "\n";
            }
            if (is_array($value)) {
                if (is_string($key)) {
                    $output .= '[' . $key . '|' . "\n";
                }
                $output .= self::buildUMLSyntax($value);
                if (is_string($key)) {
                    $output .= ']' . "\n";
                }
            } else {
                if ($key !== $count) {
                    $output .= '[' . $value . ']-[' . $next_value . ']' . "\n";
                }
            }
        }
        if (!empty($output)) {
            return $output;
        } else {
            return null;
        }
    }

    /**
     * Prepare data for UML-syntax, recursively, for use with for example Nomnoml
     *
     * @param array $data Tree-data from Content API.
     *
     * @return array Content-data reduced and simplified.
     */
    protected static function prepareUMLSyntax(array $data)
    {
        $nodes = array();
        foreach ($data as $key => $value) {
            $name = $value['header']['title'];
            if (isset($value['children']) && !empty($value['children'])) {
                $nodes[$name] = self::prepareUMLSyntax($value['children']);
            } else {
                $nodes[] = $name;
            }
        }
        if (!empty($nodes)) {
            return $nodes;
        } else {
            return null;
        }
    }

    /**
     * Get nodeStructure-JSON, encoded and pretty-printed
     *
     * @param array $data Tree-data from Content API.
     *
     * @return string Encoded JSON.
     */
    public static function getNodeStructure(array $data)
    {
        $return = new \stdClass();
        $return->nodeStructure = self::buildNodeStructure($data);
        if (!empty($return)) {
            return json_encode($return, JSON_PRETTY_PRINT);
        } else {
            return false;
        }
    }

    /**
     * Build nodeStructure-JSON, recursively, for use with for example Treant.js
     *
     * @param array $data Tree-data from Content API.
     *
     * @return array Content-data reduced and simplified.
     */
    protected static function buildNodeStructure(array $data)
    {
        $nodes = array();
        foreach ($data as $key => $value) {
            $node = array();
            $node['text']['name'] = $value['header']['title'];
            if (isset($value['children']) && !empty($value['children'])) {
                $node['stackChildren'] = true;
                $node['children'] = self::buildNodeStructure($value['children']);
            }
            $nodes[] = $node;
        }
        if (!empty($nodes)) {
            return $nodes;
        } else {
            return null;
        }
    }


    /**
     * Get Markdown-output
     *
     * @param string $data Tree-data from Content API.
     *
     * @return string Titles and Markdown from all data.
     */
    public static function getMarkdownOutput($data)
    {
        $prepared = self::prepareMarkdownOutput($data);
        return self::buildMarkdownOutput($prepared);
    }

    /**
     * Build data for Markdown-output, recursively
     *
     * @param array $data Tree-data from Content API.
     *
     * @return array Content-data reduced and simplified.
     */
    protected static function buildMarkdownOutput(array $data)
    {
        $output = '';
        foreach ($data as $title => $headers) {
            $depth = $headers['depth'];
            foreach ($headers as $key => $value) {
                switch ($key) {
                    case 'title':
                        $prepend = str_repeat('#', $depth);
                        $output .= $prepend . ' ' . $value . "\n\n";
                        break;
                    case 'subtitle':
                        $prepend = str_repeat('#', ($depth + 1));
                        $output .= $prepend . ' ' . $value . "\n\n";
                        break;
                    case 'markdown':
                        $output .= $value . "\n\n";
                        break;
                    case 'children':
                        $output .= self::buildMarkdownOutput($value);
                        break;
                }
            }
            $output .= '---' . "\n\n";
        }
        if (!empty($output)) {
            return $output;
        } else {
            return null;
        }
    }

    /**
     * Prepare data for Markdown-output, recursively
     *
     * @param array $data Tree-data from Content API.
     *
     * @return array Content-data reduced and simplified.
     */
    protected static function prepareMarkdownOutput(array $data)
    {
        $nodes = array();
        foreach ($data as $key => $value) {
            $node = array();
            $node['depth'] = $value['depth'];
            $node['title'] = $value['header']['title'];
            if (isset($value['header']['subtitle']) && !empty($value['header']['subtitle'])) {
                $node['subtitle']  = $value['header']['subtitle'];
            }
            $node['markdown'] = $value['markdown'];
            if (isset($value['children']) && !empty($value['children'])) {
                $node['children'] = self::prepareMarkdownOutput($value['children']);
            }
            $nodes[$node['title']] = $node;
        }
        if (!empty($nodes)) {
            return $nodes;
        } else {
            return null;
        }
    }
}
