<?php
namespace Timeline;

require __DIR__ . '/vendor/autoload.php';
use PHPExtra\Sorter\Sorter;
use PHPExtra\Sorter\Strategy\SimpleSortStrategy;
use PHPExtra\Sorter\Strategy\ComplexSortStrategy;
use PHPExtra\Sorter\Comparator\NumericComparator;
use PHPExtra\Sorter\Comparator\DateTimeComparator;
use PHPExtra\Sorter\Comparator\UnicodeCIComparator;

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
        $max = count($array)-1;
        $result = array($array[$max] => $info);
        for ($i=$max-1;$i>=0;$result = array($array[$i--] => $result));
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
    public static function removeKey(Array $array, String $key)
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
     * Sort multidimensional array
     * 
     * @deprecated 1.0.0
     * @see        https://stackoverflow.com/a/16788610
     *
     * @return array
     */
    public static function make_comparer()
    {
        // Normalize criteria up front so that the comparer finds everything tidy
        $criteria = func_get_args();
        foreach ($criteria as $index => $criterion) {
            $criteria[$index] = is_array($criterion)
                ? array_pad($criterion, 3, null)
                : array($criterion, SORT_ASC, null);
        }
    
        return function ($first, $second) use (&$criteria) {
            foreach ($criteria as $criterion) {
                // How will we compare this round?
                list($column, $sortOrder, $projection) = $criterion;
                $sortOrder = $sortOrder === SORT_DESC ? -1 : 1;
    
                // If a projection was defined project the values now
                if ($projection) {
                    $lhs = call_user_func($projection, $first[$column]);
                    $rhs = call_user_func($projection, $second[$column]);
                } else {
                    $lhs = $first[$column];
                    $rhs = $second[$column];
                }
    
                // Do the actual comparison; do not return if equal
                if ($lhs < $rhs) {
                    return -1 * $sortOrder;
                } elseif ($lhs > $rhs) {
                    return 1 * $sortOrder;
                }
            }
    
            return 0; // tiebreakers exhausted, so $first == $second
        };
    }

    /**
     * Sort array using PHPExtra/Sorter
     *
     * @param array  $array    Array to sort.
     * @param string $orderBy  Property to sort by.
     * @param string $orderDir Direction to sort.
     * 
     * @return array
     */
    public static function sortLeaf(Array $array, String $orderBy = 'date', String $orderDir = 'asc')
    {
        if ($orderBy == 'date') {
            $orderBy = 'datetime';
        }
        $strategy = new ComplexSortStrategy();
        if ($orderDir == 'asc') {
            $strategy->setSortOrder(Sorter::ASC);
        } elseif ($orderDir == 'desc') {
            $strategy->setSortOrder(Sorter::DESC);
        }
        $strategy->sortBy($orderBy);
        $strategy->setMaintainKeyAssociation(true);
        $sorter = new Sorter();
        return $sorter->setStrategy($strategy)->sort($array);
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