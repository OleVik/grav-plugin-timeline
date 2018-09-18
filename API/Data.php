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
     * @param string $data Tree-data from Content API.
     * 
     * @return array Content-data reduced and simplified.
     */
    public static function getUMLSyntax($data)
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
    public static function buildUMLSyntax($data)
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
     * @param string $data Tree-data from Content API.
     * 
     * @return array Content-data reduced and simplified.
     */
    public static function prepareUMLSyntax($data)
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
     * @param string $data Tree-data from Content API.
     * 
     * @return array Content-data reduced and simplified.
     */
    public static function getNodeStructure($data)
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
     * @param string $data Tree-data from Content API.
     * 
     * @return array Content-data reduced and simplified.
     */
    protected static function buildNodeStructure($data)
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
}