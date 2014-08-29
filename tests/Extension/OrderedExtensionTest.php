<?php

/*
 * This file is part of the Ivory Ordered Form package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\OrderedForm\Extension;

use Ivory\OrderedForm\Extension\OrderedExtension;
use Ivory\OrderedForm\OrderedResolvedFormTypeFactory;
use Symfony\Component\Form\Forms;

/**
 * Ordered extension test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Ivory\OrderedForm\Builder\OrderedFormBuilder */
    protected $builder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->builder = Forms::createFormFactoryBuilder()
            ->setResolvedTypeFactory(new OrderedResolvedFormTypeFactory())
            ->addExtension(new OrderedExtension())
            ->getFormFactory()
            ->createBuilder();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->builder);
    }

    /**
     * Form types data provider.
     *
     * @return array The form types.
     */
    public function formTypeProvider()
    {
        return array(
            array('text'),
            array('button'),
        );
    }

    /**
     * @dataProvider formTypeProvider
     */
    public function testEmptyPosition($type)
    {
        $form = $this->builder->create('foo', $type)->getForm();

        $this->assertNull($form->getConfig()->getPosition());
    }

    /**
     * @dataProvider formTypeProvider
     */
    public function testStringPosition($type)
    {
        $form = $this->builder->create('foo', $type, array('position' => 'first'))->getForm();

        $this->assertSame('first', $form->getConfig()->getPosition());
    }

    /**
     * @dataProvider formTypeProvider
     */
    public function testArrayPosition($type)
    {
        $form = $this->builder->create('foo', $type, array('position' => array('before' => 'bar')))->getForm();

        $this->assertSame(array('before' => 'bar'), $form->getConfig()->getPosition());
    }
}
