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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedResolvedFormTypeTest extends TestCase
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
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->factory = $this->createMock(FormFactoryInterface::class);
    }

    private function getOrderedResolvedFormTypeByFormType(FormTypeInterface $formType): OrderedResolvedFormType
    {
        return new OrderedResolvedFormType(
            new FormOrderer(),
            $formType,
            [],
            new OrderedResolvedFormType(new FormOrderer(), $formType)
        );
    }

    public function testCreateBuilderWithButtonInnerType()
    {
        /** @var ButtonType $innerType */
        $innerType = $this->createMock(ButtonType::class);
        $type = $this->getOrderedResolvedFormTypeByFormType($innerType);

        $this->assertInstanceOf(
            'Ivory\OrderedForm\Builder\OrderedButtonBuilder',
            $type->createBuilder($this->createMockFormFactory(), 'name')
        );
    }

    public function testCreateBuilderWithSubmitButtonInnerType()
    {
        /** @var SubmitType $innerType */
        $innerType = $this->createMock(SubmitType::class);
        $type = $this->getOrderedResolvedFormTypeByFormType($innerType);

        $this->assertInstanceOf(
            'Ivory\OrderedForm\Builder\OrderedSubmitButtonBuilder',
            $type->createBuilder($this->createMockFormFactory(), 'name')
        );
    }

    public function testCreateBuilderWithFormInnerType()
    {
        /** @var AbstractType $innerType */
        $innerType = $this->createMock(AbstractType::class);
        $type = $this->getOrderedResolvedFormTypeByFormType($innerType);

        $this->assertInstanceOf(
            OrderedFormBuilder::class,
            $type->createBuilder($this->createMockFormFactory(), 'name')
        );
    }

    /**
     * @return FormFactoryInterface|MockObject
     */
    private function createMockFormFactory()
    {
        return $this->createMock(FormFactoryInterface::class);
    }
}
