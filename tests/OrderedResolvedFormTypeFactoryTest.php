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
use Ivory\OrderedForm\Orderer\FormOrdererInterface;

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
        $this->orderer = $this->createMock('Ivory\OrderedForm\Orderer\FormOrdererInterface');
        $this->resolvedFactory = new OrderedResolvedFormTypeFactory($this->orderer);
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
     * @return AbstractType
     */
    private function createFormType()
    {
        return $this->createMock('Symfony\Component\Form\AbstractType');
    }
}
