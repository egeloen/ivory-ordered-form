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

use Ivory\OrderedForm\Builder\OrderedFormBuilder;
use Ivory\OrderedForm\OrderedResolvedFormType;
use Ivory\OrderedForm\Orderer\FormOrderer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->factory = $this->createMock(FormFactoryInterface::class);

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $this->createMockFormType(),
            [],
            new OrderedResolvedFormType(new FormOrderer(), $this->createMockFormType())
        );
    }

    public function testCreateBuilderWithButtonInnerType()
    {
        $innerType = $this->createMock(ButtonType::class);

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $innerType,
            [],
            new OrderedResolvedFormType(new FormOrderer(), $innerType)
        );

        $this->assertInstanceOf(
            'Ivory\OrderedForm\Builder\OrderedButtonBuilder',
            $this->type->createBuilder($this->createMockFormFactory(), 'name')
        );
    }

    public function testCreateBuilderWithSubmitButtonInnerType()
    {
        $innerType = $this->createMock(SubmitType::class);

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $innerType,
            [],
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
            [],
            new OrderedResolvedFormType(new FormOrderer(), $innerType)
        );

        $this->assertInstanceOf(
            OrderedFormBuilder::class,
            $this->type->createBuilder($this->createMockFormFactory(), 'name')
        );
    }

    /**
     * @return AbstractType|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createMockFormType()
    {
        return $this->createMock(AbstractType::class);
    }

    /**
     * @return FormFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createMockFormFactory()
    {
        return $this->createMock(FormFactoryInterface::class);
    }
}
