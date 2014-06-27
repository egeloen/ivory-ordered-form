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
    protected $orderer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->orderer = $this->getMock('Ivory\OrderedForm\Orderer\FormOrdererInterface');
        $this->resolvedFactory = new OrderedResolvedFormTypeFactory($this->orderer);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->orderer);
        unset($this->resolvedFactory);
    }

    public function testCreateWithOrderer()
    {
        $this->assertInstanceOf(
            'Ivory\OrderedForm\OrderedResolvedFormType',
            $this->resolvedFactory->createResolvedType($this->createFormType(), array())
        );
    }

    public function testCreateWithoutOrderer()
    {
        $this->resolvedFactory = new OrderedResolvedFormTypeFactory();

        $this->assertInstanceOf(
            'Ivory\OrderedForm\OrderedResolvedFormType',
            $this->resolvedFactory->createResolvedType($this->createFormType(), array())
        );
    }

    /**
     * Creates a form type.
     *
     * @return \Symfony\Component\Form\FormTypeInterface The form type.
     */
    protected function createFormType()
    {
        return $this->getMock('Symfony\Component\Form\FormTypeInterface');
    }
}
