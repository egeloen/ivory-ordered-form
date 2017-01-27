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

use Ivory\OrderedForm\Builder\OrderedFormBuilder;
use Ivory\OrderedForm\Extension\OrderedExtension;
use Ivory\OrderedForm\OrderedResolvedFormTypeFactory;
use Ivory\Tests\OrderedForm\AbstractTestCase;
use Symfony\Component\Form\Forms;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedExtensionTest extends AbstractTestCase
{
    /**
     * @var OrderedFormBuilder
     */
    private $builder;

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
     * @param string $type
     *
     * @dataProvider formTypeProvider
     */
    public function testEmptyPosition($type)
    {
        $form = $this->builder->create('foo', $type)->getForm();

        $this->assertNull($form->getConfig()->getPosition());
    }

    /**
     * @param string $type
     *
     * @dataProvider formTypeProvider
     */
    public function testStringPosition($type)
    {
        $form = $this->builder->create('foo', $type, array('position' => 'first'))->getForm();

        $this->assertSame('first', $form->getConfig()->getPosition());
    }

    /**
     * @param string $type
     *
     * @dataProvider formTypeProvider
     */
    public function testArrayPosition($type)
    {
        $form = $this->builder->create('foo', $type, array('position' => array('before' => 'bar')))->getForm();

        $this->assertSame(array('before' => 'bar'), $form->getConfig()->getPosition());
    }

    /**
     * @return array
     */
    public function formTypeProvider()
    {
        $fqcn = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix');

        return array(
            array($fqcn ? 'Symfony\Component\Form\Extension\Core\Type\TextType' : 'text'),
            array($fqcn ? 'Symfony\Component\Form\Extension\Core\Type\ButtonType' : 'button'),
        );
    }
}
