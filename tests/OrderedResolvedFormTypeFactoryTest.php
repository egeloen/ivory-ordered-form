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
use Ivory\OrderedForm\OrderedResolvedFormTypeFactory;
use Ivory\OrderedForm\Orderer\FormOrdererInterface;
use Symfony\Component\Form\AbstractType;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedResolvedFormTypeFactoryTest extends AbstractTestCase
{
    /**
     * @var OrderedResolvedFormTypeFactory
     */
    private $resolvedFactory;

    /**
     * @var FormOrdererInterface
     */
    private $orderer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->orderer = $this->createMock(FormOrdererInterface::class);
        $this->resolvedFactory = new OrderedResolvedFormTypeFactory($this->orderer);
    }

    public function testCreateWithOrderer()
    {
        $this->assertInstanceOf(
            OrderedResolvedFormType::class,
            $this->resolvedFactory->createResolvedType($this->createFormType(), [])
        );
    }

    public function testCreateWithoutOrderer()
    {
        $this->resolvedFactory = new OrderedResolvedFormTypeFactory();

        $this->assertInstanceOf(
            OrderedResolvedFormType::class,
            $this->resolvedFactory->createResolvedType($this->createFormType(), [])
        );
    }

    /**
     * @return AbstractType|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createFormType()
    {
        return $this->createMock(AbstractType::class);
    }
}
