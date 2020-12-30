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

use Grav\Plugin\TimelinePlugin\Utilities;

/**
 * Create list of Schema.org schemas from bordeux/schema-org
 */
class SchemaBlueprint
{
    /**
     * Schema.org schemas
     *
     * @var array
     */
    public $data;

    /**
     * Initialize class
     *
     * @param string $filter PCRE-pattern for matching.
     */
    public function __construct($filter = false)
    {
        $this->data = $this->getSource();
        if (!empty($filter)) {
            $this->data = preg_grep($filter, $this->data);
        }
        $list = $this->getHierarchy(true);
        $this->data = $list;
    }

    /**
     * Explode namespace-hierarchy into array
     *
     * @param boolean $sanitize Replace package-name with 'Schema'.
     *
     * @return array
     */
    public function getHierarchy($sanitize = false)
    {
        $schemas = array();
        foreach ($this->data as $schema) {
            if ($sanitize) {
                $schema = str_replace('Bordeux\\SchemaOrg', 'Schema', $schema);
            }
            Utilities::assignArrayByPath($schemas, $schema);
        }
        return $schemas;
    }

    /**
     * Parse namespace-hierarchy from source
     *
     * @param string $target Relative path to source package.
     *
     * @see https://stackoverflow.com/a/27440555
     *
     * @return array
     */
    public function getSource($target = '/../vendor/bordeux/schema-org/src')
    {
        $path = __DIR__ . $target;
        $fqcns = array();

        $allFiles = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path)
        );
        $phpFiles = new \RegexIterator($allFiles, '/\.php$/');
        foreach ($phpFiles as $phpFile) {
            $content = file_get_contents($phpFile->getRealPath());
            $tokens = token_get_all($content);
            $namespace = '';
            for ($index = 0; isset($tokens[$index]); $index++) {
                if (!isset($tokens[$index][0])) {
                    continue;
                }
                if (T_NAMESPACE === $tokens[$index][0]) {
                    $index += 2;
                    while (isset($tokens[$index]) && is_array($tokens[$index])) {
                        $namespace .= $tokens[$index++][1];
                    }
                }
                if (T_CLASS === $tokens[$index][0] &&
                    T_WHITESPACE === $tokens[$index + 1][0] &&
                    T_STRING === $tokens[$index + 2][0]
                ) {
                    $index += 2;
                    $fqcns[] = $namespace . '\\' . $tokens[$index][1];
                    break;
                }
            }
        }
        return $fqcns;
    }

    /**
     * Create list of options for use in Grav blueprint
     *
     * @param array $data Data to use in blueprint.
     *
     * @return array
     */
    public static function getOptions($data)
    {
        return Utilities::arrayFlattenKeysAsValues($data);
    }

    /**
     * Keep item(s) from multidimensional array by key
     *
     * @param string|array $key Key(s) to keep.
     *
     * @return void
     */
    public function keep($key)
    {
        $data = [];
        if (is_array($key)) {
            foreach ($key as $item) {
                $search = Utilities::arraySearch($this->data, $item);
                $tree = Utilities::collapse($search, null);
                $data = array_replace_recursive($data, $tree);
            }
        } else {
            $search = Utilities::arraySearch($this->data, $key);
            $tree = Utilities::collapse($search, null);
            $data = array_replace_recursive($data, $tree);
        }
        $this->data = $data;
    }

    /**
     * Remove items from multidimensional array by key
     *
     * @param string|array $key Key(s) to remove.
     *
     * @return void
     */
    public function remove($key)
    {
        if (is_array($key)) {
            foreach ($key as $items) {
                $this->data = Utilities::removeKey($this->data, $items);
            }
        } else {
            $this->data = Utilities::removeKey($this->data, $key);
        }
    }
}
