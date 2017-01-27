<?php

/*
 * This file is part of the Ivory Ordered Form package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\OrderedForm\Builder;

use Ivory\OrderedForm\Exception\OrderedConfigurationException;
use Ivory\OrderedForm\OrderedFormConfigInterface;
use Symfony\Component\Form\ButtonBuilder;
use Symfony\Component\Form\Exception\BadMethodCallException;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedButtonBuilder extends ButtonBuilder implements OrderedFormConfigBuilderInterface, OrderedFormConfigInterface
{
    /**
     * @var string|array|null
     */
    private $position;

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        if ($this->locked) {
            throw new BadMethodCallException('The config builder cannot be modified anymore.');
        }

        if (is_string($position) && ($position !== 'first') && ($position !== 'last')) {
            throw OrderedConfigurationException::createInvalidStringPosition($this->getName(), $position);
        }

        if (is_array($position) && !isset($position['before']) && !isset($position['after'])) {
            throw OrderedConfigurationException::createInvalidArrayPosition($this->getName(), $position);
        }

        $this->position = $position;

        return $this;
    }
}
