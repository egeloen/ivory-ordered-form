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
use Ivory\OrderedForm\Exception\OrderedConfigurationException;
use Ivory\OrderedForm\OrderedFormConfigInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Exception\BadMethodCallException;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractOrderedBuilderTest extends TestCase
{
    private OrderedFormConfigInterface&OrderedFormConfigBuilderInterface $builder;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->builder = $this->createOrderedBuilder();
    }

    abstract protected function createOrderedBuilder(): OrderedFormConfigInterface&OrderedFormConfigBuilderInterface;

    public function testDefaultState(): void
    {
        $this->assertInstanceOf(OrderedFormConfigInterface::class, $this->builder);
        $this->assertInstanceOf(OrderedFormConfigBuilderInterface::class, $this->builder);

        $this->assertNull($this->builder->getPosition());
    }

    public function testLockedPosition(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('The config builder cannot be modified anymore.');

        /** @var OrderedFormConfigBuilderInterface $config */
        $config = $this->builder->getFormConfig();
        $config->setPosition('first');
    }

    public function testFirstPosition(): void
    {
        $this->builder->setPosition('first');

        $this->assertSame('first', $this->builder->getPosition());
    }

    public function testLastPosition(): void
    {
        $this->builder->setPosition('last');

        $this->assertSame('last', $this->builder->getPosition());
    }

    public function testBeforePosition(): void
    {
        $this->builder->setPosition(['before' => 'foo']);

        $this->assertSame(['before' => 'foo'], $this->builder->getPosition());
    }

    public function testAfterPosition(): void
    {
        $this->builder->setPosition(['after' => 'foo']);

        $this->assertSame(['after' => 'foo'], $this->builder->getPosition());
    }

    public function testFluentInterface(): void
    {
        $this->assertSame($this->builder, $this->builder->setPosition('first'));
    }

    public function testInvalidStringPosition(): void
    {
        $this->expectException(OrderedConfigurationException::class);
        $this->expectExceptionMessage('The "foo" form uses position as string which can only be "first" or "last" (current: "foo").');

        $this->builder->setPosition('foo');
    }

    public function testInvalidArrayPosition(): void
    {
        $this->expectException(OrderedConfigurationException::class);
        $this->expectExceptionMessage('The "foo" form uses position as array or you must define the "before" or "after" option (current: "bar").');

        $this->builder->setPosition(['bar' => 'baz']);
    }
}
