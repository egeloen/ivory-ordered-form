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

use Ivory\OrderedForm\Builder\OrderedFormBuilder;

/**
 * Ordered form builder test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedFormBuilderTest extends AbstractOrderedBuilderTest
{
    /**
     * {@inheritdoc}
     */
    protected function createOrderedBuilder()
    {
        return new OrderedFormBuilder(
            'foo',
            null,
            $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface'),
            $this->createMock('Symfony\Component\Form\FormFactoryInterface')
        );
    }
}
