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

use Ivory\OrderedForm\Builder\OrderedFormConfigBuilderInterface;
use Ivory\OrderedForm\OrderedFormConfigInterface;
use Ivory\Tests\OrderedForm\AbstractTestCase;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractOrderedBuilderTest extends AbstractTestCase
{
    /**
     * @var OrderedFormConfigBuilderInterface
     */
    private $builder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->builder = $this->createOrderedBuilder();
    }

    /**
     * @return OrderedFormConfigBuilderInterface
     */
    abstract protected function createOrderedBuilder();

    public function testDefaultState()
    {
        $this->assertInstanceOf(OrderedFormConfigInterface::class, $this->builder);
        $this->assertInstanceOf(OrderedFormConfigBuilderInterface::class, $this->builder);

        $this->assertNull($this->builder->getPosition());
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\BadMethodCallException
     * @expectedExceptionMessage The config builder cannot be modified anymore.
     */
    public function testLockedPosition()
    {
        $config = $this->builder->getFormConfig();
        $config->setPosition('first');
    }

    public function testFirstPosition()
    {
        $this->builder->setPosition('first');

        $this->assertSame('first', $this->builder->getPosition());
    }

    public function testLastPosition()
    {
        $this->builder->setPosition('last');

        $this->assertSame('last', $this->builder->getPosition());
    }

    public function testBeforePosition()
    {
        $this->builder->setPosition(['before' => 'foo']);

        $this->assertSame(['before' => 'foo'], $this->builder->getPosition());
    }

    public function testAfterPosition()
    {
        $this->builder->setPosition(['after' => 'foo']);

        $this->assertSame(['after' => 'foo'], $this->builder->getPosition());
    }

    public function testFluentInterface()
    {
        $this->assertSame($this->builder, $this->builder->setPosition('first'));
    }

    /**
     * @expectedException \Ivory\OrderedForm\Exception\OrderedConfigurationException
     * @expectedExceptionMessage The "foo" form uses position as string which can only be "first" or "last" (current: "foo").
     */
    public function testInvalidStringPosition()
    {
        $this->builder->setPosition('foo');
    }

    /**
     * @expectedException \Ivory\OrderedForm\Exception\OrderedConfigurationException
     * @expectedExceptionMessage The "foo" form uses position as array or you must define the "before" or "after" option (current: "bar").
     */
    public function testInvalidArrayPosition()
    {
        $this->builder->setPosition(['bar' => 'baz']);
    }
}
