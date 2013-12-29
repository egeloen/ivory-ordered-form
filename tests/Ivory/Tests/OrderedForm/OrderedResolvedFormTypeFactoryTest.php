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

use Ivory\OrderedForm\OrderedResolvedFormTypeFactory;

/**
 * Ordered resolved form type factory test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedResolvedFormTypeFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Ivory\OrderedForm\OrderedResolvedFormTypeFactory */
    protected $resolvedFactory;

    /** @var \Ivory\OrderedForm\Orderer\FormOrdererFactoryInterface */
    protected $ordererFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->ordererFactory = $this->getMock('Ivory\OrderedForm\Orderer\FormOrdererFactoryInterface');
        $this->resolvedFactory = new OrderedResolvedFormTypeFactory($this->ordererFactory);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->ordererFactory);
        unset($this->resolvedFactory);
    }

    public function testCreate()
    {
        $this->ordererFactory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($this->getMock('Ivory\OrderedForm\Orderer\FormOrdererInterface')));

        $this->assertInstanceOf(
            'Ivory\OrderedForm\OrderedResolvedFormType',
            $this->resolvedFactory->createResolvedType(
                $this->getMock('Symfony\Component\Form\FormTypeInterface'),
                array()
            )
        );
    }
}
