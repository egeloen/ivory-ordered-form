<?php

/*
 * This file is part of the Ivory Ordered Form package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\OrderedForm;

use Ivory\OrderedForm\Orderer\FormOrderer;
use Ivory\OrderedForm\OrderedResolvedFormType;

/**
 * Ordered resolved form type test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedResolvedFormTypeTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Symfony\Component\EventDispatcher\EventDispatcher */
    protected $dispatcher;

    /** @var \Symfony\Component\Form\FormFactoryInterface */
    protected $factory;

    /** @var \Ivory\OrderedForm\OrderedResolvedFormType */
    protected $type;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->factory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $this->createMockFormType(),
            array(),
            new OrderedResolvedFormType(new FormOrderer(), $this->createMockFormType())
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->dispatcher);
        unset($this->factory);
        unset($this->dataMapper);
        unset($this->type);
    }

    public function testCreateBuilderWithButtonInnerType()
    {
        $innerType = $this->getMock('Symfony\Component\Form\ButtonTypeInterface');

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $innerType,
            array(),
            new OrderedResolvedFormType(new FormOrderer(), $innerType)
        );

        $this->assertInstanceOf(
            'Ivory\OrderedForm\Builder\OrderedButtonBuilder',
            $this->type->createBuilder($this->createMockFormFactory(), 'name')
        );
    }

    public function testCreateBuilderWithSubmitButtonInnerType()
    {
        $innerType = $this->getMock('Symfony\Component\Form\SubmitButtonTypeInterface');

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $innerType,
            array(),
            new OrderedResolvedFormType(new FormOrderer(), $innerType)
        );

        $this->assertInstanceOf(
            'Ivory\OrderedForm\Builder\OrderedSubmitButtonBuilder',
            $this->type->createBuilder($this->createMockFormFactory(), 'name')
        );
    }

    public function testCreateBuilderWithFormInnerType()
    {
        $innerType = $this->createMockFormType();

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $innerType,
            array(),
            new OrderedResolvedFormType(new FormOrderer(), $innerType)
        );

        $this->assertInstanceOf(
            'Ivory\OrderedForm\Builder\OrderedFormBuilder',
            $this->type->createBuilder($this->createMockFormFactory(), 'name')
        );
    }

    /**
     * Creates a form type mock.
     *
     * @return \Symfony\Component\Form\FormTypeInterface The form type mock.
     */
    protected function createMockFormType()
    {
        return $this->getMock('Symfony\Component\Form\FormTypeInterface');
    }

    /**
     * Creates a form factory mock.
     *
     * @return \Symfony\Component\Form\FormFactoryInterface The form factory mock.
     */
    protected function createMockFormFactory()
    {
        return $this->getMock('Symfony\Component\Form\FormFactoryInterface');
    }
}
