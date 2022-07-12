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
use Ivory\OrderedForm\Builder\OrderedFormConfigBuilderInterface;
use Ivory\OrderedForm\OrderedFormConfigInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedFormBuilderTest extends AbstractOrderedBuilderTest
{
    protected function createOrderedBuilder(): OrderedFormConfigInterface&OrderedFormConfigBuilderInterface
    {
        /** @var EventDispatcherInterface $eventDispatcherMock */
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        /** @var FormFactoryInterface $formFactoryMock */
        $formFactoryMock = $this->createMock(FormFactoryInterface::class);

        return new OrderedFormBuilder(
            'foo',
            null,
            $eventDispatcherMock,
            $formFactoryMock
        );
    }
}
