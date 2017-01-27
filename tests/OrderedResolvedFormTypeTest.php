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

use Ivory\OrderedForm\OrderedResolvedFormType;
use Ivory\OrderedForm\Orderer\FormOrderer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedResolvedFormTypeTest extends AbstractTestCase
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var FormFactoryInterface
     */
    private $factory;

    /**
     * @var OrderedResolvedFormType
     */
    private $type;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->dispatcher = $this->createMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->factory = $this->createMock('Symfony\Component\Form\FormFactoryInterface');

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $this->createMockFormType(),
            array(),
            new OrderedResolvedFormType(new FormOrderer(), $this->createMockFormType())
        );
    }

    public function testCreateBuilderWithButtonInnerType()
    {
        $innerType = $this->createMock('Symfony\Component\Form\Extension\Core\Type\ButtonType');

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
        $innerType = $this->createMock('Symfony\Component\Form\Extension\Core\Type\SubmitType');

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
     * @return AbstractType
     */
    private function createMockFormType()
    {
        return $this->createMock('Symfony\Component\Form\AbstractType');
    }

    /**
     * @return FormFactoryInterface
     */
    private function createMockFormFactory()
    {
        return $this->createMock('Symfony\Component\Form\FormFactoryInterface');
    }
}
