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

use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\FormConfigBuilderInterface;

/**
 * Ordered form configuration builder.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
interface OrderedFormConfigBuilderInterface extends FormConfigBuilderInterface
{
    /**
     * @param string|array|null $position
     *
     * @throws InvalidConfigurationException
     *
     * @return OrderedFormConfigBuilderInterface
     */
    public function setPosition($position);
}
