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
class OrderedResolvedFormTypeFactoryTest extends AbstractTestCase
{
    /** @var \Ivory\OrderedForm\OrderedResolvedFormTypeFactory */
    private $resolvedFactory;

    /** @var \Ivory\OrderedForm\Orderer\FormOrdererInterface */
    private $orderer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->orderer = $this->createMock('Ivory\OrderedForm\Orderer\FormOrdererInterface');
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
     * @return \Symfony\Component\Form\AbstractType The form type.
     */
    private function createFormType()
    {
        return $this->createMock('Symfony\Component\Form\AbstractType');
    }
}
