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

use Ivory\OrderedForm\Builder\OrderedSubmitButtonBuilder;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedSubmitButtonBuilderTest extends AbstractOrderedBuilderTest
{
    /**
     * {@inheritdoc}
     */
    protected function createOrderedBuilder()
    {
        return new OrderedSubmitButtonBuilder('foo', []);
    }
}
