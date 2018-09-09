<?php
/**
 * Particle.
 *
 * @link      http://github.com/particle-php for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Particle (http://particle-php.com)
 * @license   https://github.com/particle-php/Filter/blob/master/LICENSE New BSD License
 */
namespace Particle\Filter\FilterRule;

use Particle\Filter\FilterRule;

/**
 * Class CastString
 *
 * @package Particle\Filter\FilterRule
 */
class CastString extends FilterRule
{
    /**
     * Convert the value to a string
     *
     * @param mixed $value
     * @return int
     */
    public function filter($value)
    {
        return (string) $value;
    }
}
