<?php

/*
 * This file is part of the Ivory Ordered Form package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedForm\Exception;

use Symfony\Component\Form\Exception\InvalidConfigurationException;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedConfigurationException extends InvalidConfigurationException
{
    /**
     * @param array  $stack
     * @param string $position
     *
     * @return OrderedConfigurationException
     */
    public static function createCircularDiffered(array $stack, $position)
    {
        $stack[] = $stack[0];

        return new self(sprintf(
            'The form ordering cannot be resolved due to conflict in %s positions (%s).',
            $position,
            implode(' => ', self::decorateValues($stack))
        ));
    }

    /**
     * @param string $name
     * @param string $position
     * @param string $differed
     *
     * @return OrderedConfigurationException
     */
    public static function createInvalidDiffered($name, $position, $differed)
    {
        $decoratedDiffered = self::decorateValue($differed);

        return new self(sprintf(
            'The %s form is configured to be placed just %s the form %s but the form %s does not exist.',
            self::decorateValue($name),
            $position,
            $decoratedDiffered,
            $decoratedDiffered
        ));
    }

    /**
     * @param string $name
     * @param string $position
     *
     * @return OrderedConfigurationException
     */
    public static function createInvalidStringPosition($name, $position)
    {
        return new self(sprintf(
            'The %s form uses position as string which can only be "first" or "last" (current: %s).',
            self::decorateValue($name),
            self::decorateValue($position)
        ));
    }

    /**
     * @param string $name
     * @param array  $position
     *
     * @return OrderedConfigurationException
     */
    public static function createInvalidArrayPosition($name, array $position)
    {
        return new self(sprintf(
            'The %s form uses position as array or you must define the "before" or "after" option (current: %s).',
            self::decorateValue($name),
            implode(', ', self::decorateValues(array_keys($position)))
        ));
    }

    /**
     * @param string $name
     * @param string $symetric
     *
     * @return OrderedConfigurationException
     */
    public static function createSymetricDiffered($name, $symetric)
    {
        return new self(sprintf(
            'The form ordering does not support symetrical before/after option (%s <=> %s).',
            self::decorateValue($name),
            self::decorateValue($symetric)
        ));
    }

    /**
     * @param array  $values
     * @param string $decorator
     *
     * @return array
     */
    private static function decorateValues(array $values, $decorator = '"')
    {
        $result = [];

        foreach ($values as $key => $value) {
            $result[$key] = self::decorateValue($value, $decorator);
        }

        return $result;
    }

    /**
     * @param string $value
     * @param string $decorator
     *
     * @return string
     */
    private static function decorateValue($value, $decorator = '"')
    {
        return $decorator.$value.$decorator;
    }
}
