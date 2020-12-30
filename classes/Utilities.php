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

use Carbon\Carbon;

/**
 * Utilities for Timeline-plugin
 */
class Utilities
{
    /**
     * Find key in array
     *
     * @param array  $array  Array to search.
     * @param string $search Key to search for.
     * @param array  $keys   Reserved
     *
     * @see https://stackoverflow.com/a/40506009/603387
     *
     * @return array
     */
    public static function arraySearch($array, $search, $keys = array())
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $sub = self::arraySearch($value, $search, array_merge($keys, array($key)));
                if (count($sub)) {
                    return $sub;
                }
            } elseif ($key === $search) {
                return array_merge($keys, array($key));
            }
        }
        return array();
    }

    /**
     * Collapse 1-d array to n-d array
     *
     * @param array $array Array to collapse.
     * @param mixed $info  Data to add as value to last index.
     *
     * @see https://stackoverflow.com/a/16925154/603387
     *
     * @return array
     */
    public static function collapse($array, $info)
    {
        $max = count($array) - 1;
        $result = array($array[$max] => $info);
        for ($i = $max - 1; $i >= 0; $result = array($array[$i--] => $result));
        return $result;
    }

    /**
     * Unset key from multidimensional array
     *
     * @param array  $array Array to manipulate.
     * @param string $key   Key to unset.
     *
     * @see https://stackoverflow.com/a/46445227
     *
     * @return array
     */
    public static function removeKey(array $array, String $key)
    {
        foreach ($array as $k => $v) {
            if (is_array($v) && $k != $key) {
                $array[$k] = self::removeKey($v, $key);
            } elseif ($k == $key) {
                unset($array[$k]);
            }
        }
        return $array;
    }

    /**
     * Add element to multidimensional array
     *
     * @param array  $arr  Array to hold values, private.
     * @param string $path String to add.
     *
     * @see https://stackoverflow.com/a/15133284
     *
     * @return void
     */
    public static function assignArrayByPath(&$arr, $path)
    {
        $keys = explode('\\', $path);
        while ($key = array_shift($keys)) {
            $arr = &$arr[$key];
        }
    }

    /**
     * Flatten an array to key == value
     *
     * @param array $array Array to flatten
     * @param array $keys  Array to store results, private.
     *
     * @return array
     */
    public static function arrayFlattenKeysAsValues($array, $keys = array())
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $keys[$key] = $key;
                $keys = self::arrayFlattenKeysAsValues($array[$key], $keys);
            } else {
                $keys[$key] = $key;
            }
        }
        return $keys;
    }

    /**
     * Parse localized datetime to ISO 8601 string
     *
     * @param string $date   Date to parse.
     * @param string $locale ISO 639-2 or ISO 639-3 language code.
     *
     * @return void
     */
    public static function parseLocalizedDatetime(string $date, string $locale = 'en')
    {
        return Carbon::parse($date)->locale($locale)->format('c');
    }

    /**
     * Parse localized datetimes to ISO 8601 string
     *
     * @param array  $array  Multidimensional array with datetime properties.
     * @param string $key    Name of property containing datetime.
     * @param string $locale ISO 639-2 or ISO 639-3 language code.
     *
     * @return array
     */
    public static function parseLocalizedDatetimes(array $array, string $key, string $locale = 'en')
    {
        if ($key == 'date') {
            $key = 'datetime';
        }
        foreach ($array as $index => $value) {
            if (!isset($index[$key])) {
                continue;
            }
            $array[$index][$key] = Carbon::parse($value[$key])->locale($locale)->format('c');
        }
        return $array;
    }

    /**
     * Sort multidimensional array by Datetime-string property
     *
     * @param array  $array    Array to sort.
     * @param string $orderBy  Property to sort by.
     * @param string $orderDir Direction to sort.
     *
     * @return array Sorted array.
     */
    public static function sortByDatetime(array $array, string $orderDir = 'asc', string $orderBy = 'datetime')
    {
        if ($orderBy == 'date') {
            $orderBy = 'datetime';
        }
        if ($orderDir == 'asc') {
            usort($array, function ($a, $b) use ($orderBy) {
                return strtotime($a[$orderBy]) - strtotime($b[$orderBy]);
            });
        } else {
            usort($array, function ($a, $b) use ($orderBy) {
                return strtotime($b[$orderBy]) - strtotime($a[$orderBy]);
            });
        }
        return $array;
    }

    /**
     * Cast an array into an object, recursively
     *
     * @param array $array Array to cast.
     *
     * @return stdClass
     */
    public static function toObject($array)
    {
        $obj = new \stdClass;
        foreach ($array as $k => $v) {
            if (strlen($k)) {
                if (is_array($v)) {
                    $obj->{$k} = self::toObject($v);
                } else {
                    $obj->{$k} = $v;
                }
            }
        }
        return $obj;
    }
}
