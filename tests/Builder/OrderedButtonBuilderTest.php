<?php

/*
 * This file is part of the Ivory Ordered Form package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\OrderedForm\Builder;

use Ivory\OrderedForm\Builder\OrderedButtonBuilder;

/**
 * Ordered button builder test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedButtonBuilderTest extends AbstractOrderedBuilderTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->builder = new OrderedButtonBuilder('foo', array());
    }
}
